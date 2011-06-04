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
 * $Id: hybridbar.php,v 1.17 2007/04/20 08:04:16 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');

	$obj = ObjManager::load(@$_GET['id']);
	assert($obj != null);

	require_once(LIBDIR.'/hybrid/fields/fieldfactory._wc');
	require_once(LIBDIR.'/hybrid/fields/fieldutils._wc');
	require_once(LIBDIR.'/dsmanager._wc');

	$_dsm=new DSManager();
	$ds = $_dsm->getAll($obj->ds_type);
	$fields = $obj->ds_id ? FieldFactory::getRelatedFields($obj->ds_id) : array();
	$fieldsById = array();
	for($i = 0, $l = sizeof($fields); $i < $l; $i++)
		$fieldsById[$fields[$i]->id] = &$fields[$i];
?>
<html>
<head>
<title><?=WC.' | '.EDIT_OBJECT?> | <?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../<?=SKIN?>.css" type=text/css rel=stylesheet>
<link href="local.css" type=text/css rel=stylesheet>
<script src="<?=WCHOME?>/lib/js/base.js"></script>
<script src="<?=WCHOME?>/lib/js/controllers.js"></script>
<script src="<?=WCHOME?>/lib/js/widgets.js"></script>
<script language="JavaScript" type="text/JavaScript">
	var	host='<?=$_host?>', skin='<?=SKIN?>';

	function init() {
		new ArrayWidget(
			new ArrayController(document.getElementById('doc_ids')),
			document.getElementById('tab.doc_ids'),['<?=H_CREATE_DOC?>', '<?=H_ADD_DOC?>', '<?=H_REMOVE_DOC?>']
		);
	}

function openObjectUses(objId) {
	openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
}
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.name.value.match(re)||!f.description.value.match(re)||<?=WCS::decide($obj, 'editobj') ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
function addField() {
	var l, r, o, sel;
	l = document.getElementById('ord.set');
	r = document.getElementById('ord.available');
	if(r.selectedIndex < 0) return;
	o = document.createElement('OPTION');
	o.value = r.options[r.selectedIndex].value;
	o.head = r.options[r.selectedIndex].innerHTML;
	o.innerHTML = '+ ' + o.head;
	l.appendChild(o);
	sel = r.selectedIndex;
	o.style.background = r.options[sel].style.background;
	r.options[sel].removeNode(true);
	while(sel >= 0 && !r.options[sel])
		sel--;
	if(sel >= 0)
		r.selectedIndex = sel;
	o.selected = true;
	orderFieldClicked();
}

