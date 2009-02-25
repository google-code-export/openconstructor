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
 * $Id: deleteimg.php,v 1.10 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	
	$ds = & DSManager::load(@$_GET['ds']);
	$type = @$_GET['type'];
	assert($ds != null && ($type == 'main' || $type == 'intro') && @$ds->imagepath);
	$doc = & $ds->getDocument(@$_GET['id']);
	assert($doc != null);
	// TODO: невозможно удалить картинку новости которая внутри гибр документа
	WCS::assertValue(WCS::decide($doc, 'editdoc') || WCS::decide($ds, 'editdoc'), $doc, 'editdoc');
	$isArticle=false;
	$img = FILES.$ds->imagepath;
	$img = $_SERVER['DOCUMENT_ROOT'].$img.$doc->id.($type == 'intro' ? '_' : '');

	$query = $ds->ds_type == 'article'
		? 'SELECT img_main AS main FROM '.$ds->DSTable.' WHERE id='.$doc->id.' AND img_type!="" LIMIT 1'
		: 'SELECT img_intro AS intro, img_main AS main FROM '.$ds->DSTable.' WHERE id='.$doc->id.' AND img_type!="" LIMIT 1';
	$db = &WCDB::bo();
	$res = $db->query($query);
	$a = mysql_fetch_assoc($res);
	mysql_free_result($res);
	assert(is_array($a));
	assert(isset($a[$type]));
	@unlink($img.'.gif');
	@unlink($img.'.jpg');
	@unlink($img.'.png');
	$add = ', img_type=NULL';
	if(!empty($a['intro']) && !empty($a['main']))
		$add = '';
	$db->query('UPDATE '.$ds->DSTable.' SET img_'.$type.'=NULL'.$add.' WHERE real_id='.$doc->id);
?>

<html>
<head>
<title><?=H_IMAGE_WAS_REMOVED_I?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	window.returnValue=true;
</script>
</head>
<body style="padding:10">
<br>
<center>
	<img src="<?=WCHOME.'/i/'.SKIN?>/ico/ico-info.gif" align="left"><?=H_IMAGE_WAS_REMOVED_I?>
	<br><br><br>
	<input type="button" value="<?=BTN_CLOSE_WINDOW?>" onclick="window.close();" style="width:100">
</center>
</body>
</html>