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
 * $Id: gbaddmsglogic.php,v 1.12 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	
	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);
	$_dsm=new DSManager();
	$ds = $_dsm->getAll($obj->ds_type);
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
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_OBJECT?></h3>
<form name="f" method="POST" action="i_guestbook.php">
	<input type="hidden" name="action" value="edit_gbaddmsglogic">
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
	<fieldset style="padding:10" <?=WCS::decide($obj, 'editobj.ds') ? '' : 'disabled'?>><legend><?=OBJ_DATA?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap valign="top"><?=PR_DATASOURCES?>:</td>
			<td><select size="10" name="ds_id[]" multiple>
<?php
	$ds_ids=explode(',',$obj->ds_id);
	foreach($ds as $v)
		echo '<OPTION VALUE="'.$v['id'].'"'.(array_search($v['id'],$ds_ids)!==false?' SELECTED':'').'>'.
			$v['name'];
?>	
			</select></td>
		</tr>
		<tr>
			<td><?=PR_DEFINE_GB_DYNAMICALLY_BY_POST?>:</td>
			<td><input type=checkbox onclick="f.gbid.disabled=!this.checked" <?=@$obj->gbid?'checked':''?>><input type="text" name="gbid" value="<?=@$obj->gbid?$obj->gbid:'subject'?>" <?=!@$obj->gbid?'disabled':''?>></td>
		</tr>
		<tr>
			<td valign="top"><?=PR_DEFAULT_GB?>:</td>
			<td><select size="10" name="defaultgb">
<?php
	foreach(array_keys($ds) as $ds_id)
		echo '<OPTION VALUE="'.$ds_id.'"'.($ds_id==@$obj->defaultGB?' SELECTED':'').'>'.
			$ds[$ds_id]['name'];
?>	
			</select></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=OBJ_PROPERTIES?></legend>
		<table style="margin:5 0" cellspacing="3">
			<tr>
				<td nowrap><?=PR_GB_SUBJECT_ID?>:</td>
				<td nowrap><input type="checkbox" name="r_subject" <?=$obj->fields['subject']['required']?'checked':''?> value="true"> <input type="text" name="subjectid" value="<?=$obj->fields['subject']['id']?>"></td>
			</tr>
			<tr>
				<td nowrap><?=PR_GB_MESSAGE_ID?>:</td>
				<td nowrap><input type="checkbox" name="r_message" <?=$obj->fields['message']['required']?'checked':''?> value="true"> <input type="text" name="messageid" value="<?=$obj->fields['message']['id']?>"></td>
			</tr>
			<tr>
				<td nowrap><?=PR_GB_AUTHOR_ID?>:</td>
				<td nowrap><input type="checkbox" name="r_author" <?=$obj->fields['author']['required']?'checked':''?> value="true"> <input type="text" name="authorid" value="<?=$obj->fields['author']['id']?>"></td>
			</tr>
			<tr>
				<td nowrap><?=PR_GB_EMAIL_ID?>:</td>
				<td nowrap><input type="checkbox" name="r_email" <?=$obj->fields['email']['required']?'checked':''?> value="true"> <input type="text" name="emailid" value="<?=$obj->fields['email']['id']?>"></td>
			</tr>
		</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=OBJ_CAPTCHA_PROPS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_CAPTCHA_ID?>:</td>
			<td><input type="text" name="cId" onpropertychange="f.cVal.disabled = !this.value.match(/[^\s]+/)" value="<?=$obj->cId?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_GB_CAPTCHA_VALUE?>:</td>
			<td>
				<input type="text" name="cVal" value="<?=$obj->cVal?>">
				<script>f.cId.onpropertychange();</script>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="closeSess" id="ch.closeSess" value="true" <?=$obj->closeSess ? 'checked' : ''?>> <label for="ch.closeSess"><?=PR_CLOSE_SESS?></label></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=PR_ADDMSG_POLICY?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td colspan="2"><input name="policy" type="radio" <?=$obj->policy == GBAML_ADD_AS_FROMAUTH ? 'CHECKED' : ''?> value="<?=GBAML_ADD_AS_FROMAUTH?>"> <?=H_GBAML_ADD_AS_FROMAUTH?></td>
		</tr>
		<tr>
			<td colspan="2"><input name="policy" type="radio" <?=$obj->policy == GBAML_ADD_AS_DSOWNER ? 'CHECKED' : ''?> value="<?=GBAML_ADD_AS_DSOWNER?>"> <?=H_GBAML_ADD_AS_DSOWNER?></td>
		</tr>
		<tr>
			<td colspan="2"><input name="policy" type="radio" <?=$obj->policy == GBAML_ADD_AS_SPECIFIED ? 'CHECKED' : ''?> value="<?=GBAML_ADD_AS_SPECIFIED?>"> <?=H_GBAML_ADD_AS_SPECIFIED?> <input type="text" name="addAs" value="<?=$obj->addAs?>" style="vertical-align:middle"></td>
		</tr>
		<tr>
			<td colspan="2"><input name="ignoreDsAuths" type="checkbox" <?=$obj->ignoreDsAuths ? 'CHECKED' : ''?>> <?=PR_IGNORE_DS_AUTHS?></td>
		</tr>
	</table>
	<script>
		for(var i = 0; i < 3; i++)
			f.policy[i].onclick = function() {f.addAs.disabled = !f.policy[2].checked};
		f.policy[0].onclick();
	</script>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=PR_NOTIFICATION?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td colspan="2"><input id="ch.notifyEmail" type="checkbox"<?=@$obj->notifyEmail?' CHECKED':''?> onclick="f.notifyEmail.disabled=!this.checked;f.mailSubject.disabled=!this.checked"> <label for="ch.notifyEmail"><?=PR_NOTIFY_ON_NEW_MESSAGE?></label></td>
		</tr>
		<tr>
			<td><?=PR_NOTIFY_TO_EMAIL?>:</td>
			<td><input type="text" name="notifyEmail" size="64" maxlength="128" value="<?=$obj->notifyEmail?>"<?=@$obj->notifyEmail?'':'DISABLED'?>></td>
		</tr>
		<tr>
			<td><?=PR_MAIL_SUBJECT?>:</td>
			<td><input type="text" name="mailSubject" size="64" maxlength="64" value="<?=htmlspecialchars($obj->mailSubject, ENT_COMPAT, 'UTF-8')?>"<?=$obj->notifyEmail?'':'DISABLED'?>></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right">
	<input type="button" value="<?=BTN_MANAGE_OBJECT_USES?>" style="float: left;" onclick="openObjectUses(<?=$obj->obj_id?>);">
	<input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div><br><br><br>
</form>
<script>dsb();</script>
</body>
</html>