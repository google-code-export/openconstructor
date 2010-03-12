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
 * $Id: miscinjector.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	require_once(LIBDIR.'/dsmanager._wc');

	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);
?>
<html>
<head>
<title><?=WC.' | '.EDIT_OBJECT?> | <?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script src="<?=WCHOME?>/lib/js/base.js"></script>
<script language="JavaScript" type="text/JavaScript">
function openObjectUses(objId) {
	openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
}
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.name.value.match(re)||!f.description.value.match(re)||<?=WCS::decide($obj, 'editobj') ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
function addInjection() {
	var sample = document.getElementById('sample');
	var inj = sample.cloneNode(true);
	sample.parentElement.appendChild(inj);
	inj.style.display = 'block';
	return inj;
}
function removeInjection(button) {
	var inj = button.parentElement.parentElement.parentElement.parentElement.parentElement;
	inj.removeNode(true);
}
function setInjection(inj, type, param, src, srcId, field) {
	var inputs = inj.getElementsByTagName('INPUT');
	var selects = inj.getElementsByTagName('SELECT');
	selects[0].selectedIndex = type;
	inputs[0].value = param;
	inputs[0].onchange();
	selects[1].selectedIndex = src;
	inputs[1].value = srcId;
	inputs[2].value = field;
}
</script>
<style>
</style>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_OBJECT?></h3>
<form name="f" method="POST" action="i_miscellany.php">
	<input type="hidden" name="action" value="edit_miscinjector">
	<input type="hidden" name="obj_id" value="<?=$obj->obj_id?>">
	<fieldset style="padding:10"><legend><?=OBJECT?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_OBJ_NAME?>:</td>
			<td><input type="text" name="name" size="64" maxlength="64" value="<?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?>" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td valign="top" nowrap><?=PR_OBJ_DESCRIPTION?>:</td>
			<td><textarea cols="51" rows="5" name="description" onpropertychange="dsb()"><?=$obj->description?></textarea>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=OBJ_INJECTIONS?></legend>
		<div>
		<fieldset id="sample" style="margin:20px 0 30px;display:none;">
		<legend style="font-weight:bold;"></legend>
		<table style="margin:5 0" cellspacing="3" width="100%">
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td><?=H_INJECT_SRC?></td>
				<td><?=H_INJECT_SRC_PARAM?></td>
				<td>&nbsp;</td>
				<td rowspan="4" valign="top"><img src="../../i/default/e/h/remove.gif" style="cursor:pointer;margin-right:10px;" onclick="removeInjection(this)" alt="<?=BTN_REMOVE_INJECTION?>"></td>
			</tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_INJECT?></td>
				<td>
					<select size="1" name="type[]">
						<option value="<?=INJ_CTX?>">Context
						<option value="<?=INJ_GET?>">GET
						<option value="<?=INJ_POST?>">POST
						<option value="<?=INJ_COOKIE?>">Cookie
						<option value="<?=INJ_SESSION?>">Session
						<option value="<?=INJ_VALUE?>">Value
					</select>
				</td>
				<td colspan="2" width="100%"><input type="text" name="param[]" size="35" onchange="this.parentElement.parentElement.parentElement.parentElement.parentElement.getElementsByTagName('LEGEND')[0].innerHTML = this.value"></td>
			</tr>
			<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td nowrap><?=H_INJECT_DEST_TYPE?></td>
				<td nowrap><?=H_INJECT_DEST_ID?></td>
				<td><?=H_INJECT_DEST_FIELD?></td>
			</tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_INJECT_TO?></td>
				<td>
					<select size="1" name="src[]">
						<option value="<?=INJ_BY_ID?>"><?=H_INJECT_DEST_OBJECT?>
						<option value="<?=INJ_BY_BLOCK?>"><?=H_INJECT_DEST_BLOCK?>
					</select>
				</td>
				<td><input type="text" size="10" name="srcId[]"></td>
				<td><input type="text" name="field[]"></td>
			</tr>
		</table>
		</fieldset>
		</div>
		<input type="button" value="<?=BTN_ADD_INJECTION?>" onclick="addInjection()">
	<script>
	<?php
		foreach($obj->jobs as $job) {
			$type = $job[0] % 10;
			$src = intval($job[0] / 10) - 1;
			echo "setInjection(addInjection(), $type, '{$job[3]}', $src, '{$job[1]}', '{$job[2]}');\n";
		}
	?>
	</script>
	</fieldset><br>
	<div align="right">
	<input type="button" value="<?=BTN_MANAGE_OBJECT_USES?>" style="float: left;" onclick="openObjectUses(<?=$obj->obj_id?>);">
	<input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div><br><br><br>
</form>
<script>dsb();</script>
</body>
</html>