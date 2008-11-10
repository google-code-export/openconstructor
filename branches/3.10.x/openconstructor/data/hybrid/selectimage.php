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
 * $Id: selectimage.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
?>
<html>
<head>
<title><?=H_INSERT_IMAGE?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Pragma" content="no-cache">
	<link href="../../<?=SKIN?>.css" type=text/css rel=stylesheet>
	<script src="../../common.js"></script>
	<script language="JavaScript" type="text/JavaScript">
		var doctype = "<?=@$_GET['doctype']?>", type = "<?=@$_GET['type']?>", ds_id = <?=intval(@$_GET['ds_id'])?>;
		returnValue = null;
		function startsearch() {
			enableSelect(false);
			query = document.getElementById("f.query").value;
			results = document.getElementById("f.result");
			results.src = "images.php?keyword=" + query;
		}
		function enableSelect(enable) {
			document.getElementById("btn.select").disabled = !enable;
		}
		function selectDocuments() {
			var select = null;
			try {
				select = document.getElementById("f.result").contentWindow.document.getElementById("s.docs");
			} catch(e) {return false;}
			if(select.selectedIndex >= 0)
				returnValue = select.options[select.selectedIndex].value;
			if(returnValue.length > 0)
				window.close();
		}
	</script>
	<style>
	</style>
</head>
<body style="background:#f2f2f2;border-style:groove;padding:0;border-width:2px;" onkeypress="if(event.keyCode==27) window.close();">
<table width="100%" cellpadding="0" cellspacing="0" border="0" height="100%">
	<tr height="35"><td style="padding:0 5px;border-bottom:solid 1px #999;background:#ccc;">
		<form onsubmit="startsearch();return false;" style="padding:0px;margin:0px;">
		<input type="text" id="f.query" size="40"> <input type="submit" value="<?=BTN_SEARCH?>">
		</form>
	</td></tr>
	<tr><td><iframe src="images.php" height="100%" width="100%" id="f.result" frameborder="0"></iframe></td></tr>
	<tr height="40"><td align="right" style="padding:0 5px;border-top:solid 1px #999;"><input type="button" value="<?=BTN_INSERT_IMG?>" id="btn.select" disabled onclick="selectDocuments();"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close();"></td></tr>
</table>
</body>
</html>