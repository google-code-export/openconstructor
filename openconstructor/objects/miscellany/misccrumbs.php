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
 * $Id: misccrumbs.php,v 1.11 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	
	$obj = ObjManager::load(@$_GET['id']);
	assert($obj != null);
	require_once(LIBDIR.'/site/pagereader._wc');
	$pr = PageReader::getInstance();
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
</style>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_OBJECT?></h3>
<form name="f" method="POST" action="i_miscellany.php">
	<input type="hidden" name="action" value="edit_misccrumbs">
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
	<fieldset style="padding:10"><legend><?=OBJ_PROPERTIES?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><?=PR_HEADER?>:</td>
			<td><input type="text" name="header" value="<?=htmlspecialchars(@$obj->header, ENT_COMPAT, 'UTF-8')?>"></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10" id="sitemap"><legend><?=H_EXCLUDE_PAGES?></legend>
	<div style="padding: 10px 0;"><ul>
	<?php
		$pages = $pr->getAllPages();
		settype($obj->exclude, 'array');
		$tree = $pr->getTree();
		$ids = array_keys($pages);
		for($i = 0, $_l = sizeof($ids); $i < $_l; $i++) {
			$node = &$tree->node[$ids[$i]];
			if($node->index == 0) {
				echo "<li style='list-style-type: none;' i='".(array_search($node->id, $obj->exclude) !== false ? '-' : '')."$node->id'><b>{$node->header}</b></li>";
				$level = 1;
				continue;
			}
			$l = substr_count($pages[$node->id], '/');
			$diff = $l - $level;
			if($diff > 0)
				echo "<ul>";
			elseif($diff < 0)
				echo str_repeat("</ul>", -$diff);
			echo "<li i='".(array_search($node->id, $obj->exclude) !== false ? '-' : '')."$node->id' title='$node->key'>{$node->header}";
			$level = $l;
		}
	?>
	</ul></div>
	</fieldset><br>
	<script>
		(function () {
			var li = document.getElementById("sitemap").getElementsByTagName("LI");
			for(var i = 0, id = null; i < li.length; i++) {
				id = parseInt(li[i].i);
				li[i].innerHTML = "<input type='checkbox' name='exclude[]' value='" + Math.abs(id) + "' id='ex" + Math.abs(id) + "'> " + li[i].innerHTML;
				if(id < 0)
					document.getElementById("ex" + Math.abs(id)).checked = true;
			} 
		})();
	</script>
	<div align="right">
	<input type="button" value="<?=BTN_MANAGE_OBJECT_USES?>" style="float: left;" onclick="openObjectUses(<?=$obj->obj_id?>);">
	<input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div><br><br><br>
</form>
<script>dsb();</script>
</body>
</html>
