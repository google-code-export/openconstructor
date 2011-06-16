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
 * $Id: viewtpl.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/templates._wc');
	assert(isset($_GET['type']));
	require_once(LIBDIR.'/templates/wctemplates._wc');
	$tpls = new WCTemplates();
	$map = $tpls->get_map();
	$types = array();
	foreach($map as $k => $v)
		$types = array_merge($types, current($v));
	$type = $_GET['type'];
	assert(isset($types[$type]));
	require_once(LIBDIR.'/syntax/syntaxhighlighter._wc');
	$tpl = implode('', file(LIBDIR.'/tpl/'.$type.'.tpl'));
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?=VIEW_TEMPLATE.' | '.$types[$type]?></title>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
	<script>
		window.onload = function() {
			var src = document.getElementById("src.tpl");
			src.setSource(document.getElementById("f.tpl").value);
			src.setEditable(false);
		}
	</script>
</head>
<body style="border:groove;border-width:2;margin:0;">
	<textarea id="f.tpl" style="display: none;"><?=htmlspecialchars($tpl, ENT_COMPAT, 'UTF-8')?></textarea>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
		<tr>
			<td style="padding:10px;"><b><?=H_TPL_TYPE?></b>: <?="{$types[$type]} ($type)"?></td>
		</tr>
		<tr height="100%">
			<td style="padding:0 10px 10px;" valign="top">
				<div style=" border-width:2px; border-style: groove; height: 100%;">
					<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" style="width: 100%; height: 100%;" id="src.tpl"><?=SyntaxHighlighter::getHtmlEditor()?></object>
				</div>
			</td>
		</tr>
	</table>
</body>
</html>