function removeField() {
	var l, r, o, sel;
	l = document.getElementById('ord.set');
	r = document.getElementById('ord.available');
	if(l.selectedIndex < 0) return;
	o = document.createElement('OPTION');
	o.value = Math.abs(l.options[l.selectedIndex].value);
	o.innerHTML = l.options[l.selectedIndex].head;
	r.appendChild(o);
	sel = l.selectedIndex;
	o.style.background = l.options[sel].style.background;
	l.options[sel].removeNode(true);
	while(sel >= 0 && !l.options[sel])
		sel--;
	if(sel >= 0)
		l.selectedIndex = sel;
	o.selected = true;
	orderFieldClicked();
}
function moveField(step) {
	var l, r, to;
	l = document.getElementById('ord.set');
	r = document.getElementById('ord.available');
	to = l.selectedIndex + step;
	if(l.selectedIndex < 0 || to < 0 || !l.options[to]) return;
	l.options[l.selectedIndex].swapNode(l.options[to]);
}
function swapFieldDir() {
	var l, o;
	l = document.getElementById('ord.set');
	if(l.selectedIndex < 0) return;
	o = l.options[l.selectedIndex];
	o.value = o.value.substr(0, 1) == '-' ? o.value.substr(1) : '-' + o.value;
	o.innerHTML = (o.value.substr(0, 1) == '-' ? '- ' : '+ ') + o.head;
}
function prepareDocOrder() {
	var l, order = new Array();
	l = document.getElementById('ord.set');
	for(var i = 0; i < l.options.length; i++)
		order[i] = l.options[i].value;
	f.docOrder.value = order.join(',');
}
function dsIdChanged() {
	var st = (f.ds_id.options[f.ds_id.selectedIndex].value != '<?=$obj->ds_id?>');
	document.getElementById('objFields').disabled = st;
	document.getElementById('objDocOrder').disabled = st;
	document.getElementById('objFilters').disabled = st;
	f.docOrder.disabled = st;
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
function fieldClicked(id) {
	var ch = document.getElementById("ch.f" + id);
	var inp = document.getElementById("ep" + id);
	if(inp) {
		inp.disabled = !ch.checked;
		document.getElementById("trep" + id).style.display = ch.checked ? '' : 'none';
	}
}
function orderFieldClicked() {
	var sel = document.getElementById('ord.set');
	var tr = null;
	if(sel.oldTr)
		sel.oldTr.style.display = 'none';
	if(sel.selectedIndex != -1) {
		var opt = sel.options[sel.selectedIndex];
		tr = document.getElementById("trr" + Math.abs(opt.value));
		if(tr)
			tr.style.display = '';
	}
	sel.oldTr = tr;
}
</script>
<style>
	DIV#array TABLE {width:100%;border:solid 1px #ccc;}
	DIV#array TR.r1, DIV.docs TR.r1 {background:#f2f2f2;}
	DIV#array TR.r0, DIV.docs TR.r0 {background:#fff;}
	DIV#array TD {padding:4px 6px 5px;border-bottom:solid 1px #f8f8f8;}
	DIV#array TD.input {width:20px;}
	DIV.tabbed DIV {
		padding:5px 0;
	}
	DIV.tabbed DIV A {
		margin-right:5px;
	}
	A IMG {
		border:none;
	}
</style>
</head>
<body style="border-style:groove;padding:0 20 20" onload="init();">
<br>
<h3><?=EDIT_OBJECT?></h3>
<form name="f" method="POST" action="i_hybrid.php" onsubmit="prepareDocOrder();">
	<input type="hidden" name="action" value="edit_hybridhl">
	<input type="hidden" name="obj_id" value="<?=$obj->obj_id?>">
	<input type="hidden" name="docOrder" value="">
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
	$tpl_types = array($obj->obj_type, 'hybridhl');
	include('../select_tpl._wc');
?>
	<fieldset style="padding:10" <?=WCS::decide($obj, 'editobj.ds') ? '' : 'disabled'?>><legend><?=OBJ_DATA?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_DATASOURCE?>:</td>
			<td><select size="1" name="ds_id" onchange="dsIdChanged()">
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
	<fieldset style="padding:10"><legend><?=OBJ_DS_DOCS?></legend>
		<div id="tabs.array" class="tabs"></div>
		<div id="array">
			<div style="width:100%;">
				<div id="tab.doc_ids" class="tabbed">
					<select id="doc_ids" name="doc_ids[]" multiple size="1" style="display:none;" isown="0" doctype="hybrid" fromds="<?=$obj->ds_id?>" hybrid="-1" fieldid="-1">
					<?php
						if($obj->ids) {
							$docs = FieldUtils::getDocumentHeaders('hybrid', $obj->ds_id, explode(',', $obj->ids));
							foreach($docs as $id => $header)
								echo '<option value="'.$id.'">'.$header;
						}
					?>
					</select>
				</div>
			</div>
		</div>
	</fieldset><br>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=OBJ_PROPERTIES?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_HEADER?>:</td>
			<td><input type="text" name="header" value="<?=htmlspecialchars(@$obj->header, ENT_COMPAT, 'UTF-8')?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_DOCUMENTS_LIST_OFFSET?>:</td>
			<td><input type="text" name="listOffset" value="<?=$obj->listOffset?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_DOCUMENTS_LIST_SIZE?>:</td>
			<td><input type="text" name="listSize" value="<?=$obj->listSize?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_DYNAMIC_DS_ID?>:</td>
			<td><input type="text" name="dsIdKey" value="<?=$obj->dsIdKey?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_NODE_ID?>:</td>
			<td><input type="text" name="nodeId" value="<?=$obj->nodeId?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_NODE_ID_TYPE?>:</td>
			<td><select name="nodeType">
				<option value="<?=NID_PLAIN?>"><?=H_NID_PLAIN?>
				<option value="<?=NID_OR?>" <?=$obj->nodeType == NID_OR ? 'selected' : ''?>><?=H_NID_OR?>
				<option value="<?=NID_AND?>" <?=$obj->nodeType == NID_AND ? 'selected' : ''?>><?=H_NID_AND?>
			</select></td>
		</tr>
		<tr>
			<td nowrap><?=PR_DOCUMENT_URI?>:</td>
			<td><input type="text" name="srvUri" value="<?=$obj->srvUri?>"></td>
		</tr>
		<tr>
			<td nowrap valign="top"><?=PR_DOCUMENT_ID?>:</td>
			<td><textarea name="docId" cols="30" rows="4" wrap="off"><?=$obj->docId?></textarea></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="_pma" value="true" <?=@$obj->_pma ? 'checked' : ''?>> <?=PR_GROUP_PRIMITIVES_AS_ARRAYS?></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" <?=@$obj->no404?'checked':''?>> <?=PR_NO_404?></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="sortByRand" value="true" <?=@$obj->sortByRand ? 'checked' : '' ?>> <?=PR_SORT_BY_RAND?></td>
		</tr>
		<tr>
			<td colspan="2" width="100%">
			<fieldset style="padding:10"><legend><?=OBJ_SEARCH_PROPS?></legend>
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td nowrap><?=PR_KEYWORD_KEY?>:</td>
					<td><input type="text" name="keywordKey" value="<?=$obj->keywordKey?>"></td>
				</tr>
				<tr>
					<td nowrap><?=PR_NO_RESULTS_TPL?>:</td>
					<td><select size="1" name="noResTpl"><option value="0">-
					<?php
						$tmp = $tpls->get_all_tpls('searchdss');
						foreach($tmp as $tpl_id => $name)
							echo '<OPTION VALUE="'.$tpl_id.'"'.($tpl_id == $obj->noResTpl?' SELECTED':'').'>'.$name;
					?></select>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="sortByRank" <?=$obj->sortByRank ? 'checked' : ''?> value="true"> <?=PR_ORDERBY_RANK?></td>
				</tr>
			</table>
			</fieldset><br>
			</td>
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
			if($f->family == 'tree' && $f->isArray) continue;
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
			elseif($f->family == 'array' || $f->family == 'document'):
				if(!isset($hlid)) {
					$objm = new ObjManager();
					$objm->pageSize = 50;
					$hlid = $objm->get_objects('hybrid', 'hybridbar', 1, '', -1);
				}
	?>
			<tr class="extraprop" id="trep<?=$f->id?>">
				<td colspan="2">&nbsp;</td>
				<td><?=H_NESTED_LOADER?>:</td>
				<td><select size="1" disabled name="field[<?=$f->id?>][fetcher]" id="ep<?=$f->id?>">
					<option value="0" style="background: #eee;">- &nbsp; &nbsp;</option>
					<?php foreach($hlid as $k => $v):?>
						<option value="<?=$k?>"<?=@$obj->docFields[$docFields[$f->id]]['fetcher'] == $k ? " selected":""?>><?=$v['name']?></option>
					<?php endforeach; ?>
				</select></td>
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
	<fieldset style="padding:10" id="objDocOrder"><legend><?=OBJ_DOC_ORDER?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td width="50%"><?=H_ORDER_LIST_BY?>:<br><select size="10" id="ord.set" style="margin-top:5px;" onclick="orderFieldClicked()">
			<?php
				$sysf = array(
//					'id' => 'ID',
					'header' => 'Header'
				);
				$docOrder = array_flip(array_map(create_function('&$v', 'return $v[\'id\'];'), $obj->docOrder));
				$fs = array();
				for($i = 0, $l = sizeof($fields); $i < $l; $i++)
					if(isset($docOrder[$fields[$i]->id]) || isset($docOrder['-'.$fields[$i]->id]))
						$fs[$fields[$i]->id] = &$fields[$i];
				foreach($docOrder as $id => $j)
					if(is_numeric($id)) {
						$f = &$fs[abs($id)];
						if($id > 0)
							echo "<option value='$f->id' head='".htmlspecialchars($f->header, ENT_COMPAT, 'UTF-8')."'>+ $f->header";
						else if($id < 0)
							echo "<option value='-$f->id' head='".htmlspecialchars($f->header, ENT_COMPAT, 'UTF-8')."'>- $f->header";
					} elseif(isset($sysf[$id]) || isset($sysf[substr($id, 1)])) {
						$desc = substr($id, 0, 1) == '-';
						$fid = $desc ? substr($id, 1) : $id;
						echo sprintf(
							'<option value="%1$s%2$s" head="%3$s" style="background: #eee;">%4$s %3$s'
							, $desc ? '-' : '', $fid, htmlspecialchars($sysf[$fid], ENT_COMPAT, 'UTF-8'), $desc ? '-' : '+'
						);
					}
			?></select>
			</td>
			<td>
				<input type="button" value="<?=BTN_ADD_FIELD?>" onclick="addField();" style="width:85px;margin:2px 0px;">
				<input type="button" value="<?=BTN_REMOVE_FIELD?>" onclick="removeField();" style="width:85px;margin:2px 0px;">
				<input type="button" value="<?=BTN_MOVEUP_FIELD?>" onclick="moveField(-1);" style="width:85px;margin:2px 0px;">
				<input type="button" value="<?=BTN_MOVEDOWN_FIELD?>" onclick="moveField(1);" style="width:85px;margin:2px 0px;">
				<input type="button" value="<?=BTN_SWITCH_ORDERING?>" onclick="swapFieldDir();" style="width:85px;margin:2px 0px;">
			</td>
			<td width="50%"><?=H_AVAILABLE_DSH_FIELDS?>:<br><select size="10" id="ord.available" style="margin-top:5px;">
			<?php
				foreach($sysf as $id => $header)
					if(!isset($docOrder[$id]) && !isset($docOrder['-'.$id]))
						echo "<option value='$id' style='background: #eee;'>$header";
				for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
					$f = &$fields[$i];
					if(
						($f->family == 'primitive' && $f->type != 'text' && $f->type != 'html')
						|| $f->family == 'enum'
						|| $f->family == 'datasource'
						|| $f->family == 'rating'
						|| $f->family == 'document'
						|| ($f->family == 'tree' && !$f->isArray)
						)
						if(!isset($docOrder[$f->id]) && !isset($docOrder['-'.$f->id]))
							echo "<option value='$f->id'>$f->header";
					//echo '<li><input '.(isset($docFields[$f->id])? 'checked':'').' type="checkbox" name="field[]" value="'.$f->id.'"> <a href="javascript:wxyopen(\'../../data/hybrid/field/'.$f->family.'.php?id='.$f->id.'\',550,430)" title="'.$f->key.'">'.$f->header.'</a></li>';
				}
			?></select>

			</td>
		</tr>
		<?php
			for($i = 0, $l = sizeof($fields); $i < $l; $i++) {
				$f = &$fields[$i];
				if($f->family == 'rating') {
					$range = '';
					if(isset($docOrder[$f->id]))
						$range = @$obj->docOrder[$docOrder[$f->id]]['range'];
					elseif(isset($docOrder['-'.$f->id]))
						$range = @$obj->docOrder[$docOrder['-'.$f->id]]['range'];?>
				<tr id="trr<?=$f->id?>" style="display: none;">
					<td colspan="3">
						<?=H_RATING_PERIOD?>: <input type='text' name='rRange[<?=$f->id?>]' id='rr<?=$f->id?>' value='<?=$range?>' style="vertical-align: middle" size="40">
					</td>
				</tr>
				<?php
				}
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