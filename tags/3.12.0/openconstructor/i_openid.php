<?php
/**
 * Copyright 2003 - 2007 eSector Solutions, LLC
 * 
 * All rights reserved.
 * 
 * This file is part of Open Constructor (http://www.openconstructor.org/).
 * 
 * Open Constructor is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 2
 * as published by the Free Software Foundation.
 * 
 * Open Constructor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html
 * 
 * $Id: i_login.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	require_once('lib/security/authenticator._wc');
	require_once(LIBDIR_THIRD.'/openid/common.php');
	Authentication::destroy();
	
switch(@$_GET['action'])
{
	case 'verify':
		$openid = $_GET['identifier'];
		$consumer = getConsumer();
		$next = 'openconstructor/i_openid.php?action=login&next=' . (@$_GET['next'] ? $_GET['next'] : WCHOME.'/');
		
		$auth_request = $consumer->begin($openid);
	
		if (!$auth_request)
			sendRedirect('http://'.$_host.WCHOME.'/login.php?failed&error=Authentication error; not a valid OpenID.');
		
		$sreg_request = Auth_OpenID_SRegRequest::build(
				// Required
				array('nickname'),
				// Optional
				array('fullname', 'email')
			);
		
		if ($sreg_request)
			$auth_request->addExtension($sreg_request);
		
		if ($auth_request->shouldSendRedirect()) {
			$redirect_url = $auth_request->redirectURL(getTrustRoot(),
							getReturnTo($next), false);
			
			if (Auth_OpenID::isFailure($redirect_url))
				displayError("Could not redirect to server: " . $redirect_url->message);
			else
				sendRedirect($redirect_url);
			
		} else {
			$form_id = 'openid_message';
			$form_html = $auth_request->htmlMarkup(getTrustRoot(), getReturnTo($next),
							false, array('id' => $form_id));
			
			if (Auth_OpenID::isFailure($form_html)) {
				displayError("Could not redirect to server: " . $form_html->message);
			} else {
				print $form_html;
			}
		}
	break;
	
	case 'login':
		require_once(LIBDIR.'/security/userfactory._wc');
		require_once(LIBDIR.'/security/groupfactory._wc');
		$consumer = getConsumer();
		
		$return_to = getReturnTo('openconstructor/i_openid.php');
	    $response = $consumer->complete($return_to);
	
		if ($response->status == Auth_OpenID_CANCEL) {
			sendRedirect('/openconstructor/login.php?login=cancel');
		} else if ($response->status == Auth_OpenID_FAILURE) {
			sendRedirect('/openconstructor/login.php/?login=fail&message='.$response->message);
		} else if ($response->status == Auth_OpenID_SUCCESS) {
			$openid = $response->getDisplayIdentifier();
			$esc_identity = escape($openid);
			
			$identity = $response->getSigned(Auth_OpenID_OPENID2_NS, 'identity');
			$displayIdentity = $esc_identity;
			
			if ($response->endpoint->canonicalID) {
				$escaped_canonicalID = escape($response->endpoint->canonicalID);
			}
			
			$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
			
			$sreg = $sreg_resp->contents();
			$nickname = escape($sreg['nickname']);
			// TODO Нужны ли эти поля?
//			$email = escape(@$sreg['email']);
//			$fullname = @$sreg['fullname'];
			
			$auth = Authenticator::openidAuthenticate($identity);
			if(!is_object($auth)) {
				WCS::runAs(WCS_ROOT_ID);
				$uf = UserFactory::getInstance();
				$login = $uf->generatePwd(9);
				while(!$uf->isLoginAvailable($login))
					$login = $uf->generatePwd(9);
				$pwd = $uf->generatePwd(16);
				$user = new User($login, $nickname);
				$user->pwd = $pwd;
				$user->openids = array($identity);
				$user->active = true;
				$ug = GroupFactory::getGroupByName('everyone');
				$uf->createUser($ug->id, $user);
				WCS::stopRunAs();
				
				$auth = Authenticator::openidAuthenticate($identity);
			}
			
			if(WCS::inGroup(System::getInstance(), $auth->membership))
				$auth->fetchProfile();
			$auth->exportToSession();
			if(isset($_GET['remember'])) {
				Authentication::exportUID(30);// remember for 30 days
			}
			$next = @$_GET['next'] ? $_GET['next'] : WCHOME.'/';
			if(strpos($next, $GLOBALS['_wchome']) === 0)
				echo '<pre>Opening <script>page="'.addslashes('http://'.$_host.$next).'"; document.write(page); location.href = page; </script>';
			else
				header('Location: http://'.$_host.$next);
		}
	break;
	
	default:
	break;
}
?>