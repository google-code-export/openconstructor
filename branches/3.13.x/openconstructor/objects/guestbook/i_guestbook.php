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
 * $Id: i_guestbook.php,v 1.15 2007/03/09 20:37:11 sanjar Exp $
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
			case 'edit_gballmessages':
				$obj->header=@$_POST['header'];
				$obj->ds_id=@$_POST['ds_id'] ? implode(',', $_POST['ds_id']) : null;
		
				$obj->defaultGB=intval(@$_POST['defaultgb']);
				if(!isset($_POST['ds_id'])){
					$obj->ds_id = null;
				}else if(is_array(@$_POST['ds_id'])){
					if(array_search($obj->defaultGB,$_POST['ds_id'])===false)
						$obj->ds_id.=','.$_POST['defaultgb'];
				}else if(@$_POST['ds_id']!=@$_POST['defaultgb'])
					$obj->ds_id.=($obj->ds_id?',':'').$_POST['defaultgb'];
				$obj->gbid=@$_POST['gbid'];
		
				$obj->dateFormat=@$_POST['dateformat'];
				$obj->reverseOrder=@$_POST['reverseorder']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_gbaddmsglogic':
				$obj->ds_id = @$_POST['ds_id'] ? implode(',', $_POST['ds_id']) : null;
		
				$obj->defaultGB = intval(@$_POST['defaultgb']);
				if($obj->ds_id) {
					if(is_array(@$_POST['ds_id'])){
						if(array_search($obj->defaultGB, $_POST['ds_id'])===false)
							$obj->ds_id .= ','.$_POST['defaultgb'];
					} elseif($obj->ds_id != $obj->defaultGB)
						$obj->ds_id .= ','.$obj->defaultGB;
				}
				$obj->gbid = @$_POST['gbid'];
		
				$obj->notifyEmail = @$_POST['notifyEmail'] ? $_POST['notifyEmail'] : null;
				if($obj->notifyEmail)
					$obj->mailSubject = (string) @$_POST['mailSubject'];
					
				foreach($obj->fields as $k=>$v){
					$obj->fields[$k]['required']=@$_POST['r_'.$k]=='true';
					if(@$_POST[$k.'id'])
						$obj->fields[$k]['id']=$_POST[$k.'id'];
				}
				
				$obj->cId = @$_POST['cId'];
				$obj->cVal = $obj->cId ? @$_POST['cVal'] : $obj->cVal;
				$obj->closeSess = @$_POST['closeSess'] == 'true';
				
				$obj->policy = (int) @$_POST['policy'];
				if($obj->policy == GBAML_ADD_AS_SPECIFIED)
					$obj->addAs = @$_POST['addAs'];
				unset($obj->ignoreDsAuths);
				$obj->ignoreDsAuths = isset($_POST['ignoreDsAuths']);
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_gblist':
				$obj->header=@$_POST['header'];
				$obj->ds_id=@$_POST['ds_id'] ? implode(',', $_POST['ds_id']) : null;
				$obj->reverseOrder=@$_POST['reverseorder']=='true';
				$obj->dateFormat=@$_POST['dateformat'];
				$obj->srvuri=@$_POST['srvuri'];
				$obj->gbid=@$_POST['gbid'];
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_gbmsgbody':
				$obj->header=@$_POST['header'];
				$obj->ds_id = @$_POST['ds_id'] ? implode(',',$_POST['ds_id']) : null;
				if(@$_POST['messageid']) $obj->messageid=$_POST['messageid'];
				$obj->dateFormat=@$_POST['dateformat'];
				$obj->showUnpublished=@$_POST['showunpublished']=='true';
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;

			case 'edit_gbmsghl':
				$obj->header=@$_POST['header'];
				$obj->ds_id=@$_POST['ds_id'] ? implode(',', $_POST['ds_id']) : null;
				$obj->offset=@$_POST['offset']>0?$_POST['offset']:0;
				$obj->pageSize=@$_POST['pagesize']>0?$_POST['pagesize']:10;
				if(@$_POST['messageid']) $obj->messageid=$_POST['messageid'];
				if(@$_POST['pid']) $obj->pid=$_POST['pid'];
				if(@$_POST['srvuri']) $obj->srvuri=$_POST['srvuri'];
				$obj->dateFormat=@$_POST['dateformat'];
				$obj->more=@$_POST['more'];
				$obj->keywordKey = @$_POST['keywordKey'];
				$obj->reverseOrder=@$_POST['reverseorder']=='true';
				$obj->fetchContent=@$_POST['fetchcontent']=='true';
				$obj->showUnpublished=@$_POST['showunpublished']=='true';
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;

			case 'edit_gbpager':
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
