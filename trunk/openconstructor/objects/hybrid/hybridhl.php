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
 * $Id: hybridhl.php,v 1.22 2007/04/20 07:55:13 sanjar Exp $
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
	$smartybackend->assign("pma", $obj->_pma);
	$no_res_tpl = $tpls->get_all_tpls('searchdss');
	$smartybackend->assign("no_res_tpl", $no_res_tpl);

	$fields = $obj->ds_id ? FieldFactory::getRelatedFields($obj->ds_id) : array();
	$fieldsById = array();
	for($i = 0, $l = sizeof($fields); $i < $l; $i++)
		$fieldsById[$fields[$i]->id] = &$fields[$i];

	$objfields = array();
	$docFields = array_flip(array_map(create_function('&$v', 'return $v[\'id\'];'), $obj->docFields));
	for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
		$f = &$fields[$i];
		if($f->family == 'tree' && $f->isArray) continue;
		if($f->family == 'rating')
			$rating = @$obj->docFields[$docFields[$f->id]]['range'];
		elseif(($f->family == 'array' || $f->family == 'document') && $f->type == 'hybrid')
			if(!isset($hlid)) {
				$objm = new ObjManager();
				$objm->pageSize = 50;
				$hlid = $objm->get_objects('hybrid', 'hybridbar', 1, '', -1);
				$hlid_sel = @$obj->docFields[$docFields[$f->id]]['fetcher'];
			}
		$objfields[] = array(
							'id' => $f->id,
							'key' => substr($f->key, 2),
							'header' => $f->header,
							'family' => $f->family,
							'ds_name' => @$f->ds_id,
							'checked' => isset($docFields[$f->id]) ? true : false,
							'rating' => @$rating,
							'fetchers' => @$hlid,
							'fetcher_id' => @$hlid_sel
						);
	}
    $smartybackend->assign("objfields", $objfields);

	$order = array();
	$sysf = array(
		//'id' => 'ID',
		'header' => 'Header'
	);
	$docOrder = array_flip(array_map(create_function('&$v', 'return $v[\'id\'];'), $obj->docOrder));
	$fs = array();
	for($i = 0, $l = sizeof($fields); $i < $l; $i++)
		if(isset($docOrder[$fields[$i]->id]) || isset($docOrder['-'.$fields[$i]->id]))
			$fs[$fields[$i]->id] = &$fields[$i];
	foreach($docOrder as $id => $j){
		if(is_numeric($id)) {
			$f = &$fs[abs($id)];
			if(!is_object($f))continue;
			$order[] = array(
							'id' => $f->id,
							'pref' => $id > 0 ? 1 : 0,
							'header' => $f->header,
							'sysf' => false
						);
		} elseif(isset($sysf[$id]) || isset($sysf[substr($id, 1)])) {
			$desc = substr($id, 0, 1) == '-';
			$fid = $desc ? substr($id, 1) : $id;
			$order[] = array(
							'id' => $fid,
							'pref' => $desc ? 0 : 1,
							'header' => $sysf[$fid],
							'sysf' => true
						);
		}
	}
	$smartybackend->assign("order", $order);

	$availablefilds = array();
	foreach($sysf as $id => $header)
		if(!isset($docOrder[$id]) && !isset($docOrder['-'.$id]))
			$availablefilds[] = array(
									'id' => $id,
									'header' => $header,
									'sysf' => true
								);
	for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
		$f = &$fields[$i];
		if(
			($f->family == 'primitive' && $f->type != 'text' && $f->type != 'html')
			|| $f->family == 'enum'
			|| $f->family == 'datasource'
			|| $f->family == 'rating'
			|| $f->family == 'document'
			|| ($f->family == 'tree' && !$f->isArray)
		)
			if(!isset($docOrder[$f->id]) && !isset($docOrder['-'.$f->id]))
				$availablefilds[] = array(
										'id' => $f->id,
										'header' => $f->header,
										'sysf' => false
									);
	}
    $smartybackend->assign("availablefilds", $availablefilds);

    $ratingperiod = array();
    for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
		$f = &$fields[$i];
		if($f->family == 'rating') {
			$range = '';
			if(isset($docOrder[$f->id]))
				$range = @$obj->docOrder[$docOrder[$f->id]]['range'];
			elseif(isset($docOrder['-'.$f->id]))
				$range = @$obj->docOrder[$docOrder['-'.$f->id]]['range'];
		$ratingperiod[] = array('id' => $f->id, 'range' => $range);
		}
	}
	$smartybackend->assign("ratingperiod", $ratingperiod);

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

	$smartybackend->display('objects/hybrid/hybridhl.tpl');
?>