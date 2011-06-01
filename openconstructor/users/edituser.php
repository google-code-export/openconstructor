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
 * $Id: edituser.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/users._wc');
	require_once(LIBDIR.'/security/user._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');
	
	$user = &User::load(@$_GET['id']);
	assert($user != null);
	WCS::_request(WCS::decide($user, 'edit') || System::decide('users'));
	$group = &GroupFactory::getGroup($user->groupId);
?>
<html>
<head>
<title><?=WC.' | '.EDIT_USER?> <?=$user->login?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script src="<?=WCHOME?>/lib/js/base.js"></script>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.name.value.match(re)||<?=WCS::decide($group, 'edituser') || WCS::decide($user, 'edit') ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
function checkForm() {
	if(f.newpassword && f.newpassword.checked)
		if(f.password1.value != f.password2.value) {
			alert('<?=H_PASSWORDS_DOESNT_MATCH?>');
			return false;
		}
	return true;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_USER?></h3>
<form name="f" method="POST" action="i_users.php" onsubmit="return checkForm();">
	<input type="hidden" name="login" value="<?=$user->login?>">
	<input type="hidden" name="action" value="edit_user">
	<fieldset style="padding:10"><legend><?=USER?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=USR_LOGIN?>:</td>
			<td><b><?=$user->login?></b></td>
		</tr>
		<tr>
			<td><?=USR_NAME?>:</td>
			<td><input type="text" name="name" value="<?=htmlspecialchars($user->name, ENT_COMPAT, 'UTF-8')?>" size="40" maxlength="128" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="isDisabled"<?=!$user->active?' checked':''?> <?=WCS::decide($group, 'edituser') || WCS::decide($user, 'edit.status')? '' : ' DISABLED'?>> <?=USR_IS_FREEZED?></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=USR_ADDITIONAL_INFO?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=USR_EMAIL?>:</td>
			<td><input type="text" name="email" value="<?=$user->email?>" size="40" maxlength="255"></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=USR_PROPS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=USR_EXPIRES?>:</td>
			<td><input type="text" name="expiry" <?=WCS::decide($user, 'edit.expiry') ? '' :'disabled'?>value="<?=$user->expiry ? date('j F Y', $user->expiry) : ''?>" size="40" maxlength="255"></td>
		</tr>
	</table>
	</fieldset><br>
<?php
	if(WCS::decide($group, 'edituser') || WCS::decide($user, 'edit.pwd')) {?>
	<fieldset style="padding:10"><legend><?=USR_SET_NEW_PASSWORD?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td colspan="2"><input type="checkbox" name="newpassword" onclick="f.password1.disabled=!this.checked;f.password2.disabled=!this.checked;"><?=USR_CHANGE_PASSWORD?></td>
		</tr>
		<tr>
			<td><?=USR_NEW_PASSWORD?>:</td>
			<td><input type="password" name="password1" disabled size="32" maxlength="32"></td>
		</tr>
		<tr>
			<td><?=USR_CONFIRM_NEW_PASSWORD?>:</td>
			<td><input type="password" name="password2" disabled size="32" maxlength="32"></td>
		</tr>
	</table>
	</fieldset><br>
<?php }
	if(WCS::decide($user, 'edit.pwd') && $user->id == Authentication::getOriginalUserId()) {
		loadClass('userfactory', '/security/userfactory._wc');
		$secretQ = UserFactory::getSecretQuestion($user->id);
?>
	<fieldset style="padding:10"><legend><?=USR_SECRETS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
<?php 	if($secretQ) {?>
			<td valign="top" nowrap><?=USR_SECRET_QUESTION?>:</td>
			<td><i><?=$secretQ?></i></td>
		</tr>
		<tr>
			<td colspan="2"><input type="button" value="<?=BTN_USR_CHANGE_SECRETS?>" onclick="openWindow('editsecrets.php?id=<?=$user->id?>?>', 600, 400)"/></td>
<?php	} else { ?>
			<td colspan="2"><?=USR_SECRET_QUESTION?>: <span style="color:red"><?=USR_SECRET_Q_NOTSET?></span> <input type="button" value="<?=BTN_USR_SET_SECRETS?>" onclick="openWindow('editsecrets.php?id=<?=$user->id?>?>', 600, 400)" style="vertical-align:middle"/></td>
<?php	}?>
		</tr>
	</table>
	</fieldset><br>
<?php }?>
	<fieldset style="padding:10" id=fMembership><legend><?=USR_MEMBERSHIP?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td valign="top"><?=USR_MEMBERSHIP?>:</td>
			<td><select size="10" id="membership" name="membership[]" multiple onchange="for(var i = 0, j = 0; i < this.options.length; i++) if(this.options[i].selected && this.options[i].getAttribute('old') == 'yes') j++; f.groupId.disabled = !(j == <?=sizeof($user->membership)?>);">
			<?php
				$groups = &GroupFactory::getAllGroups();
				foreach($groups as $id => $title)
					echo "<option value='$id'".(array_search($id, $user->membership) !== false ? ' selected old="yes"': '').'>'.$title.'</option>';
			?>
			</select></td>
		</tr>
		<tr>
			<td><?=USR_MAIN_GROUP?>:</td>
			<td><select size="1" name="groupId">
			<?php
				foreach($user->membership as $id)
					echo "<option value='$id'".($id == $user->groupId ? ' selected': '').'>'.$groups[$id];
			?>
			</select></td>
		</tr>
	</table>
	</fieldset><br>
	<?php if(!WCS::decide($user, 'edit.group')):?>
	<script>
		document.getElementById('fMembership').disabled = true;
		document.getElementById('membership').disabled = true;
		f.groupId.disabled = true;
	</script>
	<?php endif;?>
	<?php if(0 && $user->profileId > 0): // TODO: сделать так чтоб работало?>
	<fieldset style="padding:10"><legend><?=USR_PROFILE?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td colspan="2"><a href="javascript:openWindow('../data/hybrid/edit.php?ds_id=<?=$user->profileType?>&id=<?=$user->profileId?>', 788, 520)"><?=USR_EDIT_PROFILE?></a></td>
		</tr>
		<tr>
			<td><?=USR_WC_LANGUAGE?>:</td>
			<td><select size="1" name="wclanguage">
			<?php
				foreach(array(
					'rus'=>USR_WC_RUSSIAN,
					'eng'=>USR_WC_ENGLISH
				) as $id=>$v)
					echo '<option value="'.$id.'"'.($id==$user->profile['language']?' SELECTED':'').'>'.$v.'</option>';
			?>
			</select></td>
		</tr>
		<tr>
			<td><?=USR_WC_SKIN?>:</td>
			<td><select size="1" name="wcskin">
			<?php
				foreach(array(
					'metallic'=>USR_WC_SKIN_METALLIC,
					'classic'=>USR_WC_SKIN_CLASSIC
				) as $id=>$v)
					echo '<option value="'.$id.'"'.($id==$user->profile['skin']?' SELECTED':'').'>'.$v.'</option>';
			?>
			</select></td>
		</tr>
	</table>
	</fieldset><br>
	<?php endif;?>
	<div align="right"><input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form><br><br>
<script>dsb();</script>
</body>
</html>