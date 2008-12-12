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
 * $Id: object_uses.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$om = new ObjManager();
	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);
	$objId = $obj->obj_id;

	require_once(LIBDIR.'/site/pagereader._wc');
	$pr = &PageReader::getInstance();
	$uses = array();
	$used = array();
	$blocks = array();

	$db = &WCDB::bo();
	$res = $db->query(
		"SELECT p.id, o.obj_id, o.block, o.observer FROM sitepages p, siteobjects o WHERE p.id = o.page_id AND (o.observer = 0 || o.obj_id = $objId)"
	);
	while($r = mysql_fetch_row($res))
		if($r[1] == $objId) {
			$uses[$r[0]]['used'] = true;
			$uses[$r[0]]['block'] = $r[3] ? '@'.$r[2] : $r[2];
		} elseif($r[2] != 'PRE' && $r[2] != 'POST') {
			$used[$r[0]][$r[2]] = true;
		}
	mysql_free_result($res);
	$res = $db->query(
		"SELECT p.id, b.block, b.run FROM sitepages p LEFT JOIN wctemplate_blocks b ON (p.tpl = b.tpl_id) ORDER BY p.id, b.pos"
	);
	while($r = mysql_fetch_assoc($res)) {
		if(!isset($uses[$r['id']])) {
			$uses[$r['id']] = array();
		}
		if(!isset($used[$r['id']][$r['block']])) {
			$uses[$r['id']]['blocks'][] = ($r['run'] ? '' : '*').$r['block'];
		}
	}
	mysql_free_result($res);
	foreach($uses as $id => $j) {
		if(!isset($uses[$id]['blocks']))
			$uses[$id]['blocks'] = array();
		if(!isset($uses[$id]['used']))
			$uses[$id]['used'] = false;
		$uses[$id]['blocks'] = '"'.implode('","', $uses[$id]['blocks']).'"';
		$blocks[] = $uses[$id]['blocks'];
	}

	$blocks = array_values(array_unique($blocks));
	$ref = array_flip($blocks);
	foreach($uses as $pid => $v) {
		$uses[$pid]['blocks_ref'] = $ref[$v['blocks']];
		unset($uses[$pid]['blocks']);
	}

	if(ObjManager::isObserverClass($obj->obj_type)) {
		$events = Page::getAllEvents();
	} else
		$events = array();

	$smartybackend->assign_by_ref("obj", $obj);
	$smartybackend->assign_by_ref("obj_type_name", $om->map[$obj->ds_type][constant(strtoupper("DS_{$obj->ds_type}"))][$obj->obj_type]);

	$pages = &$pr->getAllPages();
	settype($obj->exclude, 'array');
	$tree = &$pr->getTree();
	$ids = array_keys($pages);
	for($i = 0, $_l = sizeof($ids); $i < $_l; $i++) {		$node = &$tree->node[$ids[$i]];
		$level =  substr_count($pages[$node->id], '/');
		if($uses[$node->id]['used']){			$pref = 0;
			$block = htmlspecialchars($uses[$node->id]['block'], ENT_COMPAT, 'UTF-8');
		}
		else{			$pref = 1;
			$block = '';
		}
		$map[] = array(
						'id' => $node->id,
						'pref' => $pref,
						'block' => $block,
						'blocks_ref' => $uses[$node->id]['blocks_ref'],
						'name' => $node->header,
						'level' => $level
					);
	}
	$smartybackend->assign("map", $map);
	$smartybackend->assign("blocks", $blocks);
	$smartybackend->assign("events", $events);

	$smartybackend->display('objects/object_uses.tpl');
?>