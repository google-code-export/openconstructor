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
 * $Id: i_search.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
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
			case 'edit_searchdss':
				$obj->header=@$_POST['header'];
				$obj->ds_id = @$_POST['ds_id'] ? implode(',', $_POST['ds_id']) : null;
				if(@$_POST['keywordKey'])
					$obj->keywordKey = trim($_POST['keywordKey']);
				$obj->listSize = (int) @$_POST['listSize'];
				$obj->no404=@$_POST['no404']=='true';
				$obj->hrefs = array();
				foreach((array) @$_POST['ds_id'] as $id)
					if(@$_POST['hrefs'][$id])
						$obj->hrefs[$id] = @$_POST['hrefs'][$id];
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_searchdsspager':
				$obj->header=@$_POST['header'];
				$obj->pageNumberKey = @$_POST['pageNumberKey'];
				$obj->pagerSize = @$_POST['pagersize'] > 0 ? $_POST['pagersize'] : 10;
				$obj->listSizeKey = @$_POST['listSizeKey'];
				$obj->slave = @$_POST['master_obj'];
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			default:
			break;
		}
	}
?>
