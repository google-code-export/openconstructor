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
 * $Id: editnode.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/catalog._wc');
	require_once(LIBDIR.'/tree/sqltreereader._wc');
	
	$reader = new SqlTreeReader();
	$node = $reader->getNode(@$_GET['id']);
	assert($node != null);
	$rootNode = $reader->getRootNode($node->id);
//	$reader = new SqlTreeReader();
//	$tree = $reader->getTree(1);
//	$in = (int) @$_GET['id'];
//	if(!$tree->exists($in))
//		$in = 1;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=WC.' | '.EDIT_NODE?></title>
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
var rek=/^[a-z0-9_\-]{1,32}$/gi;
function dsb(){
	if(!f.key.value.match(rek)||!f.header.value.match(re)||<?=WCS::decide($rootNode, 'edittree') ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20" onload="f.header.focus();">
<br>
<h3><?=EDIT_NODE?></h3>
<form name="f" method="POST" action="i_catalog.php">
	<input type="hidden" name="action" value="edit_node">
	<input type="hidden" name="id" value="<?=$node->id?>">
	<input type="hidden" name="key" value="<?=$node->key?>">
	<fieldset style="padding:10"><legend><?=NODE_PROPS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=NODE_KEY?>:</td>
			<td><input type="text" size="32" maxlength="32" value="<?=$node->key?>" DISABLED></td>
		</tr>
		<tr>
			<td><?=NODE_HEADER?>:</td>
			<td><input type="text" name="header" size="32" maxlength="64" onpropertychange="dsb()" value="<?=$node->header?>"></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
</body>
</html>