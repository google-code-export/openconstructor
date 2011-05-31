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
?>
<html>
<head>
<title><?=WC.' | '.H_OBJECT_USES?> | <?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<link href="tree.css" type=text/css rel=stylesheet>
<script src="object_uses.js"></script>
<script language="JavaScript" type="text/JavaScript">
	window.onload = function() {
		setTimeout(initUses, 100);
	}
</script>
<style>
#sitemap {
	font-size: 11px;
	margin-top: 20px;
}
#sitemap .r0 {
}
#sitemap .r1 {
	background: #eee;
}
#sitemap SELECT {
	visibility: hidden;

}
#sitemap THEAD TD {
	font-size: 14px;
	padding: 5px 10px;
	background: #ddd;
}
</style>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=H_OBJECT_USES?></h3>
<form name="f" method="POST" action="i_objects.php">
	<input type="hidden" name="action" value="edit_uses">
	<input type="hidden" name="obj_id" value="<?=$obj->obj_id?>">
	<fieldset style="padding:10"><legend><?=OBJECT?></legend>
	<table style="margin:5 0" cellspacing="5">
		<tr>
			<td nowrap><?=PR_OBJ_NAME?>:</td>
			<td><b><?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?></b></td>
		</tr>
		<tr>
			<td nowrap><?=PR_OBJ_TYPE?>:</td>
			<td><?=$om->map[$obj->ds_type][constant(strtoupper("DS_{$obj->ds_type}"))][$obj->obj_type]?></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=H_OBJECT_USES?></legend>
	<ul id="pages">
	<?php
		$pages = $pr->getAllPages();
		settype($obj->exclude, 'array');
		$tree = $pr->getTree();
		$ids = array_keys($pages);
		for($i = 0, $_l = sizeof($ids); $i < $_l; $i++) {
			$node = &$tree->node[$ids[$i]];
			$header = $node->index == 0 ? '<b>'.htmlspecialchars($node->header, ENT_COMPAT, 'UTF-8').'</b>' : htmlspecialchars($node->header, ENT_COMPAT, 'UTF-8');
			if($uses[$node->id]['used'])
				echo sprintf(
					"<li i='-{$node->id}' l=%d b='%s' a=%d>%s"
					, substr_count($pages[$node->id], '/')
					, htmlspecialchars($uses[$node->id]['block'], ENT_COMPAT, 'UTF-8')
					, $uses[$node->id]['blocks_ref']
					, $header
				);
			else
				echo sprintf(
					"<li i='{$node->id}' l=%d a=%d>%s"
					, substr_count($pages[$node->id], '/')
					, $uses[$node->id]['blocks_ref']
					, $header
				);
		}
	?>
	</ul>
	<table id="sitemap" style="width: 100%;" cellspacing=0 cellpadding=0>
		<thead>
			<tr>
				<td><?=H_PAGE_BLOCK?></td>
				<td><?=H_PAGE_TITLE?></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="padding: 3px 5px;">
					<select size="1" class="blocks" disabled="">
						<option class="gray" value="">-</option>
						<?php
							foreach($events as $evt)
								echo "<option class='event' value='@{$evt}'>{$evt}</option>";
						?>
					</select>
				</td>
				<td style="width: 100%;"></td>
			</tr>
		</tbody>
	</table>
	<hr size="1">
	<table border=0>
		<tr>
			<td>
				<?=H_BULK_SELECT_BLOCK?>:
			</td>
			<td>
				<input type="text" id="txt.bulkBlock" size="20"> <input type="button" value="<?=BTN_BULK_SELECT_BLOCK?>" onclick="selectBlock(document.getElementById('txt.bulkBlock').value)">
			</td>
		</tr>
	</table>
	</fieldset><br>
	<script>
		var blocks = [[<?=implode('],[', $blocks)?>]];
	</script>
	<div align="right">
	<input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div>
</form>
</body>
</html>
