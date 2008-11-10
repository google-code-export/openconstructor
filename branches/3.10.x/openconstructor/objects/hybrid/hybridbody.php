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
 * $Id: hybridbody.php,v 1.14 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	require_once(LIBDIR.'/hybrid/fields/fieldfactory._wc');
	require_once(LIBDIR.'/dsmanager._wc');

	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);
	$_dsm=new DSManager();
	$ds = $_dsm->getAll($obj->ds_type);
	$fields = $obj->ds_id ? FieldFactory::getRelatedFields($obj->ds_id) : array();
?>
<html>
<head>
<title><?=WC.' | '.EDIT_OBJECT?> | <?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../<?=SKIN?>.css" type=text/css rel=stylesheet>
<link href="local.css" type=text/css rel=stylesheet>
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
function fieldClicked(id) {
	var ch = document.getElementById("ch.f" + id);
	var inp = document.getElementById("ep" + id);
	if(inp) {
		inp.disabled = !ch.checked;
		document.getElementById("trep" + id).style.display = ch.checked ? '' : 'none';
	}
}
function addCondition() {
	var sample = document.getElementById('sample');
	var cond = sample.cloneNode(true);
	sample.parentElement.appendChild(cond);
	cond.style.display = 'block';
	return cond;
}
function removeCondition(button) {
	var cond = button.parentElement.parentElement.parentElement.parentElement.parentElement;
	cond.removeNode(true);
}
function setCondition(cond, type, field, src, value, invert) {
	var inputs = cond.getElementsByTagName('INPUT');
	var selects = cond.getElementsByTagName('SELECT');
	inputs[0].value = field;
	inputs[0].onchange();
	selects[0].selectedIndex = type;
	selects[1].selectedIndex = src;
	inputs[1].checked = invert;
	inputs[1].onclick();
	inputs[3].value = value;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_OBJECT?></h3>
<form name="f" method="POST" action="i_hybrid.php">
	<input type="hidden" name="action" value="edit_hybridbody">
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
<?php
	include('../select_tpl._wc');
?>
	<fieldset style="padding:10" <?=WCS::decide($obj, 'editobj.ds') ? '' : 'disabled'?>><legend><?=OBJ_DATA?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_DATASOURCE?>:</td>
			<td><select size="1" name="ds_id" onchange="document.getElementById('objFields').disabled = (this.options[this.selectedIndex].value != '<?=$obj->ds_id?>')">
<?php
	foreach($ds as $v)
		echo '<OPTION VALUE="'.$v['id'].'"'.($v['id'] == $obj->ds_id ? ' SELECTED':'').'>'.
			$v['name'];
?>
			</select></td>
		</tr>
		<tr>
			<td colspan="2"><br><input type="checkbox" id="ch.onlySub" name="onlySub" value="true" <?=$obj->onlySub ? 'checked' : ''?>> <label for="ch.onlySub"><?=PR_REQUIRE_SUB_DS?></label></td>
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
			<td nowrap><?=PR_DYNAMIC_DS_ID?>:</td>
			<td><input type="text" name="dsIdKey" value="<?=$obj->dsIdKey?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_DOCUMENT_ID?>:</td>
			<td><input type="text" name="docId" value="<?=$obj->docId?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_DOCUMENT_ID_FIELD?>:</td>
			<td><select name="idField" size="1">
				<option value="0" style="background:#eee;"><?=H_SYS_ID_FIELD?>
			<?php
				for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
					$f = &$fields[$i];
					$upper = strpos($ds[$obj->ds_id]['path'], $ds[$f->ds_id]['path']) === 0;
					if($upper && (
						($f->family == 'primitive' && ($f->type == 'integer' || $f->type == 'string') && $f->length <= 24)
						|| ($f->family == 'enum' && !$f->isArray)
					))
						echo "<option value='$f->id' ".($f->id == $obj->idField ? 'selected' : '').">$f->header</option>";
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td nowrap><?=PR_HL_URI?>:</td>
			<td><input type="text" name="browseUri" value="<?=$obj->browseUri?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_NODE_ID?>:</td>
			<td><input type="text" name="nodeId" value="<?=$obj->nodeId?>"></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="_pma" value="true" <?=@$obj->_pma ? 'checked' : ''?>> <?=PR_GROUP_PRIMITIVES_AS_ARRAYS?></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" <?=@$obj->no404?'checked':''?>> <?=PR_NO_404?></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset id="objFields" style="padding:10"><legend><?=PR_FETCH_FIELDS?></legend>
		<table class="fieldlist" cellspacing="0">
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="id" disabled checked/></td>
			<td class="sys">id</td><td colspan="2">ID</td>
		</tr>
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="header" disabled checked/></td>
			<td class="sys">header</td><td colspan="2" width="100%">Header</td>
		</tr>
	<?php
		$docFields = array_flip(array_map(create_function('&$v', 'return $v[\'id\'];'), $obj->docFields));
		$first = true;
		for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
			$f = &$fields[$i];
//			if($f->family == 'tree' && $f->isArray) continue;
			if($f->ds_id != @$lastDs) {
				echo '<tr '.(!$first ? 'class="f"':'').'><td colspan="4" style="padding-top:10px">'.$ds[$f->ds_id]['name'].' :</td></tr>';
				$first = true;
			}
			?>
			<tr <?=!$first ? 'class="f"':''?>>
				<td class="f"><input <?=isset($docFields[$f->id])? 'checked':''?> type="checkbox" name="field[<?=$f->id?>][id]" value="<?=$f->id?>" onclick="fieldClicked(<?=$f->id?>)" id="ch.f<?=$f->id?>"></td>
				<td class="sys"><?=substr($f->key, 2)?></td>
				<td colspan="2"><a href="javascript:wxyopen('../../data/hybrid/field/<?=$f->family?>.php?id=<?=$f->id?>',550,430)"><?=$f->header?></a></td>
			</tr>
	<?php
			if($f->family == 'rating'):
	?>
			<tr class="extraprop" id="trep<?=$f->id?>">
				<td colspan="2">&nbsp;</td>
				<td><?=H_RATING_PERIOD?>:</td>
				<td><input type="text" size="40" disabled name="field[<?=$f->id?>][range]" value="<?=htmlspecialchars(@$obj->docFields[$docFields[$f->id]]['range'])?>" id="ep<?=$f->id?>"></td>
			</tr>
	<?php
			endif;
			echo "<script>fieldClicked({$f->id});</script>";
			$lastDs = $f->ds_id;
			$first = false;
		}
	?>
		</table>
	</fieldset><br>
	<fieldset style="padding:10" id="objFilters"><legend><?=OBJ_FILTERS?></legend>
		<div>
		<fieldset id="sample" style="margin:20px 0 30px;display:none;">
		<legend style="font-weight:bold;"></legend>
		<table style="margin:5 0" cellspacing="3" width="100%">
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_COND_WHERE?></td>
				<td colspan="2" width="100%">
					<input name="filter[]" type="text" style="margin-top:5px;" onchange="this.parentElement.parentElement.parentElement.parentElement.parentElement.getElementsByTagName('LEGEND')[0].innerHTML = this.value;">
				</td>
				<td rowspan="3" valign="top"><img src="../../i/default/e/h/remove.gif" style="cursor:pointer;margin-right:10px;" onclick="removeCondition(this)" alt="<?=BTN_REMOVE_CONDITION?>"></td>
			</tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_COND?></td>
				<td>
					<select size="1" name="type[]">
						<option value="<?=COND_EQ?>">Equal
						<option value="<?=COND_BTW?>">Between
						<option value="<?=COND_GT?>">Greater than
						<option value="<?=COND_LT?>">Less than
						<option value="<?=COND_GTEQ?>">Greater than or equal
						<option value="<?=COND_LTEQ?>">Less than or equal
						<option value="<?=COND_CONTAINS?>">Contains
						<option value="<?=COND_LIKE?>">Like
					</select>
					<input type="checkbox" onclick="this.nextSibling.value = this.checked ? 'true' : 'false'"><input type="hidden" name="invert[]" value="false"> <?=H_INVERT_COND?>
				</td>
			</tr>
			<tr><td colspan="2" style="font-size:2px;">&nbsp;</td></tr>
			<tr>
				<td style="padding-left:5px; padding-right:10px; font-size:115%;"><?=H_COND_VALUE?></td>
				<td>
					<select size="1" name="src[]">
						<option value="<?=VALUE_CTX?>">Context
						<option value="<?=VALUE_PLAIN?>">Plain
					</select>
					<input type="text" name="value[]">
				</td>
			</tr>
		</table>
		</fieldset>
		</div>
		<input type="button" value="<?=BTN_ADD_CONDITION?>" onclick="addCondition()">
	<script>
	<?php
		foreach((array) $obj->docFilter as $cond) {
			$type = abs($cond[0]) % 10;
			$src = intval(abs($cond[0]) / 10) - 1;
			$cond[1] = addslashes($cond[1]);
			$invert = $cond[0] < 0 ? 'true' : 'false';
			$name = escapeTags(isset($fieldsById[$cond[2]]) ? substr($fieldsById[$cond[2]]->key, 2) : $cond[2]);
			echo "setCondition(addCondition(), $type, '$name', $src, '{$cond[1]}', $invert);\n";
		}
	?>
	</script>
	</fieldset><br>
	<div align="right">
	<input type="button" value="<?=BTN_MANAGE_OBJECT_USES?>" style="float: left;" onclick="openObjectUses(<?=$obj->obj_id?>);">
	<input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div>
</form>
<script>dsb();</script>
</body>
</html>