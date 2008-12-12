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
 * $Id: htmltexthlintro.php,v 1.11 2007/03/02 10:06:44 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);
	require_once(LIBDIR.'/site/pagereader._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	$pr = &PageReader::getInstance();
	$_dsm=new DSManager();
	$ds = $_dsm->getAll($obj->ds_type);

	$smartybackend->assign_by_ref("obj", $obj);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);

	include('../select_tpl._wc');

	$smartybackend->assign("ds", $ds);
	$pages = &$pr->getAllPages();
    $smartybackend->assign("pages", $pages);

    settype($obj->exclude, 'array');
	$tree = &$pr->getTree();
	$ids = array_keys($pages);
	for($i = 0, $_l = sizeof($ids); $i < $_l; $i++) {
		$node = &$tree->node[$ids[$i]];
		$level =  substr_count($pages[$node->id], '/');
		$pref = array_search($node->id, $obj->exclude) !== false ? '-' : '';
		$map[] = array('id' => $pref . $node->id, 'title' => $node->key, 'name' => $node->header, 'level' => $level);
	}
	$smartybackend->assign("map", $map);

	$smartybackend->display('objects/htmltext/htmltexthlintro.tpl');
?>