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
 * $Id: edit_page.php,v 1.22 2007/04/23 09:50:05 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/structure._wc');
	require_once(LIBDIR.'/site/pagereader._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$pr = &PageReader::getInstance();
	$page = &$pr->getPage(@$_GET['node']);
	assert($page != null);
	$super = $pr->superDecide($page->id, 'managesub');
	require_once(LIBDIR.'/security/groupfactory._wc');
	$gf = &GroupFactory::getInstance();
	require_once(LIBDIR.'/templates/wctemplates._wc');
	$tpls = & new WCTemplates();
	$tpl = null;
	if($page->tpl)
		$tpl = &$tpls->load($page->tpl);

	$smartybackend->assign("super", $super);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);
	$smartybackend->assign_by_ref("pr", $pr);
	$smartybackend->assign_by_ref("page", $page);
	$smartybackend->assign("tpls", $tpls->get_all_tpls('page'));
	$smartybackend->assign_by_ref("tpl", $tpl);

	$cssDir = $_SERVER['DOCUMENT_ROOT'].FILES.'/css';
	$css_files = array();
	$css_selected = array();
	if(@is_dir($cssDir)) {
		$d = dir($cssDir);
		while (false !== ($entry = $d->read())){
		    if(substr($entry, 0, 1) != '_' && substr($entry, strrpos($entry, '.')) == '.css' && is_file($cssDir.'/'.$entry))
                $css_files[] = $entry;
			if(array_search($entry, $page->css) !== false)
				$css_selected[] = $entry;
		}
		$d->close();
		$smartybackend->assign("css_files", $css_files);
		$smartybackend->assign("css_selected", $css_selected);
	}

	$types = array('text/html', 'text/plain', 'text/css', 'text/xml', 'text/calendar', 'application/octet-stream'
					, 'application/pdf', 'application/x-gzip', 'application/x-javascript', 'application/xml'
					, 'application/zip', 'image/gif', 'image/jpeg', 'image/png'
					);
	$smartybackend->assign("types", $types);

	$user_groups = array();
	$selected_groups = array();
	$groups = &$gf->getAllGroups();
	foreach($groups as $id => $title){
		$user_groups[$id] = $title;
		if(array_search($id, $page->users) !== false)
				$selected_groups[] = $id;
	}
	$smartybackend->assign("groups", $user_groups);
	$smartybackend->assign("selected_groups", $selected_groups);

	require_once(LIBDIR.'/objmanager._wc');
	$obj_prof = array();
	$selected_obj = array();
	$objs = (array) ObjManager::get_objects('hybrid', 'hybridbody', 1, '', -1);
	foreach($objs as $id => $obj){
    	$obj_prof[$id] = addslashes($obj['name']);
		if($id == $page->profilesLoad)
				$selected_obj[] = $id;
	}
	$smartybackend->assign("obj_prof", $obj_prof);
	$smartybackend->assign("selected_obj", $selected_obj);

	$smartybackend->display('structure/edit_page.tpl');
?>