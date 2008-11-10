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
 * $Id: editvalue.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	require_once(LIBDIR.'/enum/wcenumfactory._wc');
	
	$ef = &WCEnumFactory::getInstance();
	$enum = $ef->load((int) @$_GET['enum']);
	assert($enum != null);
	$value = null;
	if(@$_GET['id'] != 'new') {
		$value = $enum->getValue(@$_GET['id']);
		assert($value != null);
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=($value ? htmlspecialchars($value['header'], ENT_COMPAT, 'UTF-8').' | '.EDIT_ENUMVALUE : CREATE_ENUMVALUE)?></title>
<link href="../../../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
var rek=/^[a-z0-9_\-#\$]{1,32}$/gi;
function dsb(){
	if(!f.key.value.match(rek)||!f.header.value.match(re)||<?=($value ? WCS::decide($enum, 'addvalue') : WCS::decide($enum, 'editvalue'))?'false':'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20" onload="f.key.focus();">
<br>
<h3><?=$enum ? EDIT_ENUMVALUE : CREATE_ENUMVALUE?></h3>
<form name="f" method="POST" action="i_enum.php">
	<input type="hidden" name="action" value="<?=$value ? 'edit_enumvalue' : 'create_enumvalue'?>">
	<input type="hidden" name="enum" value="<?=$enum->id?>">
	<input type="hidden" name="id" value="<?=$value ? $value['id'] : 'new'?>">
	<fieldset style="padding:10"><legend><?=H_ENUMVALUE_PROPERTIES?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=ENUMVALUE_KEY?>:</td>
			<td><input type="text" name="key" value="<?=$value ? htmlspecialchars($value['value']) : ''?>" size="32" maxlength="32" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td><?=ENUM_HEADER?>:</td>
			<td><input type="text" name="header" value="<?=$value ? htmlspecialchars($value['header'], ENT_COMPAT, 'UTF-8') : ''?>" size="64" maxlength="255" onpropertychange="dsb()"></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=$enum ? BTN_SAVE : BTN_CREATE?>" name="create" DISABLED> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>