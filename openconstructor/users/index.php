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
 * $Id: index.php,v 1.11 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('users');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');
	require_once('../include/sections._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$gf = &GroupFactory::getInstance();
	$groups = $gf->getAllGroups();
	$siteroot=0;
	$map['groupRoot'][USERS] = $groups;
	$tmp = isset($_GET['node']) ? $_GET['node'] : @$_COOKIE['curnode'];
	$curnode = isset($groups[$tmp]) ? $tmp : (sizeof($groups) ? key($groups) : -1);
	$group = &$gf->getGroup($curnode);
	setcookie('curnode', $curnode, 0, WCHOME.'/users/');

	$smartybackend->assign("curnode", $curnode);
	$smartybackend->assign("cur_section", 'users');
	$smartybackend->assign_by_ref("auth", $auth);
	$smartybackend->assign_by_ref("group", $group);

	$smartybackend->assign("menu", getTabs('users'));
	include('toolbar._wc');
	$smartybackend->assign_by_ref("toolbar", $toolbar);

	$smartybackend->assign("map", print_tree($map));
    include('headline._wc');
	$smartybackend->assign("fields", $fields);
	$smartybackend->assign("objs", $hl);

    $smartybackend->assign("editor_width", 600);
	$smartybackend->assign("editor_height", 'null');
	$smartybackend->assign("editor", WCHOME . '/users/edituser.php?j=1');
	$smartybackend->assign("icon", 'user');

	$smartybackend->assign("pager", $pager);

	$smartybackend->assign("search_text", htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8'));
    $smartybackend->assign("fieldnames", $fieldnames);
    $smartybackend->assign("pagesize", $pagesize);

	$smartybackend->display('users/main.tpl');
?>