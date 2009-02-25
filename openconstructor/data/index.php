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
 * $Id: index.php,v 1.14 2007/03/10 11:04:01 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	require_once('../include/sections._wc');
	
	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;
	
	$_dsm=new DSManager();
	$siteroot='dataRoot';
	$map[$siteroot]=array(DATASOURCES=>$_dsm->getTree());
	$tmp = @$_GET['node']?$_GET['node']:@$_COOKIE['curnode'];
	$curnode = $_dsm->exists($tmp) ? $tmp : $_dsm->first;
	if(!($ds = &$_dsm->load($curnode))) {
		$curnode = 0;
	} elseif($ds->isInternal) {
		sendRedirect('http://'.$_SERVER['HTTP_HOST'].WCHOME.'/data/internal_ds.php?node='.$ds->ds_id);
		die();
	}
	$nodetype = @$ds->ds_type;
	
	$isInternal = ( @$ds->isInternal ? true : false );
	$isLocked = ( @$ds->lock ? true : false );
	
	setcookie('curnode',$curnode,0,WCHOME.'/data/');
	if(!isset($_COOKIE['vd']['img_intro'])) {
		setcookie('vd[img_intro]','disabled',0,WCHOME.'/data/');
		$img_intro_just_disabled = true;
	}
	
	$opened[$nodetype]=true;
	$opened[$curnode]=true;
	$opened[$siteroot]=true;
	
	$smartybackend->assign("isInternal", $isInternal);
	$smartybackend->assign("isLocked", $isLocked);
	$smartybackend->assign("curnode", $curnode);
	$smartybackend->assign("nodetype", $nodetype);
	$smartybackend->assign("cur_section", 'data');
	$smartybackend->assign_by_ref("auth", $auth);

	$smartybackend->assign("menu", getTabs('data'));
	
	include('toolbar._wc');
	$smartybackend->assign_by_ref("toolbar", $toolbar);
	
	$smartybackend->assign("map", print_tree($map));
	
	if(intval($curnode)>0) {
		include($nodetype.'/headline._wc');
		$smartybackend->assign("fields", $fields);
		$smartybackend->assign("docs", $hl);
	    $smartybackend->assign("editor_width", $eWidth);
		$smartybackend->assign("editor_height", $eHeight);
		$smartybackend->assign("editor", $editor);
		$smartybackend->assign("icon", $icon);
	    $smartybackend->assign("fieldnames", $fieldnames);
	    $smartybackend->assign("pagesize", $pagesize);
		$smartybackend->assign("pager", $pager);
	}

	$smartybackend->assign("search_text", htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8'));
	
	$smartybackend->display('data/main.tpl');
?>