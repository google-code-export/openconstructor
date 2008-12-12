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
 * $Id: hybridtree.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	require_once(LIBDIR.'/hybrid/fields/fieldfactory._wc');
	require_once(LIBDIR.'/dsmanager._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);
	$_dsm=new DSManager();
	$ds = $_dsm->getAll($obj->ds_type);

	$smartybackend->assign_by_ref("obj", $obj);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);
	include('../select_tpl._wc');
	$smartybackend->assign("ds", $ds);
	$fields = FieldFactory::getRelatedFields($obj->ds_id);
	$smartybackend->assign("fields", $fields);
    $treefields = array();
	$docFields = array_flip(array_map(create_function('&$v', 'return $v[\'id\'];'), $obj->docFields));
	for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
		$f = &$fields[$i];
		if($f->family != 'tree') continue;
		if($f->ds_id != @$lastDs)
			$ds_name = $ds[$f->ds_id]['name'];
		$treefields[] = array(
							'id' => $f->id,
							'key' => substr($f->key, 2),
							'header' => $f->header,
							'family' => $f->family,
							'ds_name' => @$ds_name,
							'checked' => isset($docFields[$f->id]) ? true : false
						);
		$lastDs = $f->ds_id;
	}
	$smartybackend->assign("treefields", $treefields);

	$smartybackend->display('objects/hybrid/hybridtree.tpl');
?>