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
 * $Id: index.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('tpls');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/templates/wctemplates._wc');
	require_once('../include/sections._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$siteroot='templatesRoot';
	$curnode=@$_GET['node']?$_GET['node']:(@$_COOKIE['curnode']?$_COOKIE['curnode']:'htmltextbody');
	setcookie('curnode',$curnode,0,WCHOME.'/templates/');
	$tpls=new WCTemplates();
	$tmap=$tpls->get_map();
	foreach($tmap as $k=>$v)
		foreach($v as $v1)
			if(@$v1[$curnode]) $nodetype=$k;
	$map[$siteroot][TEMPLATES]=&$tmap;

	$opened[$nodetype]=true;
	$opened[$siteroot]=true;

	$smartybackend->assign("curnode", $curnode);
	$smartybackend->assign("nodetype", $nodetype);
	$smartybackend->assign("cur_section", 'templates');
	$smartybackend->assign_by_ref("auth", $auth);

	$smartybackend->assign("menu", getTabs('objects'));

	include('toolbar._wc');
	$smartybackend->assign_by_ref("toolbar", $toolbar);
	$smartybackend->assign("map", print_tree($map));

	include('headline._wc');
	$smartybackend->assign("fields", $fields);
	$smartybackend->assign("objs", $hl);

    $smartybackend->assign("editor_width", 760);
	$smartybackend->assign("editor_height", 'null');
	$smartybackend->assign("editor", 'edit' . ($curnode == 'page' ? 'page' : '') . '.php?dstype=' . $nodetype);
	$smartybackend->assign("icon", 'tpl');

	$smartybackend->assign("pager", $pager);

	$smartybackend->assign("search_text", htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8'));
    $smartybackend->assign("fieldnames", $fieldnames);
    $smartybackend->assign("pagesize", $pagesize);

	$smartybackend->display('templates/main.tpl');
?>