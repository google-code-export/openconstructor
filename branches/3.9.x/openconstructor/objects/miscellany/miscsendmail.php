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
 * $Id: miscsendmail.php,v 1.15 2007/03/22 13:05:31 sanjar Exp $
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
var re = new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.name.value.match(re)||!f.description.value.match(re)||<?=WCS::decide($obj, 'editobj') ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
var attCount = <?=sizeof($obj->files)?>;
function addAttachment() {
	attCount++;
	var sample = document.getElementById('sampleAttach');
	var att = sample.cloneNode(true);
	sample.parentElement.appendChild(att);
	att.style.display = 'block';
	var sel = att.getElementsByTagName('SELECT');
	var inp = att.getElementsByTagName('INPUT');
	sel[0].onchange = function() {fsrcChanged(att);};
	sel[1].onchange = function() {ftypeChanged(att);};
	var i;
	for(i = 0; i < sel.length; i++)
		sel[i].name = sel[i].name.substr(0, sel[i].name.length - 1) + attCount + "]";
	for(i = 0; i < inp.length; i++)
		inp[i].name = inp[i].name.substr(0, inp[i].name.length - 1) + attCount + "]";
	return att;
}
function addField() {
	var sample = document.getElementById('sample');
	var inj = sample.cloneNode(true);
	sample.parentElement.appendChild(inj);
	inj.style.display = 'block';
	return inj;
}
function removeField(button) {
	var inj = button.parentElement.parentElement.parentElement.parentElement.parentElement;
	inj.removeNode(true);
}
function setField(inj, src, srcId, type, validator, errorText) {
	var inputs = inj.getElementsByTagName('INPUT');
	var selects = inj.getElementsByTagName('SELECT');
	selects[0].selectedIndex = src;
	inputs[0].value = srcId;
	inputs[0].onchange();
	selects[1].selectedIndex = type;
	inputs[1].value = validator;
	inputs[2].value = errorText;
}
function setFile(att, src, srcId, type, ext, size, errorText, isReq) {
	var inputs = att.getElementsByTagName('INPUT');
	var selects = att.getElementsByTagName('SELECT');
	selects[0].selectedIndex = src;
	inputs[0].value = srcId;
	selects[1].selectedIndex = type;
	inputs[1].value = ext;
	inputs[2].value = size;
	inputs[3].value = errorText;
	inputs[4].checked = isReq > 0;
	selects[0].onchange();
	selects[1].onchange();
	inputs[0].onchange();
}
function fsrcChanged(att) {
	var inputs = att.getElementsByTagName('INPUT');
	var selects = att.getElementsByTagName('SELECT');
	var dis = selects[0].selectedIndex == 1;
	if(dis)
		selects[1].selectedIndex = 0;
	selects[1].disabled = dis;
	inputs[1].disabled = dis;
	inputs[2].disabled = dis;
	inputs[3].disabled = dis;
	inputs[4].disabled = dis;
}
function ftypeChanged(att) {
	var inputs = att.getElementsByTagName('INPUT');
	var selects = att.getElementsByTagName('SELECT');
	inputs[4].disabled = selects[1].selectedIndex == 1 || selects[1].disabled;
}
</script>
<style>
</style>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_OBJECT?></h3>
<form name="f" method="POST" action="i_miscellany.php">
	<input type="hidden" name="action" value="edit_miscsendmail">
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
<?php
	include('../select_tpl._wc');
