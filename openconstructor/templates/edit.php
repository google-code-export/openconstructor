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
 * $Id: edit.php,v 1.20 2007/03/05 07:15:35 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/templates._wc');
	
	require_once(LIBDIR.'/templates/wctemplates._wc');
	$tpls = new WCTemplates();
	if($_GET['id'] == 'new'){
		$type = @$_GET['type'];
		$tpl = new WCTemplate($type,'');
		assert($tpls->objectSupported($type));
		if(isset($_GET['header']))
			$tpl->name = $_GET['header'];
		if(file_exists(LIBDIR.'/tpl/'.$type.'.tpl'))
			$defTpl = implode('', file(LIBDIR.'/tpl/'.$type.'.tpl'));
	} else {
		$tpl = &$tpls->load(@$_GET['id']);
		assert($tpl != null);
		$tpls->parse($tpl);
	}
	require_once(LIBDIR.'/syntax/syntaxhighlighter._wc');
	$save = $tpl->id ? WCS::decide($tpl, 'edittpl') : System::decide('tpls.ds'.@$_GET['dstype']);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?=$_GET['id'] != 'new' ? EDIT_TEMPLATE.' | '.htmlspecialchars($tpl->name, ENT_COMPAT, 'UTF-8') : CREATE_TEMPLATE?></title>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
