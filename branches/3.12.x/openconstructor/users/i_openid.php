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
 * $Id: selectuser.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	require_once(LIBDIR_THIRD.'/openid/common.php');
	WCS::requireAuthentication();
switch(@$_GET['action'])
{
	case 'add':
		$openid = $_GET['identifier'];
		$userId = $_GET['user_id'];
		$consumer = getConsumer();
		$next = 'openconstructor/users/i_openid.php?action=register&user_id='.$userId;
		
		$auth_request = $consumer->begin($openid);
	
		if (!$auth_request)
			sendRedirect($_SERVER['HTTP_REFERER'] . '?failed&error=Authentication error; not a valid OpenID.');
		
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
	
	case 'remove':
		require_once(LIBDIR.'/security/userfactory._wc');
		$userId = $_GET['user_id'];
		$identity = $_GET['openid'];
		WCS::runAs(WCS_ROOT_ID);
		$uf = UserFactory::getInstance();
		$user = User::load($userId);
		$uf->removeOpenid($user, $identity);
		WCS::stopRunAs();
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/openconstructor/users/edituser.php?id='.$userId);
		
	break;
	
	case 'register':
		require_once(LIBDIR.'/security/userfactory._wc');
		$consumer = getConsumer();
		$userId = $_GET['user_id'];
		
		$return_to = getReturnTo('openconstructor/users/i_openid.php');
	    $response = $consumer->complete($return_to);
	
		if ($response->status == Auth_OpenID_CANCEL) {
			sendRedirect($_SERVER['HTTP_REFERER'] . '?login=cancel');
		} else if ($response->status == Auth_OpenID_FAILURE) {
			sendRedirect($_SERVER['HTTP_REFERER'] . '?login=fail&message='.$response->message);
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
			
			WCS::runAs(WCS_ROOT_ID);
			$uf = UserFactory::getInstance();
			$user = User::load($userId);
			$uf->addOpenid($user, $identity);
			WCS::stopRunAs();
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/openconstructor/users/edituser.php?id='.$userId);
			
		}
	break;
	
	
	default:
	break;
}

?>