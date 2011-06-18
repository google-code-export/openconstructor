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
 * $Id: i_users.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	
	if (isset($_POST['action'])) {
		$obj = ObjManager::load(@$_POST['obj_id']);
		assert($obj != null);
		$obj->name=@$_POST['name'];
		$obj->description=@$_POST['description'];
		switch(@$_POST['action'])
		{
			case 'edit_usersauthorize':
				$obj->loginID = @$_POST['loginid'];
				$obj->passwordID = @$_POST['passwordid'];
				$obj->defaultNextPage = @$_POST['defaultNextPage'];
				$obj->nextPageKey = @$_POST['nextPageKey'];
				$obj->loginPageKey = @$_POST['loginPageKey'];
				$obj->allowAutoLogin = min(max((int) @$_POST['allowAutoLogin'], 0), 365 * 10);
				if($obj->allowAutoLogin)
					$obj->autoLoginID = @$_POST['autoLoginID'];
				$obj->homes = array();
				foreach((array) @$_POST['homes'] as $id => $uri)
					if(intval($id) > 0 && trim($uri))
						$obj->homes[(int) $id] = trim($uri);
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_userslogout':
				$obj->killSession = isset($_POST['killSession']);
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			default:
			break;
		}
	}
?>