<script>
		function clearCache() {
			var d=new Date();
			openModal("manage_tpl.php?clearcache=1&type=<?=$tpl->type?>&id=<?=$tpl->id?>&msg=<?=rawurlencode(CACHE_HB_CLEARED_I)?>&j="+Math.ceil(d.getTime()/1000),350,170);
			document.getElementById('btn.clearcache').disabled=true;
		}
		function clearCompiled() {
			var d=new Date();
			openModal("manage_tpl.php?clearcompiled=1&type=<?=$tpl->type?>&id=<?=$tpl->id?>&msg=<?=rawurlencode(TPL_HB_RECOMPILED_I)?>&j="+Math.ceil(d.getTime()/1000),350,170);
			document.getElementById('btn.clearcompiled').disabled=true;
		}
		function saveTpl() {
			var src = getSrcEdit();
			f.html.value = src.getSource();
			src.setEditable(false);
			f.caret.value = src.getCaretPosition();
			f.save.disabled = true;
			f.submit();
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
		function loadDefaultTpl() {
			var src = getSrcEdit();
			src.setSource(f.defaultTpl.value);
		}
		window.onload = function() {
			var rexpName = /[^\s]+/gi;
			f.tpl_name.onpropertychange = function() {
				f.save.disabled = !(<?=$save ? 'true' : 'false' ?> && this.value.match(rexpName));
			}
			keys = new KeyBinder(document);
			keys.addShortcut("ctrl+s", f.save.click);
			keys.addShortcut("ctrl+l", window.goToLineDialog);
			var src = getSrcEdit();
			if(f.html.value.length > 0) {
				src.setSource(f.html.value);
				window.setTimeout(function() {
					src.setCaretPosition(<?=intval(@$_GET['caret'])?>);
				}, 50);
			}
			//src.setCaretPosition(<?=intval(@$_GET['caret'])?>);
			<?php if($_GET['id'] != 'new' && !$save): ?>
			src.setEditable(false);
			<?php endif; ?>
		}
		
		function getSrcEdit() {
			return document.getElementById("src.html");
		}
	</script>
<script src="../lib/js/base.js"></script>
</head>
<body style="border:groove;border-width:2;margin:0;" ondrag="return false;">
	<form method="POST" style="margin:0;padding:0" enctype="multipart/form-data" name="f" action="i_templates.php">
	<input type=hidden name="dstype" value="<?=$_GET['dstype']?>">
	<input type=hidden name="action" value="<?=$_GET['id'] == 'new' ? 'create' : 'edit'?>_tpl">
	<input type=hidden name="id" value="<?=$_GET['id']?>">
	<input type="hidden" name="type" value="<?=$tpl->type?>">
	<input type="hidden" name="caret" value="0">
	<input type="hidden" name="select" value="<?=!$tpl->id && @$_GET['select'] ? 1 : 0?>">
	<textarea name="html" style="display:none;"><?=$_GET['id'] == 'new' ? '' : htmlspecialchars($tpl->tpl, ENT_COMPAT, 'UTF-8')?></textarea>
	<textarea name="defaultTpl" style="display:none;"><?=!$tpl->id  ? htmlspecialchars($defTpl, ENT_COMPAT, 'UTF-8') : ''?></textarea>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
		<tr>
			<td style="padding:10px;">
				<?=H_TPL_NAME?>:<br>
				<input type="text" name="tpl_name" style="width: 100%;" maxlength="255" value="<?=htmlspecialchars(@$tpl->name, ENT_COMPAT, 'UTF-8')?>">
			</td>
		</tr>
		<?php if(sizeof($tpl->errors)):
				$errorText = array(
					E_USER_NOTICE => 'Notice',
					E_USER_WARNING => 'Warning',
					E_USER_ERROR => 'Fatal error',
				);
				$errorText[E_NOTICE] = $errorText[E_USER_NOTICE];
				$errorf = '<li>%s: %s</li>';
		?>
		<tr>
			<td style="padding:0px 10px 5px;">
				<script type="text/javascript">
					function toggleErrors() {
						var errors = document.getElementById("div.errors"),
							hide =  document.getElementById("a.hide"),
							show =  document.getElementById("a.show");
						if(errors.style.display != "none") {
							errors.style.display = "none";
							hide.style.display = "none";
							show.style.display = "inline";
						} else {
							errors.style.display = "";
							hide.style.display = "inline";
							show.style.display = "none";
						}
					}
				</script>
				<div style="text-align: right; font-size: 85%; padding-bottom: 3px;">
					<a href="#hide errors" onclick="toggleErrors(); return false;" id="a.hide"><?=BTN_HIDE_ERRORS?></a>
					<a href="#show errors" style="display: none;" onclick="toggleErrors(); return false;" id="a.show"><?=BTN_SHOW_ERRORS?></a>
				</div>
				<div class="fresult" id="div.errors">
						<ul>
						<?php
							foreach($tpl->errors as $v) {
								$msg = preg_replace('~^(\s*)\[(line (\d+))\]~su', '\1[<a href="javascript: goToLine(\3);">\2</a>]', $v['msg']);
								if($v['type'] == E_USER_ERROR)
									echo sprintf($errorf, "<b>{$errorText[$v['type']]}</b>", $msg);
								else
									echo sprintf($errorf, $errorText[$v['type']], $msg);
							}
						?>
						</ul>
				</div>
			</td>
		</tr>
		<?php endif;?>
		<tr>
			<td height="100%" style="padding:0 10px;">
				<div style=" border-width:2px; border-style: groove; height: 100%;">
					<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" style="width: 100%; height: 100%;" id="src.html"><?=SyntaxHighlighter::getHtmlEditor()?></object>
				</div>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 10px;">
				<div style="float: left;">
				<?php if($_GET['id'] != 'new') :?>
					<input type="button" id="btn.clearcache" onclick="clearCache()" value="<?=BTN_CLEAR_SMARTY_TPL_CACHE?>" <?=WCS::decide($tpl, 'edittpl') ? '' : 'disabled'?>>
					<input type="button" id="btn.clearcompiled" onclick="clearCompiled()" style="width:190px;" value="<?=BTN_CLEAR_SMARTY_TPL_COMPILED?>" <?=WCS::decide($tpl, 'edittpl') ? '' : 'disabled'?>>
					<input type="button" onclick="window.location.assign('?id=new&dstype=<?=@$_GET['dstype']?>&type=<?=$tpl->type?>')" value="<?=BTN_NEW_TEMPLATE?>">
				<?php else: ?>
					<input type="button" onclick="loadDefaultTpl()" value="<?=BTN_LOAD_DEFAULT?>" <?=isset($defTpl) ? '' : 'disabled'?>>
				<?php endif; ?>
				</div>
				<div style="float: right;">
					<input type="button" name="save" disabled="yes" onclick="saveTpl()" value="<?=BTN_SAVE?>">
				</div>
			</td>
		</tr>
	</table>
	</form>
</body>
</html>