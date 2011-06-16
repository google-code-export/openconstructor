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
 * $Id: edit_hybrid.php,v 1.10 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = $dsm->load(@$_GET['ds_id']);
	assert($_ds != null);
	WCS::request($_ds, 'editds');
	$dss = $dsm->getAll('hybrid');
	//userfriendly names
	$uf['ds_name']=DS_NAME;
	$uf['ds_key']=DS_KEY;
	$uf['description']=DS_DESCRIPTION;
	//default values
	$ds_id=$_GET['ds_id'];
	$ds_name=$_ds->name;
	$description=$_ds->description;
	$record = $_ds->getRecord();
	
	$sources = array(); // Events section
	$db = WCDB::bo();
	$res = $db->query(
		'SELECT id, header'.
		' FROM dsphpsource'.
		' WHERE 1'
	);
	if(mysql_num_rows($res)>0)
		while($row = mysql_fetch_assoc($res))
			$sources[$row['id']] = $row['header'];
	
	//read values that have not been saved
	read_fail_header();
?>
<html>
<head>
<title><?=WC.' | '.EDIT_DS_HYBRID?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<link href="../objects/hybrid/local.css" type=text/css rel=stylesheet>
<script src="../common.js"></script>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
//var rek=new RegExp('^[a-zA-Z][a-zA-Z0-9]{0,5}$','g');
var rek=new RegExp('^[a-zA-Z][a-zA-Z0-9]{0,5}(\\.[a-zA-Z][a-zA-Z0-9]{0,5})*$','g');
function dsb(){
	if(!f.ds_name.value.match(re)||<?=WCS::decide($_ds, 'editds') ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
function addfield(){
	wxyopen('./hybrid/field/add.php?ds_id=<?=$_ds->ds_id?>',550,430);
}
function removefields(){
	if(mopen("../confirm.php?q=<?=urlencode(REMOVE_SELECTED_FIELDS_Q)?>&skin=<?=SKIN?>",350,170)) {
		f.attributes.action.value='./hybrid/i_hybrid.php';
		f.action.value='remove_field';
		f.submit();
	}
}
</script>
<style>
UL.fieldlist {list-style-type:none;margin-left:10px;}
UL.fieldlist LI {}
</style>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_DS_HYBRID?></h3>
<?php
	report_results(CREATE_DS_FAILED_W);
?>
<form name="f" method="POST" action="i_data.php">
	<input type="hidden" name="action" value="edit_dshybrid">
	<input type="hidden" name="ds_id" value="<?=$_GET['ds_id']?>">
	<input type="hidden" name="dshkey" value="<?=$_ds->key?>">
	<fieldset style="padding:10"><legend><?=DS_GENERAL_PROPS?></legend>
		<div class="property"<?=is_valid('ds_name')?>>
			<span><?=$uf['ds_key']?>:</span>
			<input disabled type="text" name="ds_key" value="<?=htmlspecialchars($_ds->key, ENT_COMPAT, 'UTF-8')?>" size="64" maxlength="128" onpropertychange="dsb()">
		</div>
		<div class="property"<?=is_valid('ds_name')?>>
			<span><?=$uf['ds_name']?>:</span>
			<input type="text" name="ds_name" value="<?=htmlspecialchars($ds_name, ENT_COMPAT, 'UTF-8')?>" size="64" maxlength="64" onpropertychange="dsb()">
		</div>
		<div class="property"<?=is_valid('description')?>>
			<span><?=$uf['description']?>:</span>
			<textarea cols="51" rows="5" name="description"><?=$description?></textarea>
		</div>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=DS_HYBRID_FIELDS?></legend>
		<table class="fieldlist" cellspacing="0">
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="id" disabled/></td>
			<td class="sys">id</td><td>ID</td>
		</tr>
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="header" disabled/></td>
			<td class="sys">header</td><td>Header</td>
		</tr>
	<?php
		foreach($record->fields as $f) {
			if($f->ds_id != @$lastDs)
				echo '<tr><td colspan="3" style="padding-top:10px">'.$dss[$f->ds_id]['name'].' :</td></tr>'; ?>
			<tr>
				<td class="f"><input type="checkbox" name="field[]" value="<?=$f->key?>" <?=$f->ds_id != $_ds->ds_id ? 'disabled' : ''?>></td>
				<td class="sys"><?=substr($f->key, 2)?></td>
				<td><a href="javascript:wxyopen('./hybrid/field/<?=$f->family?>.php?id=<?=$f->id?>',550,430)"><?=$f->header?></a></td>
			</tr>
			<?
			$lastDs = $f->ds_id;
		}
	?>
		</table>
		<input type="button" value="<?=BTN_ADD_FIELD?>" onclick="addfield()"> <input type="button" value="<?=BTN_REMOVE_FIELDS?>" onclick="removefields()">
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=DS_SEARCH?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><input type="checkbox" name="isindexable"<?=@$_ds->isIndexable?'checked':''?> onclick="document.getElementById('fIndexed').disabled = !this.checked"> <?=IS_INDEXABLE?></td>
		</tr>
	</table>
		<fieldset style="padding:10" id="fIndexed"><legend><?=H_INDEX_PROPS?></legend>
		<div class="property">
			<span><?=INDEXED_DOC_PATTERN?>:</span>
			<textarea cols="51" rows="5" name="indexedDoc"><?=htmlspecialchars($_ds->indexedDoc, ENT_COMPAT, 'UTF-8')?></textarea>
		</div>
		<div class="property">
			<span><?=INDEX_INTRO_FIELD?>:</span>
			<select size="1" name="docIntro">
				<option value="0">-
		<?php
			foreach($record->fields as $f)
				echo "<option value='$f->key' ".($f->key == $_ds->docIntro ? 'selected' : '').">".htmlspecialchars($f->header, ENT_COMPAT, 'UTF-8');
		?>
			</select>
		</div>
		</fieldset><br>
	</fieldset><br>
	<script>f.isindexable.onclick();</script>
	<fieldset style="padding:10"><legend><?=DS_PROPS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td>
				<?php
				if($_ds->editTpl > 0): ?>
					<a href="/tpl [id = <?=$_ds->editTpl?>]" onclick="wxyopen('../templates/edit.php?dstype=hybridbodyedit&id=<?=$_ds->editTpl?>', 660); return false;"><?=DS_DOC_EDIT_TPL?></a>
				<?php
				else :
					echo DS_DOC_EDIT_TPL;
				endif;?>:
			</td>
			<td><select name="editTpl" size="1">
					<option value="0" style="background:#eee;">- &nbsp; &nbsp; &nbsp;
					<?php
						require_once(LIBDIR.'/templates/wctemplates._wc');
						$tpls = new WCTemplates();
						$etpls = $tpls->get_all_tpls('hybridbodyedit');
						foreach($etpls as $tplId => $title):
					?>
					<option value="<?=$tplId?>" <?=$tplId == $_ds->editTpl ? 'selected' : ''?>><?=$title?>
					<?php
						endforeach;
					?>
			</select></td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="autoPublish" value="true"<?=@$_ds->autoPublish ? ' CHECKED':''?> <?=WCS::decide($_ds, 'publishdoc')?'':'DISABLED'?>> <?=DS_ALLOW_AUTOPUBLISHING?></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=H_DSH_EVENTS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=H_DSH_EVENTS_ON_CREATE?></td>
			<td>
				<select size="1" name="onDocCreate"><option value=0>-</option>
					<?php
						foreach($sources as $id => $name)
							echo '<option value="'.$id.'"'.($id == $_ds->listeners['onDocCreate'] ? ' SELECTED' : '').'>'.htmlspecialchars($name, ENT_COMPAT, 'UTF-8').'</option>';
					?>	
				</select>
			</td>
		</tr>
		<tr>
			<td><?=H_DSH_EVENTS_ON_UPDATE?></td>
			<td>
				<select size="1" name="onDocUpdate"><option value=0>-</option>
					<?php
						foreach($sources as $id => $name)
							echo '<option value="'.$id.'"'.($id == $_ds->listeners['onDocUpdate'] ? ' SELECTED' : '').'>'.htmlspecialchars($name, ENT_COMPAT, 'UTF-8').'</option>';
					?>	
				</select>
			</td>
		</tr>
		<tr>
			<td><?=H_DSH_EVENTS_ON_DELETE?></td>
			<td>
				<select size="1" name="onDocDelete"><option value=0>-</option>
					<?php
						foreach($sources as $id => $name)
							echo '<option value="'.$id.'"'.($id == $_ds->listeners['onDocDelete'] ? ' SELECTED' : '').'>'.htmlspecialchars($name, ENT_COMPAT, 'UTF-8').'</option>';
					?>	
				</select>
			</td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE_CHANGES_DS?>" name="create"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>