?>
	<script>
		f.enableCaching.disabled = true;
		f.tpl_args.disabled = true;
	</script>
	<fieldset style="padding:10"><legend><?=OBJ_PROPERTIES?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_MAIL_SUBJECT?>:</td>
			<td><input type="text" name="subject" value="<?=htmlspecialchars($obj->subject, ENT_COMPAT, 'UTF-8')?>" size="40"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_MAIL_FROM?>:</td>
			<td><input type="text" name="from" value="<?=htmlspecialchars($obj->from, ENT_COMPAT, 'UTF-8')?>" size="40"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_MAIL_TO?>:</td>
			<td><input type="text" name="to" value="<?=htmlspecialchars($obj->to, ENT_COMPAT, 'UTF-8')?>" size="40"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_MAIL_CC?>:</td>
			<td><input type="text" name="cc" value="<?=htmlspecialchars($obj->cc, ENT_COMPAT, 'UTF-8')?>" size="40"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_MAIL_BCC?>:</td>
			<td><input type="text" name="bcc" value="<?=htmlspecialchars($obj->bcc, ENT_COMPAT, 'UTF-8')?>" size="40"></td>
		</tr>
		<tr>
			<td colspan=2>
		<fieldset style="padding:10"><legend><?=PR_MAIL_CONTENT_TYPE?></legend>
		<table style="margin:5 0" cellspacing="3">
			<tr>
				<td colspan="2"><input type=checkbox name="isHtml" value="true" <?=$obj->isHtml?'checked':''?> onclick="f.allowedTags.disabled = !this.checked"> <?=PR_MSG_IS_HTML?></td>
			</tr>
			<tr>
				<td colspan=2><?=PR_MSG_ALLOWED_TAGS?>:</td>
			</tr>
			<tr>
				<td colspan=2><textarea cols="52" rows="4" name="allowedTags"<?=$obj->isHtml?'':'DISABLED'?>><?=$obj->allowedTags?></textarea></td>
			</tr>
		</table>
		</fieldset><br>
			</td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=OBJ_CAPTCHA_PROPS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_CAPTCHA_ID?>:</td>
			<td><input type="text" name="cId" onpropertychange="f.captcha.disabled = !this.value.match(/[^\s]+/)" value="<?=$obj->cId?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_CAPTCHA_VALUE?>:</td>
			<td>
				<select name="captcha"><option value="" style="background: #eee">-&nbsp; &nbsp;
				<?php
					for($i = 0, $l = sizeof($obj->fields); $i < $l; $i++)
						echo sprintf('<option value="%1$s"%2$s>%1$s', $obj->fields[$i][1], $obj->cId && $obj->fields[$i][1] == $obj->captcha ? ' selected' : '');
				?>
				</select>
				<script>f.cId.onpropertychange();</script>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="closeSess" id="ch.closeSess" value="true" <?=$obj->closeSess ? 'checked' : ''?>> <label for="ch.closeSess"><?=PR_CLOSE_SESS?></label></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=OBJ_MSG_ATTACHMENTS?></legend>
		<div>
		<fieldset id="sampleAttach" style="margin:20px 0 30px;display:none;">
		<legend style="font-weight:bold;"></legend>
		<table style="margin:5 0" cellspacing="3" width="100%">
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td><?=H_MSG_FIELD_SRC?></td>
				<td><?=H_MSG_FILE_SRC_PARAM?></td>
				<td nowrap><?=H_MSG_FIELD_TYPE?></td>
				<td rowspan="7" valign="top"><img src="../../i/default/e/h/remove.gif" style="cursor:pointer;margin-right:10px;" onclick="removeField(this)" alt="<?=BTN_REMOVE_ATTACHMENT?>"></td>
			</tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_MSG_ATTACHMENT?></td>
				<td>
					<select size="1" name="attachSrc[]">
						<option value="<?=FSRC_FILES?>">$_FILES
						<option value="<?=FSRC_FILESYS?>">Filesystem
					</select>
				</td>
				<td><input type="text" name="attachSrcId[]" size="30" onchange="this.parentElement.parentElement.parentElement.parentElement.parentElement.getElementsByTagName('LEGEND')[0].innerHTML = this.value"></td>
				<td>
					<select size="1" name="attachType[]">
						<option value="<?=FTYPE_FILE?>">File
						<option value="<?=FTYPE_FILES?>">Files
					</select>
				</td>
			</tr>
			<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td nowrap colspan="3"><?=H_MSG_FILE_ALLOWED_EXT?></td>
			</tr>
			<tr>
				<td nowrap style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_MSG_FILE_EXT?></td>
				<td colspan="3"><input type="text" size="60" name="ext[]"></td>
			</tr>
			<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td nowrap colspan="3"><?=H_MSG_FILE_ALLOWED_SIZE?></td>
			</tr>
			<tr>
				<td nowrap style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_MSG_FILE_SIZE?></td>
				<td colspan="3"><input type="text" size="60" name="size[]"></td>
			</tr>
			<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td nowrap><?=H_MSG_FIELD_ERROR_TEXT?></td>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_MSG_FIELD_ERROR?></td>
				<td colspan="3"><input type="text" size="60" name="attachError[]"></td>
			</tr>
			<tr>
				<td colspan="4"><input type="checkbox" value="true" name="attachIsReq[]"><?=H_MSG_ATTACH_IS_REQ?></td>
			</tr>
			</tbody>
		</table>
		</fieldset>
		</div>
		<input type="button" value="<?=BTN_ADD_ATTACHMENT?>" onclick="addAttachment()">
	<script>
	<?php
		foreach((array) $obj->files as $f) {
			$src = $f[0] % 10 - 5;
			$type = intval($f[0] / 10) - 3;
			echo "setFile(addAttachment(), $src, '".addslashes($f[1])."', $type, '".addslashes($f[2])."', '".addslashes($f[3])."', '".addslashes($f[5])."', ".($f[4] ? 1 : 0).");\n";
		}
	?>
	</script>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=OBJ_MSG_FIELDS?></legend>
		<div>
		<fieldset id="sample" style="margin:20px 0 30px;display:none;">
		<legend style="font-weight:bold;"></legend>
		<table style="margin:5 0" cellspacing="3" width="100%">
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td><?=H_MSG_FIELD_SRC?></td>
				<td><?=H_MSG_FIELD_SRC_PARAM?></td>
				<td nowrap><?=H_MSG_FIELD_TYPE?></td>
				<td rowspan="7" valign="top"><img src="../../i/default/e/h/remove.gif" style="cursor:pointer;margin-right:10px;" onclick="removeField(this)" alt="<?=BTN_REMOVE_MSG_FIELD?>"></td>
			</tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_MSG_FIELD?></td>
				<td>
					<select size="1" name="src[]">
						<option value="<?=FSRC_CTX?>">Context
						<option value="<?=FSRC_GET?>">GET
						<option value="<?=FSRC_POST?>">POST
						<option value="<?=FSRC_COOKIE?>">Cookie
						<option value="<?=FSRC_SESSION?>">Session
					</select>
				</td>
				<td><input type="text" name="srcId[]" size="30" onchange="this.parentElement.parentElement.parentElement.parentElement.parentElement.getElementsByTagName('LEGEND')[0].innerHTML = this.value"></td>
				<td>
					<select size="1" name="type[]">
						<option value="<?=FTYPE_TXT?>">Text
						<option value="<?=FTYPE_HTML?>">HTML
					</select>
				</td>
			</tr>
			<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td nowrap><?=H_MSG_FIELD_VALIDATOR?></td>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_MSG_FIELD_VALIDATION?></td>
				<td colspan="3"><input type="text" size="60" name="validator[]"></td>
			</tr>
			<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
			<tr style="font-size:9px;color:#888;">
				<td>&nbsp;</td>
				<td nowrap><?=H_MSG_FIELD_ERROR_TEXT?></td>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_MSG_FIELD_ERROR?></td>
				<td colspan="3"><input type="text" size="60" name="error[]"></td>
			</tr>
			</tbody>
		</table>
		</fieldset>
		</div>
		<input type="button" value="<?=BTN_ADD_MSG_FIELD?>" onclick="addField()">
	<script>
	<?php
		foreach($obj->fields as $f) {
			$src = $f[0] % 10;
			$type = intval($f[0] / 10) - 1;
			echo "setField(addField(), $src, '".addslashes($f[1])."', $type, '".addslashes($f[2])."', '".addslashes($f[3])."');\n";
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