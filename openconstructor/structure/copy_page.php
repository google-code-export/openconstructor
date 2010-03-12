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
<title><?=WC.' | '.H_COPY_PAGE?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
var folder = /^[a-z0-9][a-z0-9\-_]{0,127}$/i;
function dsb(){
	if(!f.page_name.value.match(folder)||!f.header.value.match(re)||<?=$super || WCS::decide($in, 'managesub') ?'false':'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=H_COPY_PAGE?></h3>
<form name="f" method="POST" action="i_structure.php">
	<input type="hidden" name="action" value="copy_page">
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
	<fieldset style="padding:10"><legend><?=COPY_TO?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=COPY_TO?>:</td>
			<td><select name="dest_id" size="1" onchange="if(this.selectedIndex != -1) {document.getElementById('uri').innerHTML = this.options[this.selectedIndex].uri;}">
<?php
	$tree = &$pr->getTree();
	$nodeIds = array_keys($tree->node);
	$map = array();
	foreach($nodeIds as $id)
		if($id != $page->id && !$tree->contains($page->id, $id))
			$map[$id] = $tree->node[$id]->getFullKey();
	asort($map);
	foreach($map as $id => $key)
		echo sprintf('<option value="%d" uri="%s">%s %s'
			, $id, $key, str_repeat('&nbsp; ', (substr_count($key, '/') - 1) * 3), $tree->node[$id]->header
		);
?>
</select></td>
		</tr>
		<tr>
			<td><?=PAGE_URI_NEW?>:</td>
			<td style="font-family:monospace"><span id="uri"></span><input type="text" id="page_name" name="page_name" maxlength="128" onpropertychange="dsb()" value="<?=$page->name?>" />/</td>
		</tr>
		<tr>
			<td><?=PAGE_NAME?>:</td>
			<td style="font-family:monospace"><input type="text" id="header" name="header" maxlength="128" onpropertychange="dsb()" value="<?=$page->header?>" /></td>
		</tr>
		<tr>
			<td></td>
			<td style="font-family:monospace"><input type="checkbox" name="published" id="f_recursive" value="true" <?=$super || WCS::decide($page, 'editpage.publish') ? '':' DISABLED'?> /> <label for="f_recursive"><?=PUBLISH_PAGE_WITH_SUBSECTIONS?></label></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_COPY?>" name="create"<?=$super || WCS::decide($page, 'removepage') ? '':' DISABLED'?>> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
	<script>f.dest_id.onchange();</script>
</form>
</body>
</html>