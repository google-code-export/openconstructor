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
 * $Id: addnode.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/catalog._wc');
	require_once(LIBDIR.'/tree/sqltreereader._wc');
	
	$reader = new SqlTreeReader();
	$tree = $reader->getTree(1);
	assert($tree->exists(@$_GET['in']) == true);
	$in = &$tree->node[$_GET['in']];
	$rootNode = $reader->getRootNode($in->id);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=WC.' | '.CREATE_NODE?></title>
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
var rek=/^[a-z][a-z0-9_\-]{0,31}$/gi;
function dsb(){
	if(!f.key.value.match(rek)||!f.header.value.match(re)||<?=($in->id == 1 ? System::decide('catalog.tree') : WCS::decide($rootNode, 'managetree')) ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20" onload="document.getElementById('f.key').focus();">
<br>
<h3><?=CREATE_NODE?></h3>
<form name="f" method="POST" action="i_catalog.php">
	<input type="hidden" name="action" value="create_node">
	<input type="hidden" name="parent" value="<?=$in->id?>">
	<fieldset style="padding:10"><legend><?=NEW_NODE?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=NODE_KEY?>:</td>
			<td><input type="text" name="key" id="f.key" size="32" maxlength="32" onpropertychange="dsb();"></td>
		</tr>
		<tr>
			<td><?=NODE_HEADER?>:</td>
			<td><input type="text" name="header" size="32" maxlength="64" onpropertychange="dsb()"></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=PARENT_NODE?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=CREATE_NODE_IN?>:</td>
			<td><b><?=$in->getFullKey('/');?></b></td>
		</tr>
		<tr>
			<td><?=PARENT_NODE?>:</td><td><?=$in->header?></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_CREATE?>" name="create" DISABLED> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
</body>
</html>