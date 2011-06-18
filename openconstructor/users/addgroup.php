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
 * $Id: addgroup.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('users');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/users._wc');
?>
<html>
<head>
<title><?=WC.' | '.CREATE_USERGROUP?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
var rek = /^[a-z][a-z\-_0-9]{0,31}$/i;
function dsb(){
	if(!f.key.value.match(rek)||!f.name.value.match(re)||<?=System::decide('users.manage') ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=CREATE_USERGROUP?></h3>
<form name="f" method="POST" action="i_users.php">
	<input type="hidden" name="action" value="add_group">
	<fieldset style="padding:10"><legend><?=USR_NEW_USERGROUP?></legend>
	<table style="margin:10 0">
	<tr><td><?=USR_USERGROUP_KEY?>:</td><td><input type="text" name="key" size="32" maxlength="32" onpropertychange="dsb()">
	<tr><td><?=USR_USERGROUP_NAME?>:</td><td><input type="text" name="name" size="64" maxlength="128" onpropertychange="dsb()">
	</td></tr></table>
	</fieldset><br>
	<div align="right" style="color:red"><input type="submit" value="<?=BTN_CREATE?>" name="create" DISABLED> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form><br><br>
</body>
</html>