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
 * $Id: editsecrets.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/users._wc');
	require_once(LIBDIR.'/security/user._wc');
	
	$user = User::load(@$_GET['id']);
	assert($user != null);
	WCS::request($user, 'edit.pwd');
	loadClass('userfactory', '/security/userfactory._wc');
	$secretQ = UserFactory::getSecretQuestion($user->id);
?>
<html>
<head>
<title><?=WC.' | '.EDIT_USER_SECRETS?> <?=$user->login?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(<?=$user->id == Authentication::getOriginalUserId() ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_USER_SECRETS?></h3>
<form name="f" method="POST" action="i_users.php">
	<input type="hidden" name="id" value="<?=$user->id?>">
	<input type="hidden" name="action" value="edit_secrets">
	<fieldset style="padding:10"><legend><?=USER?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=USR_LOGIN?>:</td>
			<td><b><?=$user->login?></b></td>
		</tr>
		<tr>
			<td><?=USR_NAME?>:</td>
			<td><?=htmlspecialchars($user->name, ENT_COMPAT, 'UTF-8')?></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=USR_SECRETS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td valign="top" nowrap><?=USR_SECRET_QUESTION?>:</td>
	<?php 	if($secretQ) {?>
			<td><b><?=$secretQ?></b></td>
	<?php	} else { ?>
			<td><span style="color:red"><?=USR_SECRET_Q_NOTSET?></span></td>
	<?php	}?>
		</tr>
		<tr>
			<td><?=USR_PASSWORD?>:</td>
			<td><input type="password" name="pwd" size="32" maxlength="32"></td>
		</tr>
		<tr>
			<td><?=USR_NEW_SECRET_QUESTION?>:</td>
			<td><input type="text" name="secretQ" size="64" maxlength="64"></td>
		</tr>
		<tr>
			<td><?=USR_SECRET_ANSWER?>:</td>
			<td><input type="text" name="secretA" size="40" maxlength="40"></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>