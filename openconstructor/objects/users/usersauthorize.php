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
 * $Id: usersauthorize.php,v 1.9 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	
	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);
	require_once(LIBDIR.'/security/groupfactory._wc');
	$gf = &GroupFactory::getInstance();
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
<form name="f" method="POST" action="i_users.php">
	<input type="hidden" name="action" value="edit_usersauthorize">
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
	<fieldset style="padding:10"><legend><?=OBJ_PROPERTIES?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_LOGIN_ID?>:</td>
			<td><input type="text" name="loginid" value="<?=$obj->loginID?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_PASSWORD_ID?>:</td>
			<td><input type="text" name="passwordid" value="<?=$obj->passwordID?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_DEFAULT_NEXT_PAGE?>:</td>
			<td><input type="text" name="defaultNextPage" value="<?=$obj->defaultNextPage?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_NEXT_PAGE_KEY?>:</td>
			<td><input type="text" name="nextPageKey" value="<?=$obj->nextPageKey?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_LOGIN_PAGE_KEY?>:</td>
			<td><input type="text" name="loginPageKey" value="<?=$obj->loginPageKey?>"></td>
		</tr>
		<tr>
			<td colspan="2">
				<script>
					function onRememberMe(ch) {
						this.clicks = this.clicks ? this.clicks + 1 : 1;
						document.getElementById('fs.autologin').disabled = !ch.checked;
						if(this.clicks == 1 && ch.checked && <?=$obj->allowAutoLogin ? 'false' : 'true'?>)
							f.allowAutoLogin.value = 90;
					}
				</script>
				<input type="checkbox" id="ch.autologin" onclick="onRememberMe(this);" <?=$obj->allowAutoLogin?'checked':''?>>
				<label for="ch.autologin"><?=PR_ALLOW_AUTOLOGIN?></label>
				<fieldset id="fs.autologin" <?=$obj->allowAutoLogin?'':'disabled'?> style="border: none; padding-left: 20px;">
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td nowrap><?=PR_AUTOLOGIN_ID?>:</td>
						<td><input type="text" name="autoLoginID" value="<?=$obj->autoLoginID?>"></td>
					</tr>
					<tr>
						<td nowrap><?=PR_AUTOLOGIN_TIMEOUT?>:</td>
						<td><input type="text" name="allowAutoLogin" value="<?=$obj->allowAutoLogin?>"></td>
					</tr>
				</table>
				</fieldset>
			</td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=PR_DEFAULT_NEXT_PAGES?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr style="font-size: 115%;">
			<td><?=H_GROUP?></td>
			<td><?=H_NEXTPAGE_URI?></td>
		</tr>
		<?php
			$groups = $gf->getAllGroups();
			foreach($groups as $id => $title) :
		?>
		<tr>
			<td nowrap><?=$title?>:</td>
			<td><input type="text" name="homes[<?=$id?>]" value="<?=@$obj->homes[$id]?>" size="40" style="font-family: monospace"></td>
		</tr>
		<?php endforeach;?>
	</table>
	</fieldset><br>
	<div align="right">
	<input type="button" value="<?=BTN_MANAGE_OBJECT_USES?>" style="float: left;" onclick="openObjectUses(<?=$obj->obj_id?>);">
	<input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div>
</form>
<script>dsb();</script>
</body>
</html>
