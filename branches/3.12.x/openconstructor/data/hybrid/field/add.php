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
 * $Id: add.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	require_once(LIBDIR.'/tree/sqltreereader._wc');
	require_once(LIBDIR.'/enum/wcenumfactory._wc');
	
	$_dsm=new DSManager();
	$ds = $_dsm->load(@$_GET['ds_id']);
?>
<html>
<head>
<title><?=H_ADD_FIELD?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script src="../../../common.js"></script>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
var rek=new RegExp('^[a-zA-Z][a-zA-Z0-9_]{0,15}$','g');
function dsb(){
	if(!f.key.value.match(rek)||!f.header.value.match(re)||<?=WCS::decide($ds, 'editds') ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
var curclass = null;
function fcchanged() {
	if(curclass){
		curclass.style.display = "none";
		curclass.disabled = true;
	}
	curclass = document.all("t."+f.fieldclass.options(f.fieldclass.selectedIndex).value);
	if(!curclass)
		return;
	curclass.disabled = false;
	curclass.style.display = "block";
}
var curdoctype = null;
function doctypechanged() {
	if(curdoctype)
		curdoctype.style.display = "none";
	curdoctype = f.all("document_from_"+f.document_type.options(f.document_type.selectedIndex).value);
	if(!curdoctype)
		curdoctype = f.all("document_from_none");
	curdoctype.style.display = "";
}
var curarrtype = null;
function arrtypechanged() {
	if(curarrtype)
		curarrtype.style.display = "none";
	curarrtype = f.all("array_from_"+f.array_type.options(f.array_type.selectedIndex).value);
	if(!curarrtype)
		curarrtype = f.all("array_from_none");
	curarrtype.style.display = "";
}
var curdstype = null;
function dstypechanged() {
	if(curdstype)
		curdstype.style.display = "none";
	curdstype = f.all("datasource_from_"+f.datasource_type.options(f.datasource_type.selectedIndex).value);
	if(!curdstype)
		curdstype = f.all("datasource_from_none");
	curdstype.style.display = "";
}
function prepare() {
	fc = f.fieldclass.options(f.fieldclass.selectedIndex).value;
	if(f.all(fc + "_type"))
		f.type.value = f.all(fc + "_type").options(f.all(fc+"_type").selectedIndex).value;
	if(fc == 'document' || fc == 'array' || fc == 'datasource') {
		f.fromDS.value = f.all(fc+"_from_"+f.type.value).options(f.all(fc+"_from_"+f.type.value).selectedIndex).value;
		if(fc != 'datasource')
			f.isOwn.value = f.all(fc + "_isown").checked ? 'true' : 'false';
	}
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
<h3 class="new"><?=H_ADD_FIELD?></h3>
<form name="f" method="POST" action="../i_hybrid.php" style="padding:0;margin:0;" onsubmit="return prepare();">
	<div style="padding:20px;background:#fff;">
	<input type="hidden" name="action" value="add_field">
	<input type="hidden" name="ds_id" value="<?=@$_GET['ds_id']?>">
	<input type="hidden" name="type">
	<input type="hidden" name="fromDS">
	<input type="hidden" name="isOwn">
	<table border="0">
		<tr>
			<td>ID:</td>
			<td><input type="text" name="key" size="16" maxlength="16" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td><?=H_DSH_FIELD_NAME?>:</td>
			<td><input type="text" name="header" size="64" maxlength="255" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td><?=H_DSH_FIELD_TYPE?>:</td>
			<td><select size=1 name="fieldclass" onchange="fcchanged()">
				<option value="primitive"><?=H_DSH_PRIMITIVE_FIELD?>
				<option value="file"><?=H_DSH_FILE_FIELD?>
				<option value="document"><?=H_DSH_DOCUMENT_FIELD?>
				<option value="array"><?=H_DSH_ARRAY_FIELD?>
				<option value="datasource"><?=H_DSH_DATASOURCE_FIELD?>
				<option value="tree"><?=H_DSH_WCTREE_FIELD?>
				<option value="enum"><?=H_DSH_WCENUM_FIELD?>
				<option value="rating"><?=H_DSH_RATING_FIELD?>
			</select></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			<fieldset id="t.primitive" disabled>
				<legend><?=H_DSH_FIELD_PROPS?></legend>
				<table>
				<tr>
					<td><?=H_DSH_PRIMITIVE_FIELD?>:</td>
					<td><select size="1" name="primitive_type">
						<option value="boolean">Boolean<option value="integer">Integer<option value="float">Float<option value="string">String<option value="date">Date<option value="time">Time<option value="datetime">Datetime<option value="text">Text<option value="html">HTML
					</select></td>
				</tr>
				<tr>
					<td><?=H_DSH_FIELD_DEFAULT?>:</td>
					<td><input type="text" name="primitive_default" size="10"></td>
				</tr>
				</table>
			</fieldset>
			<fieldset id="t.file" disabled>
				<legend><?=H_DSH_FIELD_PROPS?></legend>
				<div class="property">
					<span><?=H_DSH_FIELD_FILETYPES?>:</span>
					<div class="tip"><?=DS_TT_ALLOWED_FILETYPES?></div>
					<input type="text" style="font-family:monospace;" name="file_types" size="64">
				</div>
			</fieldset>
			<fieldset id="t.document" disabled>
				<legend><?=H_DSH_FIELD_PROPS?></legend>
				<table>
				<tr>
					<td><?=H_DSH_FIELD_DOCTYPE?>:</td>
					<td><select size="1" name="document_type" onchange="doctypechanged()"><?php
							foreach($_dsm->types as $k => $v)
								if($k != 'phpsource' && $k != 'htmltext' && $k != 'rating')
									echo '<option value="'.$k.'">'.$v;
					?></select></td>
				</tr>
				<tr>
					<td><?=H_DSH_FIELD_CHOOSEFROM?>:</td>
					<td>
						<select size="1" name="document_from_none" style="display:none"></select>
						<?php
							$map = $_dsm->getTree();
							$hds = $_dsm->getAll('hybrid');
							foreach($_dsm->types as $k=>$v){
								if($k == 'htmltext' || $k == 'phpsource' || $k == 'rating' || !@$map[$k][$v]) continue;
								echo '<select size="1" name="document_from_'.$k.'" style="display:none">';
								if($k != 'hybrid')
									foreach($map[$k][$v] as $id=>$name)
										echo '<option value="'.$id.'">'.$name;
								else
									foreach($hds as $id=>$v)
										echo '<option value="'.$id.'">'.$v['name'];
								echo '</select>';
							}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="document_isown" value="true"> <?=H_DSH_FIELD_ONETOONE?></td>
				</tr>
				</table>
			</fieldset>
			<fieldset id="t.array" disabled>
				<legend><?=H_DSH_FIELD_PROPS?></legend>
				<table>
				<tr>
					<td><?=H_DSH_FIELD_DOCTYPE?>:</td>
					<td><select size="1" name="array_type" onchange="arrtypechanged()"><?php
							foreach($_dsm->types as $k=>$v)
								if($k != 'phpsource' && $k != 'htmltext' && $k != 'rating')
									echo '<option value="'.$k.'">'.$v;
					?></select></td>
				</tr>
				<tr>
					<td><?=H_DSH_FIELD_CHOOSEFROM?>:</td>
					<td>
						<select size="1" name="array_from_none" style="display:none"></select>
						<?php
							foreach($_dsm->types as $k=>$v){
								if($k == 'htmltext' || $k == 'phpsource' || $k == 'rating' || !@$map[$k][$v]) continue;
								echo '<select size="1" name="array_from_'.$k.'" style="display:none">';
								if($k != 'hybrid')
									foreach($map[$k][$v] as $id=>$name)
										echo '<option value="'.$id.'">'.$name;
								else
									foreach($hds as $id=>$v)
										echo '<option value="'.$id.'">'.$v['name'];
								echo '</select>';
							}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="array_isown" value="true"> <?=H_DSH_FIELD_ONETOMANY?></td>
				</tr>
				</table>
			</fieldset>
			<fieldset id="t.datasource" disabled>
				<legend><?=H_DSH_FIELD_PROPS?></legend>
				<table>
				<tr>
					<td><?=H_DSH_FIELD_DSTYPE?>:</td>
					<td><select size="1" name="datasource_type" onchange="dstypechanged()"><?php
							foreach($_dsm->types as $k=>$v)
								if($k != 'hybrid' && $k != 'phpsource' && $k != 'rating')
									echo '<option value="'.$k.'">'.$v;
					?></select></td>
				</tr>
				<tr>
					<td><?=H_DSH_FIELD_CHOOSEFROM?>:</td>
					<td>
						<select size="1" name="datasource_from_none" style="display:none"></select>
						<?php
							foreach($_dsm->types as $k=>$v){
								if($k == 'hybrid' || $k == 'phpsource' || $k == 'rating' || !@$map[$k][$v]) continue;
								echo '<select size="1" name="datasource_from_'.$k.'" style="display:none">';
								foreach($map[$k][$v] as $id=>$name)
									echo '<option value="'.$id.'">'.$name;
								echo '</select>';
							}
						?>
					</td>
				</tr>
				</table>
			</fieldset>
			<fieldset id="t.tree" disabled>
				<legend><?=H_DSH_FIELD_PROPS?></legend>
				<table>
				<tr>
					<td><?=H_DSH_WCTREE_FIELD?>:</td>
					<td><select size="1" name="tree_type">
					<?php
						$reader = new SqlTreeReader();
						$types = $reader->getChildren(1);
						foreach($types as $node)
							echo "<option value='$node->id'>$node->header ($node->key)";
					?>
					</select></td>
				</tr>
				<tr>
					<td colspan=2><input type="checkbox" name="tree_is_array" value="true"><?=H_DSH_FIELD_IS_ARRAY?></td>
				</tr>
				</table>
			</fieldset>
			<fieldset id="t.enum" disabled>
				<legend><?=H_DSH_FIELD_PROPS?></legend>
				<table>
				<tr>
					<td><?=H_DSH_WCENUM?>:</td>
					<td><select size="1" name="enum_type">
					<?php
						$ef = &WCEnumFactory::getInstance();
						$types = $ef->getAllEnums();
						foreach($types as $id => $header)
							echo "<option value='$id'>$header";
					?>
					</select>
					<img src="../../../i/default/e/enums.gif" align="absmiddle" onclick="wxyopen('../enum/index.php', 650, 500)" style="cursor: pointer;">
					</td>
				</tr>
				<tr>
					<td colspan=2><input type="checkbox" name="enum_is_array" value="true"><?=H_DSH_FIELD_IS_ARRAY?></td>
				</tr>
				</table>
			</fieldset>
			<fieldset id="t.rating" disabled>
				<legend><?=H_DSH_FIELD_PROPS?></legend>
				<table>
				<tr>
					<td><?=H_DSH_FIELD_CHOOSEFROM?>:</td>
					<td><select size="1" name="rating_type">
					<?php
						if(isset($map['rating'])) {
							$db = &WCDB::bo();
							$res = $db->query('SELECT fromds FROM dshfields WHERE family="rating"');
							while($r = mysql_fetch_row($res))
								$usedRatings[$r[0]] = true;
							mysql_free_result($res);
							$ratings = (array) $map['rating'][key($map['rating'])];
							foreach($ratings as $id => $header)
								if(!isset($usedRatings[$id]))
									echo "<option value='$id'>$header";
						}
					?>
					</select>
					</td>
				</tr>
				</table>
			</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="isRequired" value="true"/><?=H_DSH_FIELD_ISREQUIRED?></td>
		</tr>
	</table>
	</div>
	<div class="ctrl"><input type="submit" value="<?=BTN_ADD_FIELD?>" name="create"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>doctypechanged();arrtypechanged();dstypechanged();fcchanged();dsb();</script>
</body>
</html>