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
 * $Id: i_event.php,v 1.13 2007/03/02 10:06:41 sanjar Exp $
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
			case 'edit_eventcalendar':
				unset($obj->localeTime);
				$obj->header=@$_POST['header'];
				if(intval(@$_POST['year'])>0)
					$obj->year = $_POST['year'];
				$month = explode(',', @$_POST['month']);
				$obj->months=sizeof($month)==12?$month:$obj->months;
				$obj->ds_id=@$_POST['ds_id'] ? implode(',', $_POST['ds_id']) : null;
				if(@$_POST['srvuri']) $obj->srvuri=$_POST['srvuri'];
				if(@$_POST['eventid']) $obj->eventid=$_POST['eventid'];
				if(@$_POST['monthid']) $obj->monthid=$_POST['monthid'];
				$obj->dateFormat=@$_POST['dateformat'];
				$obj->moWeek = @$_POST['moWeek']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_eventbody':
				$obj->header=@$_POST['header'];
				$obj->ds_id = @$_POST['ds_id'] ? implode(',',$_POST['ds_id']) : null;
				if(@$_POST['eventid']) $obj->eventid=$_POST['eventid'];
				$obj->dateFormat=@$_POST['dateformat'];
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_eventhl':
				$obj->ds_id=@$_POST['ds_id'] ? implode(',', $_POST['ds_id']) : null;
				$obj->header=@$_POST['header'];
				$obj->offset=@$_POST['offset']>0?$_POST['offset']:0;
				$obj->pageSize=@$_POST['pagesize']>0?$_POST['pagesize']:10;
				if(@$_POST['srvuri']) $obj->srvuri=$_POST['srvuri'];
				if(@$_POST['eventid']) $obj->eventid=$_POST['eventid'];
				if(@$_POST['pid']) $obj->pid=$_POST['pid'];
				$obj->dateFormat=@$_POST['dateformat'];
				$obj->comingEventsOnly=@$_POST['comingEventsOnly']=='true';
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_eventhlintro':
				$obj->ds_id=@$_POST['ds_id'] ? implode(',', $_POST['ds_id']) : null;
				$obj->header=@$_POST['header'];
				$obj->offset=@$_POST['offset']>0?$_POST['offset']:0;
				$obj->pageSize=@$_POST['pagesize']>0?$_POST['pagesize']:10;
				if(@$_POST['srvuri']) $obj->srvuri=$_POST['srvuri'];
				if(@$_POST['eventid']) $obj->eventid=$_POST['eventid'];
				if(@$_POST['pid']) $obj->pid=$_POST['pid'];
				if(intval(@$_POST['cutintro'])>0)
					$obj->cutIntro=intval($_POST['cutintro']);
				$obj->dateFormat=@$_POST['dateformat'];
				$obj->more=@$_POST['more'];
				$obj->keywordKey = @$_POST['keywordKey'];
				$obj->sortByRank = @$_POST['sortByRank'] == 'true';
				$obj->noResTpl = (int) @$_POST['noResTpl'];
				$obj->comingEventsOnly=@$_POST['comingEventsOnly']=='true';
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_eventpager':
				$obj->header=@$_POST['header'];
		//		$obj->ds_id=implode(',',$_POST['ds_id']);
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
	die();
?>
