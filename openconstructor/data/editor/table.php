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
 * $Id: table.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	require_once(LIBDIR.'/templates/wctemplates._wc');
	$wt = new WCTemplates();
	$tpls = $wt->get_all_tpls('importtables');
	$prototype = @$_GET['prototype'];
	assert(!$prototype || isset($tpls[$prototype]) == true);
?>
<html>
	<head>
		<title><?=H_IMPORT_TABLE?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
	<script src="../editor.js"></script>
	<script>
		window.returnValue = false;
		tbl = dialogArguments[0].parent.curTbl;
	</script>
	<script src="import.js"></script>
	</head>
<body style="border:groove;border-width:2;margin:10;" ondrag="return false">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="99%">
<tr height="100%" valign="top"><td style="padding-bottom:5px">
<iframe name="content" style="border:none;margin:0px;" width="100%" height="100%" src="<?=$prototype?>"></iframe>
</td></tr><tr><td align=right>
	<?=H_TEMPLATE?>: <select size=1 name=prtotype onchange="if(this.options[0].value == 0) this.options[0].removeNode(true);loadprot(this.options(this.selectedIndex).value)">
		<option value="0"><?=H_CHOOSE_TEMPLATE?>
<?php
	foreach($tpls as $k => $v)
		echo '<option value="'.$k.'"'.($k==$prototype?' SELECTED':'').'>'.$v.'</option>';
?>
	</select>
	<input type="button" value="<?=BTN_IMPORT?>" onclick="tblimport();this.disabled=!(btnok.disabled=false);" disabled name=btnimp style=""> <input name=btnok type="button" disabled value="<?=BTN_INSERT_IMPORTED_TABLE?>" onclick="tblinsert();window.close()" style="">
</td></tr><table>
<script defer>
	theHTML=content;
	window.inter=window.setInterval(setcontent,250);
	function setcontent(a)
	{
		if(theHTML.document.body)
		{
			window.clearInterval(window.inter);
			head=theHTML.document.childNodes(0).childNodes(0);
			meta=theHTML.document.createElement('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
			head.appendChild(meta);
			link=theHTML.document.createElement('<link href="http://<?=$_host?>/default.css" type=text/css rel=stylesheet>');
			head.appendChild(link);
			content.document.body.style.background='white';
			content.document.body.style.margin='5px';
			if(content.location.toString().substr(0,7)=='http://') btnimp.disabled=false;
		}
	}
</script>
</body>
</html>