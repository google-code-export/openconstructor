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
 * $Id: i_phpsource.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
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
			case 'edit_phpinclude':
				$obj->ds_id=@$_POST['ds_id'];
				$obj->header=@$_POST['header'];
				if(intval(@$_POST['source'])>0)
					$obj->source=$_POST['source'];
				else
					$obj->source=false;
				if(@$_POST['once']=='true')
					$obj->once=true;
				else
					$obj->once=false;
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_phpcallback':
				$obj->ds_id = (int) @$_POST['ds_id'];
				if(intval(@$_POST['source']) > 0)
					$obj->source = (int) $_POST['source'];
				else
					$obj->source = false;
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			default:
			break;
		}
	}
?>
