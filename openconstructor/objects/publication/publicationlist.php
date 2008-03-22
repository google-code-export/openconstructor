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
 * $Id: publicationlist.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	
	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);
	$_dsm=new DSManager();
	$ds = $_dsm->getAll($obj->ds_type);
?>
<html>
<head>
<title><?=WC.' | '.EDIT_OBJECT?> | <?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script src="<?=WCHOME?>/lib/js/base.js"></script>
<script language="JavaScript" type="text/JavaScript">
function openObjectUses(objId) {
	openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
}
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.name.value.match(re)||!f.description.value.match(re)||<?=WCS::decide($obj, 'editobj') ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_OBJECT?></h3>
<form name="f" method="POST" action="i_publication.php">
	<input type="hidden" name="action" value="edit_publicationlist">
	<input type="hidden" name="obj_id" value="<?=$obj->obj_id?>">
	<fieldset style="padding:10"><legend><?=OBJECT?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_OBJ_NAME?>:</td>
			<td><input type="text" name="name" size="64" maxlength="64" value="<?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?>" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td valign="top" nowrap><?=PR_OBJ_DESCRIPTION?>:</td>
			<td><textarea cols="51" rows="5" name="description" onpropertychange="dsb()"><?=$obj->description?></textarea>
		</tr>
	</table>
	</fieldset><br>
<?
	include('../select_tpl._wc');
?>
	<fieldset style="padding:10" <?=WCS::decide($obj, 'editobj.ds') ? '' : 'disabled'?>><legend><?=OBJ_DATA?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap valign="top"><?=PR_DATASOURCES?>:</td>
			<td><select size="10" name="ds_id[]" multiple>
<?php
	$ds_ids=explode(',',$obj->ds_id);
	foreach($ds as $v)
		echo '<OPTION VALUE="'.$v['id'].'"'.(array_search($v['id'],$ds_ids)!==false?' SELECTED':'').'>'.
			$v['name'];
?>	
			</select></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=OBJ_PROPERTIES?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_HEADER?>:</td>
			<td><input type="text" name="header" value="<?=htmlspecialchars(@$obj->header, ENT_COMPAT, 'UTF-8')?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_DATE_FORMAT?>:</td>
			<td><input type="text" name="dateformat" value="<?=htmlspecialchars(@$obj->dateFormat, ENT_COMPAT, 'UTF-8')?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_PUBLICATIONS_PER_PAGE?>:</td>
			<td><input type="text" name="pagesize" value="<?=$obj->pageSize?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_PAGE_ID?>:</td>
			<td><input type="text" name="pid" value="<?=$obj->pid?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_PUBLICATION_URI?>:</td>
			<td><input type="text" name="srvuri" value="<?=$obj->srvuri?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_PUBLICATION_ID?>:</td>
			<td><input type="text" name="publicationid" value="<?=$obj->publicationid?>"></td>
		</tr>
		<tr>
			<td nowrap><input type=checkbox name="clause" onclick="f.from.disabled=f.to.disabled=!this.checked" <?=$obj->clause?'checked':''?>>&nbsp;<?=PR_FILTER_BY_HEADERS?>:</td>
			<td><?=PR_FILTER_FROM?>&nbsp;<input type="text" name="from" value="<?=$obj->from?>" size="3" maxlength=1 <?=$obj->clause?'':'DISABLED'?>>&nbsp;&nbsp;<?=PR_FILTER_TO?>&nbsp;<input type="text" name="to" value="<?=$obj->to?>" size="3" maxlength=1 <?=$obj->clause?'':'DISABLED'?>></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="reverseorder" value="true" <?=@$obj->reverseOrder?'checked':''?>> <?=PR_SHOW_IN_REVERSE_ORDER?></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="ohnemain" value="true" <?=$obj->ohneMain?'checked':''?>> <?=PR_HIDE_MAIN_PUBLICATION?></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="withcurrent" value="true" <?=@$obj->withCurrent?'checked':''?>> <?=PR_SHOW_CURRENT_PUBLICATION?></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" <?=@$obj->no404?'checked':''?>> <?=PR_NO_404?></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right">
	<input type="button" value="<?=BTN_MANAGE_OBJECT_USES?>" style="float: left;" onclick="openObjectUses(<?=$obj->obj_id?>);">
	<input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div>
</form>
<script>dsb();</script>
</body>
</html>