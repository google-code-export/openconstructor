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
 * $Id: i_gallery.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	
	if (isset($_POST['action'])) {
		$obj = &ObjManager::load(@$_POST['obj_id']);
		assert($obj != null);
		$obj->name=@$_POST['name'];
		$obj->description=@$_POST['description'];
		switch(@$_POST['action'])
		{
			case 'edit_galleryhl':
				$obj->header=@$_POST['header'];
				if(@$_POST['dyn_ds_id']||@$_POST['ds_id']){
					$obj->dynamic_ds=@$_POST['dynamic_ds']=='true';
					$obj->ds_id=@$obj->dynamic_ds&&@$_POST['dyn_ds_id']?$_POST['dyn_ds_id']:implode(',',$_POST['ds_id']);
				}
				$obj->pageSize=@$_POST['pagesize']>0?$_POST['pagesize']:10;
				if(intval(@$_POST['cutintro'])>0)
					$obj->cutIntro=intval($_POST['cutintro']);
				else
					$obj->cutIntro=NULL;
				if(@$_POST['pid']) $obj->pid=$_POST['pid'];
				if(@$_POST['srvuri']) $obj->srvuri=$_POST['srvuri'];
				if(@$_POST['imageid']) $obj->imageid=$_POST['imageid'];
				$obj->keywordKey = @$_POST['keywordKey'];
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_galleryimage':
				$obj->header=@$_POST['header'];
				if(@$_POST['dyn_ds_id']||@$_POST['ds_id']){
					$obj->dynamic_ds=@$_POST['dynamic_ds']=='true';
					$obj->ds_id=@$obj->dynamic_ds&&@$_POST['dyn_ds_id']?$_POST['dyn_ds_id']:implode(',',$_POST['ds_id']);
					$obj->isPub=@$_POST['isPub'];
				}
				if(@$_POST['imageid']) $obj->imageid=$_POST['imageid'];
				$obj->byId = @$_POST['byId'] == 'true';
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_gallerypager':
				$obj->header = @$_POST['header'];
				$obj->pagerSize=@$_POST['pagersize']>0?$_POST['pagersize']:10;
				$obj->master=@$_POST['master_obj'];
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_galleryimgpager':
				$obj->header=@$_POST['header'];
				$obj->pagerSize=@$_POST['pagersize']>0?$_POST['pagersize']:10;
				$obj->master=@$_POST['master_obj'];
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			default:
			break;
		}
	}
?>
