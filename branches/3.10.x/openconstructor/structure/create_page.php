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
 * $Id: create_page.php,v 1.9 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/structure._wc');
	require_once(LIBDIR.'/site/pagereader._wc');
	
	$pr = &PageReader::getInstance();
	$in = $pr->getPage(@$_GET['node']);
	assert($in != null);
	$super = $pr->superDecide($in->id, 'managesub');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=WC.' | '.H_CREATE_PAGE?></title>
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
<h3><?=H_CREATE_PAGE?></h3>
<form name="f" method="POST" action="i_structure.php">
	<input type="hidden" name="action" value="create_page">
	<input type="hidden" name="parent_id" value="<?=$in->id?>">
	<fieldset style="padding:10"><legend><?=NEW_PAGE?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=PAGE_FOLDER?>:</td>
			<td><input type="text" name="page_name" size="64" maxlength="128" onpropertychange="dsb();un.innerText='<?=$in->uri?>'+this.value+'/'"></td>
		</tr>
		<tr>
			<td><?=PAGE_NAME?>:</td>
			<td><input type="text" name="header" size="64" maxlength="128" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td colspan="2"><?=PAGE_URI?>: <span id="un"><?=$in->uri?>/</span></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=PARENT_PAGE?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=CREATE_PAGE_IN?>:</td>
			<td><b><?=$in->uri?></b></td>
		</tr>
		<tr>
			<td><?=PARENT_PAGE?>:</td>
			<td><?=$in->header?></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_CREATE?>" name="create" DISABLED> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
</body>
</html>