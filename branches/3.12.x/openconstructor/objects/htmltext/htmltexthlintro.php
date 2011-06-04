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
 * $Id: htmltexthlintro.php,v 1.11 2007/03/02 10:06:44 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	
	$obj = ObjManager::load(@$_GET['id']);
	assert($obj != null);
	require_once(LIBDIR.'/site/pagereader._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	$pr = PageReader::getInstance();
	$_dsm=new DSManager();
	$ds = $_dsm->getAll($obj->ds_type);
?>
<html>
<head>
<title><?=WC.' | '.EDIT_OBJECT?> | <?=htmlspecialchars($obj->name, ENT_COMPAT, 'UTF-8')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../<?=SKIN?>.css" type=text/css rel=stylesheet>
<link href="../tree.css" type=text/css rel=stylesheet>
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
var oldLi = null;
function markLi(value) {
	if(oldLi)
		oldLi.childNodes[2].className = "";
	if(value > 0) {
		var li = document.getElementById("l" + value);
		li = li ? li : document.getElementById("l-" + value);
		if(li)
			li.childNodes[2].className = "selected";
		oldLi = li;
	}
}
</script>
<style>
#sitemap UL {
	margin: 0 0 0 30px;
	padding: 0;
	list-style-type: none;
	font-size: 11px;
}
#sitemap LI {
	margin: 2px 0;
	padding: 0;
}
#sitemap LI I {
	font-style: normal;
}
#sitemap LI I.selected {
	font-style: italic;
	font-weight: bold;
}
</style>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_OBJECT?></h3>
<form name="f" method="POST" action="i_htmltext.php">
	<input type="hidden" name="action" value="edit_htmltexthlintro">
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
	<fieldset style="padding:10" <?=WCS::decide($obj, 'editobj.ds') ? '' : 'disabled'?>><legend><?=OBJ_DATA?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><a href="<?=WCHOME?>/data/?node=<?=$obj->ds_id?>" target="_blank" title="<?=H_OPEN_DATASOURCE?>"><?=PR_DATASOURCE?></a>:</td>
			<td><select size="1" name="ds_id">
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
<?php
	include('../select_tpl._wc');
?>
	<fieldset style="padding:10"><legend><?=OBJ_PROPERTIES?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_HEADER?>:</td>
			<td><input type="text" name="header" value="<?=htmlspecialchars(@$obj->header, ENT_COMPAT, 'UTF-8')?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_CUT_INTRO?>:</td>
			<td><input type="text" name="cutintro" value="<?=intval(@$obj->cutIntro)>0?intval(@$obj->cutIntro):'0'?>"></td>
		</tr>
		<tr>
			<td nowrap><?=PR_PAGE_MORE_HREF_TEXT?>:</td>
			<td><input type="text" name="more" value="<?=$obj->more?>"></td>
		</tr>
		<tr>
			<td><?=PR_PAGE_URI?>:</td>
			<td><select name="page_id" size="1" onchange="markLi(this.options[this.selectedIndex].value);">
			<OPTION value="0">.
<?php
	$pages = $pr->getAllPages();
	foreach($pages as $id => $uri)
		echo '<OPTION value="'.$id.'"'.($id == $obj->pageId ? ' SELECTED' : '').'>'.$uri;
?>
</select></td>
		</tr>
		<tr>
			<td colspan=2>&nbsp;&nbsp;<input type=checkbox name="children" value="true" <?=$obj->children ? 'checked' : ''?> onclick="document.getElementById('sitemap').style.display = this.checked ? '' : 'none'" id="ch.children"> <label for="ch.children"><?=PR_PAGE_CHILDREN?></label>
		</td>
		</tr>
	</table>
	<fieldset style="padding:10" id="sitemap"><legend><?=H_EXCLUDE_PAGES?></legend>
	<div style="padding: 10px;">
	<?php
		settype($obj->exclude, 'array');
		$tree = $pr->getTree();
		$ids = array_keys($pages);
		for($i = 0, $_l = sizeof($ids); $i < $_l; $i++) {
			$node = &$tree->node[$ids[$i]];
			if($node->index == 0) {
				echo "<div style='padding: 5px 0'><b>{$node->header}</b></div>";
				$level = 1;
				continue;
			}
			$l = substr_count($pages[$node->id], '/');
			$diff = $l - $level;
			if($diff > 0)
				echo "<ul>";
			elseif($diff < 0)
				echo str_repeat("</ul>", -$diff);
			echo "<li id='l".(array_search($node->id, $obj->exclude) !== false ? '-' : '')."$node->id' title='$node->key'><i>{$node->header}</i>";
			$level = $l;
		}
	?>
	</div>
	</fieldset><br>
	<script>
		f.children.onclick();
		(function () {
			var li = document.getElementById("sitemap").getElementsByTagName("LI");
			for(var i = 0, id = null; i < li.length; i++) {
				id = parseInt(li[i].id.substr(1));
				li[i].innerHTML = "<input type='checkbox' name='exclude[]' value='" + Math.abs(id) + "' id='ex" + Math.abs(id) + "'> " + li[i].innerHTML;
				if(id < 0)
					document.getElementById("ex" + Math.abs(id)).checked = true;
			} 
		})();
	</script>
	</fieldset><br>
	<div align="right">
	<input type="button" value="<?=BTN_MANAGE_OBJECT_USES?>" style="float: left;" onclick="openObjectUses(<?=$obj->obj_id?>);">
	<input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div>
</form>
<script>
	f.page_id.onchange();
	dsb();
</script>
</body>
</html>