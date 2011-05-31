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
 * $Id: index.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=H_INSERT_IMAGE?></title>
<link href="<?=WCHOME?>/<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	editor=dialogArguments[0].parent;
	window.returnValue=false;
	function sbmt(){
		window.returnValue=f.img.value;
		window.close();
	}
	function upload(){
		editor.wxyopen('/openconstructor/data/file/edit.php?id=new&ds_id='+fimages.folderID,590,400);
	}
</script>
</head>
<body style="border-style:groove;border-width:2px;padding:5 10;">
<br>
<h3><?=H_INSERT_IMAGE?></h3>
<form name="f" onsubmit="return false">
<input type="hidden" name="img" value="">
<table border="0" width="100%" height="80%"><tr>
<td width="40%"><iframe src="folders.php" width="100%" height="100%"></iframe></td>
<td width="60%"><iframe src="images.php" width="100%" height="100%" name="fimages"></iframe></td>
</tr></table>
<div align="right"><input type="button" disabled name="insert" value="<?=BTN_INSERT_THIS_IMAGE?>" onclick="sbmt()"> <input type="button" disabled value="<?=BTN_UPLOAD_IMAGE?>" title="<?=TT_UPLOAD_IMAGE?>" name="create" onclick="upload()"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
</body>
</html>