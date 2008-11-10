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
 * $Id: move_page.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/structure._wc');
	require_once(LIBDIR.'/site/pagereader._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$pr = &PageReader::getInstance();
	$page = $pr->getPage(@$_GET['node']);
	assert($page != null && $page->uri != '/');
	$super = $pr->superDecide($page->id, 'managesub');

	$disabled = $super || WCS::decide($page, 'removepage') ? 'false' : 'true';
	$smartybackend->assign("disabled", $disabled);
	$smartybackend->assign_by_ref("page", $page);

	$tree = &$pr->getTree();
	$nodeIds = array_keys($tree->node);
	$map = array();
	foreach($nodeIds as $id)
		if($id != $page->id && !$tree->contains($page->id, $id))
			$map[$id] = $tree->node[$id]->getFullKey();
	asort($map);
	$smartybackend->assign("map", $map);
	$smartybackend->assign_by_ref("node", $tree->node);

	$smartybackend->display('structure/move_page.tpl');
?>
