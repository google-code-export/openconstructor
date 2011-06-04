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
 * $Id: edit.php,v 1.15 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	assert(isset($_GET['ds_id']) && isset($_GET['id']));
	require_once($_SERVER['DOCUMENT_ROOT'].WCHOME.'/include/toolbar._wc');
	require_once(LIBDIR.'/site/pagereader._wc');
	$pr = PageReader::getInstance();
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = $dsm->load($_GET['ds_id']); 
	assert($_ds->ds_id > 0);
	
	if($_GET['id'] == 'new') {
		$pages = $pr->getAllPages();
		$db = WCDB::bo();
		$res = $db->query(
			'SELECT id '.
			'FROM dshtmltext '.
			'WHERE ds_id = '.$_ds->ds_id
		);
		while($r = mysql_fetch_assoc($res))
			unset($pages[$r['id']]);
		mysql_free_result($res);
	} else {
		$page = $pr->getPage($_GET['id']);
		assert($page != null);
		$_doc = $_ds->get_record($_GET['id']);
		assert($_doc != null);
	}
	require_once(LIBDIR.'/syntax/syntaxhighlighter._wc');
	$m = array();
	preg_match_all('~<([A-Z0-9]+)~', strtoupper($_ds->allowedTags), $m, PREG_PATTERN_ORDER);
	$allowed = (array) @$m[1];
	$sDoc = $_ds->wrapDocument($_doc);
	$save = $_GET['id'] == 'new' ? WCS::decide($_ds, 'createdoc') && sizeof($pages) > 0 : WCS::decide($_ds, 'editdoc') || WCS::decide($sDoc, 'editdoc');
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?=$_ds->name?> | <?=$_GET['id'] != 'new' ? htmlspecialchars($page->header, ENT_COMPAT, 'UTF-8').' | '.EDIT_HTMLTEXT : CREATE_HTMLTEXT?></title>
		<script src="<?=WCHOME?>/lib/js/base.js"></script>
		<script src="<?=WCHOME?>/lib/js/wysiwyg.js"></script>
		<script>
			var
				host='<?=$_host?>',
				skin='<?=SKIN?>'
				;
			function saveDocument() {
				f.onsubmit();
				f.submit();
			}
			window.onload = function() {
				keys = new KeyBinder(document);
				keys.addShortcut("ctrl+s", function() {if(<?=$save ? 'true' : 'false'?>) saveDocument()});
				keys.addShortcut("ctrl+h", function() {control.editSource();});
				control = new WYSIWYGController();
				new WYSIWYGWidget(
					document.getElementById('txt.intro'),
					document.getElementById('iframe.intro'),
					control,
					'@import url("http://<?=$_SERVER['HTTP_HOST']?>/css/content.css");',
					document.getElementById('src.intro')
				);
				keys.addTarget(document.getElementById('iframe.intro').contentWindow.document, document.getElementById('iframe.intro').contentWindow);
				new WYSIWYGWidget(
					document.getElementById('txt.html'),
					document.getElementById('iframe.html'),
					control,
					'@import url("http://<?=$_SERVER['HTTP_HOST']?>/css/content.css");',
					document.getElementById('src.html')
				);
				keys.addTarget(document.getElementById('iframe.html').contentWindow.document, document.getElementById('iframe.html').contentWindow);
			}
		</script>
		<style>
			@import url(<?=WCHOME.'/'.SKIN?>.css);
			@import url(<?=WCHOME?>/lib/js/api.css);
			A IMG {
				border: none;
			}
		</style>
	</head>
<body style="border:groove; border-width:2; margin:10;" ondrag="return false;">
<form method="POST" style="margin: 0; padding: 0;" enctype="multipart/form-data" name="f" action="i_htmltext.php">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="99%">
	<tr>
		<td style="padding-bottom:5px">
			<input type=hidden name="action" value="<?=$_GET['id'] == 'new' ? 'create' : 'edit'?>_html">
			<input type="hidden" name="ds_id" value="<?=$_GET['ds_id']?>">
			<?=F_PAGE_URI?>: 
			<?php
				if($_GET['id']=='new') {
					echo '<select name="uri_id" size="1" style="font-family:monospace; font-size: 15px;">';
					foreach($pages as $id => $uri)
						echo '<OPTION value="'.$id.'">'.$uri;
					echo '</select>';
				}
				else
					echo '<input type="text" name="uri" value="'.$page->uri.'" disabled style="width:100%;font-family:monospace; font-size: 15px;">'.
					'<input type="hidden" name="uri_id" value="'.$page->id.'">';
			?>
			<?php if($_ds->isIndexable) :?>
				<div style="padding: 5px 0 0 0;">
					<input type="checkbox" name="noIndex" value="true" align="absmiddle" id="noIndex" <?=@$_doc['noindex'] ? 'checked' : ''?>> <label for="noIndex"><?=H_DONT_INDEX?></label>
				</div>
			<?php endif; ?>
			<hr size="2">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" height="24">
				<tr><td align="left">
					<nobr class="tool"><?php
						wysiwygtoolbar('control', array(
							BTN_NEW_DOCUMENT=>array('pic'=>'newdocument','action'=>'window.location.assign("?id=new&ds_id='.$_GET['ds_id'].'")'),
							BTN_SAVE=>array('pic'=>'save','action'=> $save ? 'saveDocument()':''),
						));
					?></nobr>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr height="66%" valign="top">
		<td style="border-width:2px; border-style: groove;">
			<textarea id="txt.html" name="html" style="display:none; width: 100%; height: 100%;"><?= $_GET['id'] != 'new' ? htmlspecialchars($_doc['html'], ENT_COMPAT, 'UTF-8') : ''?></textarea>
			<iframe id="iframe.html" style="border:none; margin:0px;" width="100%" height="100%"></iframe>
			<?php if(WC_USE_SYNTAX):?>
				<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" style="width: 100%; height: 100%;" id="src.html"><?=SyntaxHighlighter::getHtmlEditor()?></object>
			<?php endif;?>
		</td>
	</tr>
	<tr>
		<td style="padding:2px 0px">
			<nobr><input type="checkbox" name="autointro" id="ch.autointro" value="true"> <label for="ch.autointro"><?=F_AUTOGENERATE_INTRO?></label></nobr>
		</td>
	</tr>
	<tr height="33%" valign="top">
		<td style="border-width:2px; border-style: groove;">
			<iframe id="iframe.intro" style="border:none" width="100%" style="margin:0" height="100%"></iframe>
			<textarea id="txt.intro" name="intro" style="display:none; width: 100%; height: 100%;"><?= $_GET['id'] == 'new' ? '' : htmlspecialchars($_doc['intro'], ENT_COMPAT, 'UTF-8')?></textarea>
			<?php if(WC_USE_SYNTAX):?>
				<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" style="width: 100%; height: 100%;" id="src.intro"><?=SyntaxHighlighter::getHtmlEditor()?></object>
			<?php endif;?>
		</td>
	</tr>
</table>
</form>
</body>
</html>