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
 * $Id: switchuser.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
?>
<html>
<head>
<title><?=SWITCH_USER?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	window.returnValue=false;
function sbmt()
{
	window.returnValue=new Array(f.login.value,f.password.value);
	window.close();
}
</script>
</head>
<body style="border-style:groove;border-width:2px" onkeypress="if(event.keyCode==27) window.close()">
<br>
<center>
<h3><?=SWITCH_USER?></h3>
<form name="f" onsubmit="return false">
<table border="0" cellspacing="0" cellpadding="0">
<tr> 
  <td><?=USR_LOGIN?></td>
  <td>&nbsp;:&nbsp;</td>
  <td><input name="login" type="text"></td>
</tr>
<tr> 
  <td><?=USR_PASSWORD?></td>
  <td>&nbsp;:&nbsp;</td>
  <td><input type="password" name="password"></td>
</tr>
<tr> 
	<td colspan="3" align="right" style="padding-top:5px">
	<input type="submit" value="<?=BTN_AUTHORIZE?>" onclick="sbmt()">
	<input type="button" value="<?=BTN_CANCEL?>" onclick="window.close();">
    </td>
</tr>
</table>
</form>
</center>
</body>
</html>