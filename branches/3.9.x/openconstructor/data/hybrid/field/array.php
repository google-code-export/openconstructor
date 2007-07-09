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
 * $Id: array.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	assert(@$_GET['id'] > 0);
	
	require_once(LIBDIR.'/dsmanager._wc');
	$_dsm=new DSManager();
	require_once(LIBDIR.'/hybrid/fields/fieldfactory._wc');
	$field = FieldFactory::getField($_GET['id']);
	assert(is_object($field) && $field->family == 'array');
	$ds = &$_dsm->load($field->ds_id);
?>
<html>
<head>
<title><?=H_EDIT_FIELD?> "<?=htmlspecialchars($field->header, ENT_COMPAT, 'UTF-8')?>"</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script src="../../../common.js"></script>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
//var rek=new RegExp('^[a-zA-Z][a-zA-Z0-9_]{0,15}$','g');
function dsb(){
	if(!f.header.value.match(re)||<?=WCS::decide($ds, 'editds')?'false':'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
function prepare() {
	return true;
}
</script>
<style>
	TD.opt {padding-left:30px;}
	FIELDSET {
		padding:10px;
		display:none;
	}
	LEGEND {
		margin-bottom:5px;
	}
</style>
</head>
<body style="background:#f2f2f2;border-style:groove;padding:0">
<h3 class="new"><?=H_EDIT_FIELD?></h3>
<form name="f" method="POST" action="../i_hybrid.php" style="padding:0;margin:0;" onsubmit="return prepare();">
	<div style="padding:20px;background:#fff;">
	<input type="hidden" name="action" value="edit_field">
	<input type="hidden" name="id" value="<?=$field->id?>">
	<table border="0" cellspacing="2">
		<tr>
			<td><?=H_DSH_FIELD_TYPE?>:</td>
			<td style="font:bold 15px courier new;"><?=ucfirst($field->type)?></td>
		</tr>
		<tr>
			<td>ID:</td>
			<td><input type="text" name="key" size="20" maxlength="16" value="<?=substr($field->key, 2)?>" disabled></td>
		</tr>
		<tr>
			<td valign="top"><?=H_DSH_FIELD_NAME?>:</td>
			<td><textarea name="header" cols="40" rows="3" onpropertychange="dsb()"><?=$field->header?></textarea></td>
		</tr>
		<tr>
			<td><?=H_DSH_FIELD_CHOOSEFROM?>:</td>
			<td>
				<?php
					if(!$field->isOwn || $field->type == 'hybrid') {
						$fds = $_dsm->getInfo($field->fromDS);
						if(!$fds) {
							echo '<span style="color: red;">The datasource was deleted</span>';
						} else {
							echo '<select size="1" name="fromds" DISABLED>';
							echo '<option value='.$field->fromDS.' SELECTED>'.$fds['name'].'</option>';
							echo '</select>';
						}
					} else {
						$ownds = $_dsm->getInfo($field->fromDS);
						echo '<a href="javascript: wxyopen(\'../../internal_ds.php?node='.$ownds['id'].'\', 650, 500);" style="cursor: pointer;">'.$ownds['name'].'</a>';
					}
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="document_isown" value="true" <?=$field->isOwn ? 'checked':''?> DISABLED> <?=H_DSH_FIELD_ONETOONE?></td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="isRequired" value="true" <?=$field->isRequired ? 'checked':''?>/><?=H_DSH_FIELD_ISREQUIRED?></td>
		</tr>
	</table>
	</div>
	<div class="ctrl"><input type="submit" value="<?=BTN_SAVE_FIELD?>" name="create"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>