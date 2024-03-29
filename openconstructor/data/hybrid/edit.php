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
 * $Id: edit.php,v 1.23 2007/04/04 09:44:28 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	assert(isset($_GET['ds_id']) && isset($_GET['id']));

	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$ds = &$dsm->load($_GET['ds_id']);
	if(@$ds->editTpl > 0) {
		require_once(LIBDIR.'/smarty/wcsmarty._wc');
		require_once(LIBDIR.'/wcobject._wc');
		require_once(LIBDIR.'/context._wc');
		require_once(LIBDIR.'/hybrid/view/hybridagent._wc');
		require_once(LIBDIR.'/hybrid/view/hybridbodyedit._wc');
		$ctx = &Context::getInstance();
		$smarty = & new WCSmarty();
//		$smarty->compile_check = true;
		$obj = & new HybridBodyEdit();
		$obj->ctx = &Context::getInstance();
		$obj->ctx->_smarty = &$smarty;
		$obj->docId = 'id';
		$obj->dsIdKey = 'ds_id';
		$obj->block = 'POST';
		$obj->tpl = (int) $ds->editTpl;
		$obj->onPageLoad();
		$obj->docFields = array_keys($obj->agent->getFields());
		foreach($obj->docFields as $k => $v)
			$obj->docFields[$k] = array('id' => $v);
		$obj->exec($smarty);
		die();
	}
	require_once($_SERVER['DOCUMENT_ROOT'].WCHOME.'/include/toolbar._wc');
	require_once(LIBDIR.'/enum/wcenumfactory._wc');
	if($_GET['id']!='new') {
		$doc=$ds->getDocument($_GET['id']);
		assert($doc->id > 0);
	} else {
		$doc = $ds->getEmptyDocument();
		$preset = array_keys($_GET);
		for($i = 0; $i < sizeof($preset); $i++)
			if(array_key_exists($preset[$i], $doc->fields))
				$doc->fields[$preset[$i]] = $_GET[$preset[$i]];
			else if($preset[$i] == 'header')
				$doc->header = @$_GET['header'];
	}
	$doc->fetchValues();
	$rec = $ds->getRecord();
	$primitive = $html = $document = $array = array();
	foreach($rec->struct['primitive'] as $type=>$j)
		if($type != 'html')
			foreach($j as $key)
				$primitive[$key] = $type;
		else
			foreach($j as $key)
				$html[$key] = $type;
	foreach($rec->struct['document'] as $type=>$j)
		foreach($j as $key)
			if($doc->id || !$rec->fields[$key]->isOwn)
				$document[$key] = $type;
	foreach($rec->struct['array'] as $type=>$j)
		foreach($j as $key)
			if($doc->id || !$rec->fields[$key]->isOwn)
				$array[$key] = $type;
	if($doc->id)
		foreach($rec->struct['rating'] as $type=>$j)
			foreach($j as $key)
					$rating[$key] = $type;
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?=$ds->name?> | <?=$_GET['id'] == 'new' ? CREATE_HYBRID : htmlspecialchars($doc->header, ENT_COMPAT, 'UTF-8').' | '.EDIT_HYBRID?></title>
		<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
