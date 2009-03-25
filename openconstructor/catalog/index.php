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
 * $Id: index.php,v 1.13 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('catalog');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/tree/sqltreereader._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	require_once('../include/sections._wc');
	
	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;
	
	$dsm = new DSManager();
//	$siteroot='catalogRoot';
	$dsh = &$dsm->getAll('hybrid');
	$reader = new SqlTreeReader();
	$currentDs = @$dsh[@$_COOKIE['dsh']] ? @$_COOKIE['dsh'] : key($dsh);
	@list(,$curtab) = explode('/',$_SERVER['PATH_INFO']);
	if(!$curtab) $curtab = $currentDs ? 'browse' : 'trees';
	$ds = &$dsm->load($currentDs);
	$tmp = @$_GET['node'] ? $_GET['node'] : @$_COOKIE['curnode'];
	$treeFields = getTreesFor($currentDs);
	if($curtab == 'browse') {
		$rootNode = null;
		$tree = $reader->getPartialTree($treeFields);
		require_once(LIBDIR.'/tree/export/tplviewmultiple._wc');
		$view = new TreeTplViewMultiple();
		if((int) $tmp == -1) { // Special node to select unassigned documents
			$selected = array();
			$curnode = -1;
		} else {
			$selected = explode(',', $tmp);
			for($i = 0; $i < sizeof($selected); $i++)
				if(!$tree->exists($selected[$i]))
					unset($selected[$i]);
			$selected = sizeof($selected) ? array_values($selected) : array(-1);
			$curnode = implode(',', $selected);
		}
		$view->setSelected($selected);
		foreach($treeFields as $fieldName=>$nodeId) {
			$presetValue[$fieldName] = array();
			for($i = 0; $i < sizeof($selected); $i++) {
				if($tree->contains($nodeId, $selected[$i]))
					$presetValue[$fieldName][] = $selected[$i];
			}
			$presetValue[$fieldName] = $fieldName.'='.implode(',', $presetValue[$fieldName]);
		}
		$presetValue = implode('&', $presetValue);
		setcookie('dsh', $currentDs, 0, WCHOME);
	} else {
		$tree = $reader->getTree(1);
		if(!$tmp || !$tree->exists($tmp))
			@list($tmp) = each($tree->root->node);
		$curnode = $tmp && $tree->exists($tmp) ? $tmp : $tree->root->id;
		$rootNode = $tree->node[$curnode];
		while($rootNode->parent && $rootNode->parent->id != 1)
			$rootNode = &$rootNode->parent;
		if($rootNode->id != 1)
			$reader->loadAuths($rootNode);
		require_once(LIBDIR.'/tree/export/tplview._wc');
		$view = new TreeTplView();
		$view->setSelected($curnode);
		foreach($treeFields as $fieldName=>$nodeId)
			if($tree->contains($nodeId, $curnode)) {
				$presetValue = $fieldName.'='.$curnode;
				break;
			}
			
	}
	$tree->root->header = H_TREES;
	setcookie('curnode', $curnode, 0, WCHOME.'/catalog/');
	
	$ds_name = ($currentDs > 0 ? ' | '.htmlspecialchars($dsh[$currentDs]['name'], ENT_COMPAT, 'UTF-8') : '');
	foreach($dsh as &$v)
		$v['pathView'] = str_repeat("&#160;", (substr_count($v['path'],',') - 1) * 3);

	$smartybackend->assign("ds", $ds);
	$smartybackend->assign("dsh", $dsh);
	$smartybackend->assign("ds_name", $ds_name);
	$smartybackend->assign("curnode", $curnode);
	$smartybackend->assign("currentDs", $currentDs);
	$smartybackend->assign("curtab", $curtab);
	if(isset($presetValue))
		$smartybackend->assign("presetValue", $presetValue);
	$smartybackend->assign("rootNode", $rootNode);
	$smartybackend->assign("treeFields", $treeFields);
	$smartybackend->assign("tree", $tree);
	$smartybackend->assign("view", $view);
	if(isset($selected))
		$smartybackend->assign("selected", $selected);
	
	$smartybackend->assign("cur_section", 'catalog');
	$smartybackend->assign_by_ref("auth", $auth);
	$smartybackend->assign("menu", getTabs('catalog'));
	
	include('toolbar._wc');
	$smartybackend->assign_by_ref("toolbar", $toolbar);
	
	include('headline._wc');
	$smartybackend->assign("fields", $fields);
	$smartybackend->assign("docs", $hl);
    $smartybackend->assign("editor_width", $eWidth);
	$smartybackend->assign("editor_height", $eHeight);
	$smartybackend->assign("editor", $editor);
	$smartybackend->assign("icon", $icon);
    $smartybackend->assign("fieldnames", $fieldnames);
    $smartybackend->assign("pagesize", $pagesize);
	$smartybackend->assign("pager", $pager);
	
	$smartybackend->assign("search_text", htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8'));
	
	$smartybackend->display('catalog/main.tpl');
?>