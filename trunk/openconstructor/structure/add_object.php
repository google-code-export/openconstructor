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
 * $Id: add_object.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/structure._wc');
	require_once(LIBDIR.'/site/pagereader._wc');
	require_once(LIBDIR.'/objmanager._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$pr = &PageReader::getInstance();
	$to = $pr->getPage(@$_GET['node']);
	assert($to != null);
	$super = $pr->superDecide($to->id, 'managesub');
	$_objm = & new ObjManager();
	$objs = $_objm->get_all_objects();
	$ex = &$to->getObjects();
	foreach($ex as $id => $j)
		unset($objs[$id]);

	$result = array();
	foreach($objs as $id => $obj)
		$result[$obj['ds_type']][$obj['obj_type_f']][$id] = $obj;

	$disabled = $super || WCS::decide($to, 'pageblock.manage') ? 'false' : 'true';
	$smartybackend->assign("disabled", $disabled);
	$smartybackend->assign("node", $_GET['node']);
	$smartybackend->assign("objs", $result);

	$smartybackend->display('structure/add_object.tpl');
?>
