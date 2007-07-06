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
 * $Id: editstyle.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	if(!isset($_GET['tag'])||!isset($_GET['class'])||!isset($_GET['style']))
		die('<script>window.returnValue=new Array(true);window.close()</script>');
	$fd=fopen($_SERVER['DOCUMENT_ROOT'].'/css/content.css',"rb");
	$file=fread($fd, filesize($_SERVER['DOCUMENT_ROOT'].'/css/content.css'));
	fclose($fd);
	preg_match_all('/((^)|(\}))\s*(([^\}]+\s)?\s*(('.$_GET['tag'].'\.?)|(\.))([a-z0-9_]*))\s*\{([^\}]*)/usim',$file,$matches);
	$classes=array(''=>'');$cssText='';
	for($i=0;$i<sizeof($matches[0]);$i++)
	{
		$classes[$matches[9][$i]]=$matches[4][$i];
		if($matches[9][$i]==$_GET['class'])
			$cssText=str_replace(array("\t","\n",';'),array('','',";\n"),$matches[10][$i]);
	}
	$style=str_replace(';',";\n",preg_replace('/(:|;)(\s*)/usi','\\1',$_GET['style']));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=H_EDIT_STYLE?></title>
<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	window.returnValue=new Array(true);
	function sbmt()
	{
		window.returnValue=new Array(true,f.sClass.options(f.sClass.selectedIndex).value,f.sStyle.value);
		window.close();
	}
	function changeClass()
	{
		window.returnValue=new Array(false,f.sClass.options(f.sClass.selectedIndex).value,f.sStyle.value);
		window.close();
	}
</script>
</head>
<body style="border-style:groove;border-width:2px;padding:5 10;">
<br>
<h3><?=H_EDIT_STYLE?></h3>
<form name="f" onsubmit="sbmt();return false">
<table border="0">
<tr><td width=70><?=H_TAG_NAME?>:</td><td><b><?=$_GET['tag']?></b></td></tr>
<tr><td valign="top"><?=H_CSS_CLASS?>:</td><td>
<SELECT size=1 name="sClass" onchange="changeClass()">
<?php
	foreach($classes as $k=>$v)
		echo '<OPTION value="'.$k.'" '.($k==$_GET['class']?'SELECTED':'').'>'.$v;
?></SELECT><br>
<textarea wrap="off" cols=54 rows=5 name="cssStyle" onkeydown="return false" oncut="return false" style="cursor:default;color:gray"><?=$cssText?></textarea>
</td></tr>
<tr><td valign="top"><?=H_CSS_STYLE?>:</td><td>
<textarea wrap="off" cols=54 rows=5 name="sStyle"><?=$style?></textarea></td></tr>
</table>
<div align="right"><input type="submit" value="<?=BTN_OK?>" style="width:100"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()" style="width:100"></div>
</form>
<script>//alert(f.cssStyle.contentEditable);</script>
</body>
</html>
