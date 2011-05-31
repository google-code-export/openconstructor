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
 * $Id: adduser.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('users');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/users._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');
	
	$group = &GroupFactory::getGroup(@$_GET['group_id']);
	assert($group != null);
?>
<html>
<head>
<title><?=WC.' | '.CREATE_USER?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.name.value.match(re)||!f.login.value.match(re)||<?=WCS::decide($group, 'createuser') ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=CREATE_USER?></h3>
<form name="f" method="POST" action="i_users.php" onsubmit="if(f.password1.value!=f.password2.value) {alert('<?=H_PASSWORDS_DOESNT_MATCH?>');return false;}">
	<input type="hidden" name="group_id" value="<?=$group->id?>">
	<input type="hidden" name="action" value="add_user">
	<fieldset style="padding:10"><legend><?=USER?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=USR_LOGIN?>:</td>
			<td><input type="text" name="login" size="32" maxlength="32" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td><?=USR_NAME?>:</td>
			<td><input type="text" name="name" size="32" maxlength="128" onpropertychange="dsb()"></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=USR_ADDITIONAL_INFO?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=USR_EMAIL?>:</td>
			<td><input type="text" name="email" size="40" maxlength="255"></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=USR_SET_PASSWORD?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=USR_PASSWORD?>:</td>
			<td><input type="password" name="password1" size="32" maxlength="32"></td>
		</tr>
		<tr>
			<td><?=USR_CONFIRM_PASSWORD?>:</td>
			<td><input type="password" name="password2" size="32" maxlength="32"></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_CREATE?>" DISABLED name="create"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form><br><br>
</body>
</html>