<script src="<?=WCHOME?>/lib/js/base.js"></script>
<script src="<?=WCHOME?>/lib/js/controllers.js"></script>
<script src="<?=WCHOME?>/lib/js/widgets.js"></script>
<script src="<?=WCHOME?>/lib/js/wysiwyg.js"></script>
<script src="<?=WCHOME?>/lib/js/nodeselectwidget.js"></script>
<script src="<?=WCHOME?>/lib/js/validators.js"></script>
<script>
	var
		host='<?=$_host?>',
		skin='<?=SKIN?>',
		control, alt, keys, isReady
		;
	function init() {
		// Autogenerated function

		isReady = new IsReadyController(document.getElementById("btn.save"), null);
		isReady.addTarget(new RegExpValidator(document.getElementById('header'), document.getElementById('e.header'),/\S+/i));
<?php	if(!(@$_GET['hybridid'] > 0
			|| ($doc->id > 0
				? WCS::decide($ds, 'editdoc') || WCS::decide($doc, 'editdoc')
				: WCS::decide($ds, 'createdoc'))
			)) : ?>
		isReady.addTarget(new RegExpValidator(document, null, /\S+/i));
<?php 	endif; ?>
		isReady.init();

		// Key bindings
		keys = new KeyBinder(document);
		keys.addShortcut("ctrl+s", document.getElementById("btn.save").click); // Save
		keys.addShortcut("ctrl+h", function() {
			var ctl;
			for(ctlName in control) {
				ctl = control[ctlName];
				if(ctl.editor.iframe.parentNode.style.display != "none") {
					ctl.editSource();
					return;
				}
			}

		}); // View source

<?php	if(sizeof($rec->struct['enum'])):?>
		// Enum fields
<?php	foreach($rec->struct['enum'] as $j)
			foreach($j as $type=>$key):?>
		new SelectWidget(
			document.getElementById('<?=$key?>'),
			document.getElementById('btn.<?=$key?>'),
			null,
			"<?=BTN_CLOSE?>",
			"select",
			"@import url('/openconstructor/lib/js/api.css');",
			200,300
		);
<?php 		endforeach;
		endif;
		if(sizeof($rec->struct['tree'])):?>
		// Tree fields
<?php	foreach($rec->struct['tree'] as $j)
			foreach($j as $type=>$key):?>
		new NodeSelectWidget(
			document.getElementById('<?=$key?>'),
			document.getElementById('btn.<?=$key?>'),
			document.getElementById('btn.<?=$key?>'),
			280,300
		);
<?php 		endforeach;
		endif;
		if(sizeof($html)):?>
		// HTML fields
		control = new Array();
		alt = new AltElementsController();
<?php 	foreach($html as $key=>$type):?>
		control['<?=$key?>'] = new WYSIWYGController();
		new WYSIWYGWidget(
			document.getElementById('<?=$key?>'),
			document.getElementById('iframe.<?=$key?>'),
			control['<?=$key?>'],
			'@import url("http://<?=$_SERVER['HTTP_HOST']?>/css/content.css");'
		);
		alt.add(
			document.getElementById('d.<?=$key?>'),
			'<?=addslashes($rec->fields[$key]->header)?>'
		);
		keys.addTarget(document.getElementById('iframe.<?=$key?>').contentWindow.document, document.getElementById('iframe.<?=$key?>').contentWindow);
<?php 	endforeach; ?>
		// Placing all these WYSIWYGS into tabs
		new TabbedWidget(alt, document.getElementById("tabs.wysiwyg"),"dd");

<?php	endif;
		if(sizeof($primitive))
			foreach($primitive as $key=>$type)
				if($type == 'date' || $type == 'datetime'):?>
		new CalendarWidget(document.getElementById('<?=$key?>'),document.getElementById('btn.<?=$key?>'),1970,(new Date()).getUTCFullYear() + 10,"calendar","<?=LANGUAGE?>");
<?				endif;

		if(sizeof($document)):?>

		// Documents
		var docs = new DocumentsController();
<?php 		foreach($document as $key=>$type): ?>
		docs.add(document.getElementById('<?=$key?>'),'<?=addslashes($rec->fields[$key]->header)?>');
<?php		endforeach;?>
		new DocumentsWidget(docs, document.getElementById('f.docs'), ['<?=H_DOCFIELD_NAME?>','<?=H_DOCFIELD_VALUE?>','<?=H_DOCFIELD_CREATE?>','<?=H_DOCFIELD_REMOVE?>','<?=H_DOCFIELD_SELECT?>']);

<?php
		 endif;
		if(sizeof($array)):?>
		// Arrays
		altarr = new AltElementsController();
<?php 		foreach($array as $key=>$type): ?>
		new ArrayWidget(
			new ArrayController(document.getElementById('<?=$key?>')),
			document.getElementById('tab.<?=$key?>'),['<?=H_ARRAYFIELD_CREATE?>', '<?=H_ARRAYFIELD_ADD?>', '<?=H_ARRAYFIELD_REMOVE?>']
		);
		altarr.add(document.getElementById('tab.<?=$key?>'), '<?=addslashes($rec->fields[$key]->header)?>');
<?php		endforeach;?>
		// Placing all these arrays into tabs;
		new TabbedWidget(altarr, document.getElementById("tabs.array"),"dd");
<?php	endif; ?>
	}
</script>
<script src="../editor.js"></script>
<style>
	@import url(/openconstructor/lib/js/api.css);
	@import url(editor.css);
	FIELDSET {
		padding:10px;
	}
	LEGEND {
		margin-bottom:5px;
		font-weight:bold;
	}
	A IMG {
		border:none;
	}
	SPAN.yesno {
		padding-right:20px;
		cursor:default;
	}
	SPAN.yesno A {
		text-decoration:none;
		color:#000 !important;
		cursor:pointer;
	}
	SPAN.yesno A INPUT {
		vertical-align:middle;
	}
