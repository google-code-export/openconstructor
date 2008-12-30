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
 * $Id: hybridbody.php,v 1.14 2007/03/02 10:06:41 sanjar Exp $
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
	$fields = $obj->ds_id ? FieldFactory::getRelatedFields($obj->ds_id) : array();
	$smartybackend->assign("fields", $fields);

    $idFields = array();
	for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
		$f = &$fields[$i];
		$upper = strpos($ds[$obj->ds_id]['path'], $ds[$f->ds_id]['path']) === 0;
		if($upper && (
			($f->family == 'primitive' && ($f->type == 'integer' || $f->type == 'string') && $f->length <= 24)
			|| ($f->family == 'enum' && !$f->isArray)
		))
			$idFields[] = array($f->id => $f->header);
	}
	$smartybackend->assign("idFields", $idFields);

	$smartybackend->assign("pma", $obj->_pma);

	$objfields = array();
	$docFields = array_flip(array_map(create_function('&$v', 'return $v[\'id\'];'), $obj->docFields));
	for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
		$f = &$fields[$i];
		if($f->family == 'rating')
			$rating = @$obj->docFields[$docFields[$f->id]]['range'];
		$objfields[] = array(
							'id' => $f->id,
							'key' => substr($f->key, 2),
							'header' => $f->header,
							'family' => $f->family,
							'ds_name' => @$f->ds_id,
							'checked' => isset($docFields[$f->id]) ? true : false,
							'rating' => @$rating
						);
	}
    $smartybackend->assign("objfields", $objfields);

    $condition = array();
	foreach((array) $obj->docFilter as $cond) {
		$type = abs($cond[0]) % 10;
		$src = intval(abs($cond[0]) / 10) - 1;
		$invert = $cond[0] < 0 ? 'true' : 'false';
		$name = isset($fieldsById[$cond[2]]) ? substr($fieldsById[$cond[2]]->key, 2) : $cond[2];
		$condition[] = array(
							'type' => $type,
							'name' => $name,
							'src' => $src,
							'value' => $cond[1],
							'invert' => $invert
						);
	}
	$smartybackend->assign("condition", $condition);

	$smartybackend->display('objects/hybrid/hybridbody.tpl');
?>