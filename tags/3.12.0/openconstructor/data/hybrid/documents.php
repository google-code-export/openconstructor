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
 * $Id: documents.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	require_once(ROOT.WCHOME.'/include/headlines._wc');
	$pageSize = 50;
	if(isset($_GET['keyword']))
		switch(@$_GET['doctype']) {
			case 'publication':
			case 'event':
			case 'gallery':
				list($items, $hl) = get_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'header', $pageSize, 1, get_clause(@$_GET['keyword'], 'header, content'));
			break;
			case 'hybrid':
				list($items, $hl) = get_hybrid_headline(intval(@$_GET['ds_id']), true, false, $pageSize, 1, get_clause(@$_GET['keyword'], 'header'));
			break;
			case 'article':
				list($items, $hl) = get_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'header', $pageSize, 1, get_clause(@$_GET['keyword'], 'header, intro'));
			break;
			case 'textpool':
				list($items, $hl) = get_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'header', $pageSize, 1, get_clause(@$_GET['keyword'], 'header, html'));
			break;
			case 'guestbook':
				list($items, $hl) = get_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'subject as header', $pageSize, 1, get_clause(@$_GET['keyword'], 'subject, html'));
			break;
			case 'file':
				list($items, $hl) = get_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'name as header', $pageSize, 1, get_clause(@$_GET['keyword'], 'name, description, filename'));
			break;
			default:
				assert(true == false); // Invalid type of document;
		}
?>
<html>
<head>
<title>Documents</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Pragma" content="no-cache">
	<script src="/openconstructor/lib/js/base.js"></script>
	<script src="/openconstructor/lib/js/widgets.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	</script>
	<style>
		BODY {border:none;padding:0;margin:0;font:normal 13px arial;}
		TABLE {font-size:100%;}
		A {color:#06c;}
		#docs {height:100%;}
		.docs {width:100%;}
		.docs TR.r1 {background:#f2f2f2;}
		.docs TR.r0 {background:#fff;}
		.docs TD {padding:4px 6px 5px;border-bottom:solid 1px #f8f8f8;}
		.docs TD.input {width:20px;}
	</style>
</head>
<body>
<?php
	if(isset($_GET['keyword'])){ 
		if(sizeof($hl) > 0) {
			echo '<select size="10"'.(@$_GET['type']=='multiple'?' multiple':'').' id="s.docs" style="display:none">';
			foreach($hl as $id=>$v)
				echo '<option value="'.$id.'">'.$v['header'];
			echo '<select>';
?>
	<div id="docs"></div>
	<script>
		window.holder = window.dialogArguments.length > 0 ? window.dialogArguments[0].parent : window.parent;
		try{
			window.parent.enableSelect(true);
		} catch(e){}
		var select = document.getElementById("s.docs");
		function doclink(index) {
			var url = "<?=WCHOME.'/data/'.$_GET['doctype']?>/edit.php?id=" + select.options[index].value + "&ds_id=<?=$_GET['ds_id']?>";
			return "<a href='" + url + "' onclick='window.holder.wxyopen(this.href, 800, 500);return false;'>" + select.options[index].innerHTML + "</a>";
		}
		var table = widgetUtils.createTableFromSelect(select, doclink);
		table.className = "docs";
		table.cellPadding = 0;
		table.cellSpacing = 0;
		document.getElementById("docs").appendChild(table);
		
	</script>
<?php
		} else
			echo '<table width="100%" height="100%" style="font-size:110%;"><tr><td align="center">'.H_NO_RESULTS_FOR_KEYWORD.' "'.@$_GET['keyword'].'"</td></tr></table>';
	} else
		echo '<table width="100%" height="100%" style="font-size:110%;"><tr><td align="center">'.H_INPUT_KEYWORD.'</td></tr></table>';
?>
</body>
</html>