</style>
</head>
<body style="border:groove;border-width:2;margin:10;" ondrag="return false" onload="init();">
<?php if($doc->id):?>
	<div class="head"><?=htmlspecialchars($doc->header, ENT_COMPAT, 'UTF-8')?> <a href="javascript:location.href=location.href.replace(/((?:\?|&)ds_id)=\d+(&|$)/i,'$1=<?=$doc->ds_id?>$2');"><?=$doc->ds_id != $rec->ds_id ? '[autocast]' :'';?></a></div>
<?php endif;?>
	<form method="POST" style="margin:0;padding:0" enctype="multipart/form-data" name="f" action="i_hybrid.php">
		<input type=hidden name="action" value="<?=$_GET['id']=='new'?'create':'edit'?>_hybrid">
		<input type="hidden" name="ds_id" value="<?=$_GET['ds_id']?>">
		<input type="hidden" name="id" value="<?=$doc->id?>">
		<input type="hidden" name="hybridid" value="<?=@$_GET['hybridid']?>">
		<input type="hidden" name="fieldid" value="<?=@$_GET['fieldid']?>">
		<input type="hidden" name="callback" value="<?=@$_GET['callback']?>">
		<b><?=PR_DOC_HEADER?></b>:<br>
		<div id="e.header" errorclass="pink"><textarea name="header" id="header" rows="2" style="width:100%"><?=$doc->header?></textarea></div>
		<fieldset <?=!WCS::decide($ds, 'publishdoc') ? 'disabled' : ''?>>
			<legend><?=PR_DOC_PUBLISHED_STATUS?></legend>
			<?=H_PUBLISH_DOC?>:
			<span class="yesno"><a href="javascript: document.forms['f'].published[0].click();"><input type="radio" name="published" value="1" <?=$doc->isPublished ? 'checked' : ''?>> <?=H_PUBLISH_YES?></a></span>
			<span class="yesno"><a href="javascript: document.forms['f'].published[1].click();"><input type="radio" name="published" value="0" <?=!$doc->isPublished ? 'checked' : ''?>> <?=H_PUBLISH_NO?></a></span>
		</fieldset>
		<?php
		if($doc->id && sizeof($rec->struct['rating'])):?>
		<fieldset>
			<legend>Rating</legend>
			<table cellpadding="3">
			<?php
				foreach($rec->struct['rating'] as $j)
					foreach($j as $type => $key) {
						echo '<tr><td>';
						if($doc->fields[$key]['id'] > 0)
							echo '<a href="../rating/edit.php?ds_id='.$rec->fields[$key]->fromDS.'&id='.$doc->fields[$key]['id'].'" target="_blank" onclick="openWindow(this.href, 788, 520); return false;">'.$rec->fields[$key]->header.'</a>:</td><td>'
								.H_RATING_VALUE.': <b>'.$doc->fields[$key]['rating'].'</b> / '.H_RATING_VOTES.': '.$doc->fields[$key]['votes'];
						else
							echo $rec->fields[$key]->header.':</td><td>'.H_SAVE_DOC_TO_MANAGE_DS;
						echo '</td></tr>';
					}
			?>
			</table>
		</fieldset>
		<?php
		endif;
		if(sizeof($rec->struct['tree']) || sizeof($rec->struct['enum'])):?>
		<fieldset>
			<legend>Trees and Enums</legend>
			<table cellpadding="3">
			<?php
				$ef = &WCEnumFactory::getInstance();
				if(sizeof($rec->struct['enum']))
					foreach($rec->struct['enum'] as $j)
						foreach($j as $type=>$key) {
							$enum = $ef->load($rec->fields[$key]->enumId);
							$values = $enum ? $enum->getAllValues() : array();
							echo '<tr><td>'.$rec->fields[$key]->header.': <a href="javascript:void(0);" id="btn.'.$key.'">'.H_SELECT_VALUE.'</a><select id="'.$key.'" name="'.$key.'[]" style="display:none"'.($rec->fields[$key]->isArray ? ' multiple' : '').'>';
							foreach($values as $id => $v)
								echo '<option value="'.$id.'" '.(($rec->fields[$key]->isArray ? array_search($id, $doc->fields[$key]) !== false  : $id == $doc->fields[$key] ) ? 'selected': '').'>'.$v['header'];
							echo '</select></td></tr>';
						}
				if(sizeof($rec->struct['tree']))
					foreach($rec->struct['tree'] as $j)
						foreach($j as $type=>$key) {
							echo '<tr><td>'.$rec->fields[$key]->header.': <a href="javascript:void(0);" id="btn.'.$key.'">'.H_SELECT_VALUE.'</a><select id="'.$key.'" name="'.$key.'[]" tree="'.$rec->fields[$key]->treeId.'" style="display:none"'.($rec->fields[$key]->isArray ? ' multiple' : '').'>';
							foreach($doc->fields[$key] as $id=>$header)
								echo '<option value="'.$id.'" selected>'.$header;
							echo '</select></td></tr>';
						}
			?>
			</table>
		</fieldset>
		<?php endif;
		if($doc->id && sizeof($rec->struct['file'])):?>
		<fieldset>
			<legend>Files</legend>
			<table cellpadding="3">
			<?php
				foreach($rec->struct['file'] as $j)
					foreach($j as $type=>$key) {
						if($doc->fields[$key])
							echo "<tr><td><a href='{$doc->fields[$key]}' target='_blank'>{$rec->fields[$key]->header}</a>:</td>";
						else
							echo "<tr><td>{$rec->fields[$key]->header}:</td>";
						if(sizeof($rec->fields[$key]->types))
							$types = sprintf("Allowed types: %s\nSize: up to %4.02f KB", implode(',', $rec->fields[$key]->types), $rec->fields[$key]->maxSize / 1024);
						else
							$types = '';
						echo "<td><input type='file' name='$key' id='$key' title='$types'>";
						if($doc->fields[$key])
							echo " <input type='checkbox' name='$key' id='chk.$key' value='-1' onclick='document.getElementById(\"$key\").disabled = this.checked'> <label for='chk.$key'>".H_REMOVE_FILE.'</label>';
						echo "</td></td>";
					}
			?>
			</table>
		</fieldset>
		<?php endif;
		if(sizeof($primitive)):?>
		<fieldset>
			<legend>Primitives</legend>
			<table width=100%>
			<?php
				foreach($primitive as $key=>$type){
					echo '<tr>';
					switch($type) {
					case 'date':case 'datetime':
						echo '<td>'.$rec->fields[$key]->header.':</td><td><input type="text" name="'.$key.'" value="'.$doc->fields[$key].'" size="25"> <img align="absmiddle" src="'.WCHOME.'/i/default/e/h/calendar.gif" class="btn" alt="'.H_SELECT_VALUE.'" id="btn.'.$key.'"></td>';
					break;
					case 'boolean':
					?>
						<td><?=$rec->fields[$key]->header?>:</td>
						<td>
							<span class="yesno"><a href="javascript: document.forms['f'].<?=$key?>[0].click();"><input type="radio" name="<?=$key?>" value="1" <?=$doc->fields[$key] ? 'checked' : ''?>> Yes</a></span>
							<span class="yesno"><a href="javascript: document.forms['f'].<?=$key?>[1].click();"><input type="radio" name="<?=$key?>" value="0" <?=!$doc->fields[$key] ? 'checked' : ''?>> No</a></span>
						</td>
					<?php
					break;
					case 'string':
						echo "<td>{$rec->fields[$key]->header}:</td>";
						$value = htmlspecialchars($doc->fields[$key], ENT_COMPAT, 'UTF-8');
						if($rec->fields[$key]->length <= 40)
							echo "<td><input type='text' name='$key' value='$value' maxlength='{$rec->fields[$key]->length}' size='{$rec->fields[$key]->length}'></td>";
						else {
							$rows = intval($rec->fields[$key]->length / 40) + 1;
							echo "<td><textarea name='$key' cols='40' rows='$rows'>$value</textarea></td>";
						}
						unset($value);
					break;
					case 'text':
						echo "<td colspan=2>{$rec->fields[$key]->header}:<br/>";
						$value = htmlspecialchars($doc->fields[$key], ENT_COMPAT, 'UTF-8');
						echo "<textarea name='$key' rows='15' style='width:100%'>$value</textarea></td>";
						unset($value);
					break;
					case 'pagepath':
						require_once(ROOT.FILES.'/map._wc');
						echo '<td>'.$rec->fields[$key]->header.':</td><td>';
						echo '<select name="'.$key.'" value="'.$doc->fields[$key].'">';
							echo '<option value="">Choose page path';
							foreach($_map as $uri => $arr) {
								echo '<option value="'.$arr['id'].'"'.($arr['id'] == $doc->fields[$key] ? ' SELECTED' : '').'>'.$uri;
							}
						echo '</select>';
						echo '</td>';
					break;
					default:
						echo '<td>'.$rec->fields[$key]->header.':</td><td><input type="text" name="'.$key.'" value="'.$doc->fields[$key].'"></td>';
					}
					echo '</tr>';
				}
			?>
			</table>
		</fieldset>
		<?php endif;
		if(sizeof($html)):?>
		<fieldset>
			<legend>HTML Fields</legend>
			<div id="tabs.wysiwyg" class="tabs"></div>
			<div id="wysiwyg">
			<div style="width:100%;">
			<?php
				foreach($html as $key=>$type){
					echo '<div id="d.'.$key.'">';
					echo '<nobr id="tool.wysiwyg" class="tool">';
						wysiwygtoolbar('control["'.$key.'"]');
					echo '</nobr>';
					echo '<textarea name="'.$key.'" id="'.$key.'" style="width:100%; height: 400px;border-color: red; display:none">'.htmlspecialchars($doc->fields[$key], ENT_COMPAT, 'UTF-8').'</textarea>';
					echo '<iframe id="iframe.'.$key.'" width="100%" height="400"></iframe>';
					echo '</div>';
				}
			?>
			</div></div>
		</fieldset>
		<?php endif;
		if(sizeof($document)):?>
		<fieldset>
			<legend>Documents</legend>
			<div id="f.docs" class="docs"></div>
			<?php
				foreach($document as $key=>$type)
					echo '<input type="hidden" id="'.$key.'" name="'.$key.'"'.
						' value="'.$doc->fields[$key]['id'].'"'.
						' header="'.htmlspecialchars($doc->fields[$key]['header'], ENT_COMPAT, 'UTF-8').'"'.
						' isown="'.($rec->fields[$key]->isOwn ? 1 : 0).'"'.
						' doctype="'.$rec->fields[$key]->type.'"'.
						' fromds="'.$rec->fields[$key]->fromDS.'"'.
						' hybrid="'.$doc->id.'"'.
						' fieldid="'.$rec->fields[$key]->id.'">';
			?>
		</fieldset>
		<?php endif;
		if(sizeof($array)):?>
		<fieldset>
			<legend>Arrays of documents</legend>
			<div id="tabs.array" class="tabs"></div>
			<div id="array"><div style="width:100%;">
			<?php
				foreach($array as $key=>$type){
					echo '<div id="tab.'.$key.'" class="tabbed">';
					echo '<select id="'.$key.'" name="'.$key.'[]" multiple size="1" style="display:none;"'.
						' isown="'.($rec->fields[$key]->isOwn ? 1 : 0).'"'.
						' doctype="'.$rec->fields[$key]->type.'"'.
						' fromds="'.$rec->fields[$key]->fromDS.'"'.
						' hybrid="'.$doc->id.'"'.
						' fieldid="'.$rec->fields[$key]->id.'">';
					foreach($doc->fields[$key] as $id=>$header)
						echo '<option value="'.$id.'">'.$header;
					echo '</select></div>';
				}
			?>
			</div></div>
		</fieldset>
		<?php endif;
		if(sizeof($rec->struct['datasource']) && $doc->id):?>
		<fieldset>
			<legend>Datasources</legend>
			<table cellpadding="3">
			<?php
				foreach($rec->struct['datasource'] as $j)
					foreach($j as $type=>$key) {
						echo '<tr><td>'.$rec->fields[$key]->header.':</td><td>';
						if(intval($doc->fields[$key]) > 0)
							echo '<a href="../internal_ds.php?node='.$doc->fields[$key].'" target="_blank">'.H_MANAGE_DS.'</a>';
						else
							echo H_SAVE_DOC_TO_MANAGE_DS;
						echo '</td></tr>';
					}
			?>
			</table>
		</fieldset>
		<?php endif;?>
		<br>
		<div align="right">
			<input type="button" value="<?=BTN_NEW_DOCUMENT?>" style="float: left;" onclick="location.href=location.href.replace(/((?:\?|&)id)=\d+(&|$)/i,'$1=new');">
			<input type="submit" id="btn.save" value="<?=BTN_SAVE?>">
		</div>
	</form>
</body>
</html>