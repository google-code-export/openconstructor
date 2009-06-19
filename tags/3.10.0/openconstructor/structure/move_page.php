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
	
	$pr = &PageReader::getInstance();
	$page = $pr->getPage(@$_GET['node']);
	assert($page != null && $page->uri != '/');
	$super = $pr->superDecide($page->id, 'managesub');
?>
<html>
<head>
<title><?=WC.' | '.H_MOVE_PAGE?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=H_MOVE_PAGE?></h3>
<form name="f" method="POST" action="i_structure.php">
	<input type="hidden" name="action" value="move_page">
	<input type="hidden" name="uri_id" value="<?=$page->id?>">
	<fieldset style="padding:10"><legend><?=PAGE_WEB?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=PAGE_NAME?>:</td>
			<td><span style="font-size: 120%;"><?=$page->header?></span></td>
		</tr>
		<tr>
			<td><?=PAGE_URI_CURRENT?>:</td>
			<td><span style="font-family: monospace; font-size: 120%;"><?=$page->uri?></span></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=MOVE_TO?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=MOVE_TO?>:</td>
			<td><select name="dest_id" size="1" onchange="if(this.selectedIndex != -1) {document.getElementById('uri').innerHTML = this.options[this.selectedIndex].uri; f.create.disabled = this.options[this.selectedIndex].current ? true : false;}">
<?php
	$tree = &$pr->getTree();
	$nodeIds = array_keys($tree->node);
	$map = array();
	foreach($nodeIds as $id)
		if($id != $page->id && !$tree->contains($page->id, $id))
			$map[$id] = $tree->node[$id]->getFullKey();
	asort($map);
	foreach($map as $id => $key)
		echo sprintf('<option value="%d" uri="%s"%s>%s %s'
			, $id, $key, $id == $page->parent ? ' current="1" style="color: #888;"' : '', str_repeat('&nbsp; ', (substr_count($key, '/') - 1) * 3), $tree->node[$id]->header 
		);
?>
</select></td>
		</tr>
		<tr>
			<td><?=PAGE_URI_NEW?>:</td>
			<td style="font-family:monospace"><span id="uri"></span><b><?=$page->name?></b>/</td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_MOVE?>" name="create"<?=$super || WCS::decide($page, 'removepage') ? '':' DISABLED'?>> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
	<script>f.dest_id.onchange();</script>
</form>
</body>
</html>