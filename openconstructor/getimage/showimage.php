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
 * $Id: showimage.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');

	assert(@$_GET['id'] > 0);
	$db = WCDB::bo();
	$res = $db->query(
		'SELECT id, name, filename, size, type, date'.
		' FROM dsfile'.
		' WHERE id='.$_GET['id']
	);
	$image = mysql_fetch_assoc($res);
	mysql_free_result($res);
	assert(is_array($image));
	$s = getimagesize($_SERVER['DOCUMENT_ROOT'].$image['filename']);
	$width = $s[0] + 90;
	$height = $s[1] + 160;
	if($width > 771) $width = 799;
	if($width < 260) $width = 260;
	if($height > 550) $height = 550;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=H_VIEW_IMAGE.' | '.$image['name']?></title>
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
window.resizeTo(<?=$width?>,<?=$height?>);
function insert(){
try{
	if(window.opener.junk)
	{
		window.opener.getRange().execCommand('InsertImage',false,'<?=$image['filename']?>');
		window.opener.focus();
		window.close();
	}
}catch(RuntimeException){document.all('insert').disabled=true;alert('<?=YOU_HAVE_CLOSED_EDITOR_W?>');}
}
</script>
<style>
</style>
</head>
<body style="border:groove 2px;padding:5 10">
<h3 NOWRAP><?=H_VIEW_IMAGE?></h3>
<div align="center">
<div style="width:1px;height:1px;border:solid 1 gray;margin:5 10;padding:5px;background:white;">
<img src="<?=$image['filename']?>" TITLE="<?=$image['filename'].' ['.$s[0].'x'.$s[1].']'?>">
</div>
<div style="clear:both"><input type="button" name="insert" value="<?=BTN_INSERT_THIS_IMAGE?>" onclick="insert()"> <input type="button" value="<?=BTN_CLOSE_WINDOW?>" onclick="window.close()"></div>
</div>
</body>
</html>