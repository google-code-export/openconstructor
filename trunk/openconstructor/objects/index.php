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
 * $Id: index.php,v 1.9 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('objects');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/objmanager._wc');
	require_once('../include/sections._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$siteroot='objRoot';
	$curnode=@$_GET['node']?$_GET['node']:(@$_COOKIE['curnode']?$_COOKIE['curnode']:'htmltextbody');

	setcookie('curnode',$curnode,0,WCHOME.'/objects/');
	$objm=new ObjManager();
	foreach($objm->map as $k=>$v)
		foreach($v as $v1)
			if(@$v1[$curnode]) $nodetype=$k;
	$map[$siteroot][OBJECTS]=&$objm->map;

	$opened[$nodetype]=true;
	$opened[$siteroot]=true;

	$smartybackend->assign("curnode", $curnode);
	$smartybackend->assign("nodetype", $nodetype);
	$smartybackend->assign("cur_section", 'objects');
	$smartybackend->assign_by_ref("auth", $auth);

	$smartybackend->assign("menu", getTabs('objects'));

	include('toolbar._wc');
	$smartybackend->assign_by_ref("toolbar", $toolbar);

	$smartybackend->assign("map", print_tree($map));

	include('headline._wc');
	$smartybackend->assign("fields", $fields);
	$smartybackend->assign("objs", $hl);

    $smartybackend->assign("editor_width", 660);
	$smartybackend->assign("editor_height", 'null');
	$smartybackend->assign("editor", $nodetype . '/' . $curnode . '.php?j=1');
	$smartybackend->assign("icon", 'object');

	$smartybackend->assign("pager", $pager);

	$smartybackend->assign("search_text", htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8'));
    $smartybackend->assign("fieldnames", $fieldnames);
    $smartybackend->assign("pagesize", $pagesize);

    $smartybackend->display('objects/main.tpl');
?>