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
	Authentication::destroy();
	
	$auth = &Authenticator::authenticate(@$_POST['login'],@$_POST['password']);
	if(is_object($auth)) {
		if(WCS::inGroup(System::getInstance(), $auth->membership))
			$auth->fetchProfile();
		$auth->exportToSession();
		if(isset($_POST['remember'])) {
			Authentication::exportUID(30);// remember for 30 days
		}
		$next = @$_POST['next'] ? $_POST['next'] : WCHOME.'/';
		if(strpos($next, $GLOBALS['_wchome']) === 0)
			echo '<pre>Opening <script>page="'.addslashes('http://'.$_host.$next).'"; document.write(page); location.href = page; </script>';
		else
			header('Location: http://'.$_host.$next);
	} else {
		Authentication::destroyHistory();
		header('Location: http://'.$_host.WCHOME.'/login.php?failed&next='.urlencode(@$_POST['next']));
	}
?>