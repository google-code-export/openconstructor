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
 * $Id: edit.php,v 1.13 2007/03/05 07:15:14 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	if(!isset($_GET['ds_id'])||!isset($_GET['id'])) die();
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = &$dsm->load($_GET['ds_id']);
	if($_GET['id']!='new') {
		$_doc=$_ds->get_record($_GET['id']);
		assert($_doc != null);
	} else
		$_doc = array();
	require_once(LIBDIR.'/syntax/syntaxhighlighter._wc');
	$sDoc = $_ds->wrapDocument($_doc);
	$save = $_GET['id'] == 'new' ? WCS::decide($_ds, 'createdoc') : WCS::decide($_ds, 'editdoc') || WCS::decide($sDoc, 'editdoc');
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?=$_ds->name?> | <?=$_GET['id'] != 'new' ? htmlspecialchars($_doc['header'], ENT_COMPAT, 'UTF-8').' | '.EDIT_PHPSOURCE : CREATE_PHPSOURCE?></title>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
	<script>
		function saveDoc()	{
			var editApplet = getSrcEdit();
			editApplet.setEditable(false);
			f.html.value = editApplet.getSource();
			f.caret.value = editApplet.getCaretPosition();
			f.save.disabled = true;
			f.submit();
		}
		
		window.onload = function() {
			keys = new KeyBinder(document);
			keys.addShortcut("ctrl+s", f.save.click);
			keys.addShortcut("ctrl+l", window.goToLineDialog);
			var editApplet = getSrcEdit();
			if (f.html.value.length > 0) {
				editApplet.setSource(f.html.value);
				window.setTimeout(function() {
					var c = <?=intval(@$_GET['caret'])?>;
					for (var s = 20, i = c % s; i <= c; i += s)
						editApplet.setCaretPosition(i);
				}, 50);
			}
			<?php if($_GET['id'] != 'new' && !(WCS::decide($_ds, 'editdoc') || WCS::decide($sDoc, 'editdoc'))): ?>
				editApplet.setEditable(false);
			<?php endif; ?>
			var rexpHeader = /[^\s]+/g;
			f.header.onpropertychange = function() {
				f.save.disabled = !(<?=$save ? 'true' : 'false' ?> && this.value.match(rexpHeader));
			}
		}
		
		function goToLineDialog() {
			var src = getSrcEdit();
			goToLine(prompt("Go to line [1.." + src.getLineCount() + "]:", src.getCaretLine() + 1));
		}
		
		function goToLine(line) {
			var src = getSrcEdit();
			try {
				var index = parseInt(line) - 1;
				if(index >= 0 && index < src.getLineCount())
					src.setCaretLine(index);
			} catch(e){}
			src.focus();
		}
		
		function getSrcEdit() {
			return document.getElementById("srcEdit");
		}
	</script>
	<script src="../../lib/js/base.js"></script>
</head>
<body style="border:groove;border-width:2;margin:10;">
	<form method="POST" style="margin:0;padding:0" enctype="multipart/form-data" name="f" action="i_phpsource.php">
	<textarea name="html" style="display:none"><?=$_GET['id'] == 'new' ? '' : htmlspecialchars($_doc['source'], ENT_COMPAT, 'UTF-8')?></textarea>
	<input type=hidden name="action" value="<?=$_GET['id'] == 'new' ? 'create' : 'edit'?>_source">
	<input type=hidden name="id" value="<?=$_GET['id']?>">
	<input type="hidden" name="ds_id" value="<?=$_GET['ds_id']?>">
	<input type="hidden" name="caret" value="0">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
		<tr>
			<td style="padding-bottom:5px">
				<?=F_PHPSOURCE_HEADER?>:<br>
				<input type="text" name="header" maxlength="255" style="width: 100%;" value="<?=htmlspecialchars(@$_doc['header'], ENT_COMPAT, 'UTF-8')?>"><br>
			</td>
		</tr>
		<tr height="100%" valign="top">
			<td style="border-width:2px; border-style: groove;">
				<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" style="width: 100%; height: 100%;" id="srcEdit">
					<?=SyntaxHighlighter::getPhpEditor()?>
				</object>
			</td>
		</tr>
		<tr>
			<td style="padding-top: 5px;">
				<div style="text-align: right;">
					<div style="float:left;">
						<input type="button" name="goToLine" onclick="goToLineDialog()" value="<?=BTN_GOTO_LINE?>">
						<?php if($_GET['id'] != 'new'): ?>
							<input type="button" onclick="window.location.assign('?id=new&ds_id=<?=$_GET['ds_id']?>')" value="<?=BTN_NEW_DOCUMENT?>">
						<?php endif;?>
					</div>
					<input type="button" name="save" onclick="saveDoc()" disabled="yes" value="<?=$_GET['id'] == 'new' ? BTN_CREATE : BTN_SAVE?>">
				</div>
			</td>
		</tr>
	</table>
	</form>
</body>
</html>