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
 * $Id: images.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	$pageSize = 50;
	function &get_images($keyword, $page, $size) {
		$result = array();
		settype($page, 'int');
		if(--$page < 0) $page = 0;
		$offset = $page * $size;
		$lkeyword = addslashes(str_replace('%', '%%', $keyword));
		$skeyword = addslashes($keyword);
		$db = WCDB::bo();
		$res = $db->query(
			'SELECT f.name, f.filename, d.name as dsname'.
			' FROM datasources d, dsfile f'.
			' WHERE f.ds_id = d.ds_id AND d.internal = 0 AND'.
			"  (f.name LIKE '%$lkeyword%' OR f.basename = '$skeyword' OR d.name LIKE '%$lkeyword%')".
			'  AND f.type IN ("jpeg", "jpg", "gif", "png")'.
			" LIMIT $offset, $size"
		);
		for($i = 0, $l = mysql_num_rows($res); $i < $l; $i++)
			$result[] = mysql_fetch_assoc($res);
		mysql_free_result($res);
		return $result;
	}
	if(isset($_GET['keyword']))
		$hl = get_images($_GET['keyword'], 1, $pageSize);
?>
<html>
<head>
<title>Images</title>
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
			echo '<select size="10" id="s.docs" style="display:none">';
			for($i = 0, $l = sizeof($hl); $i < $l; $i++)
				echo '<option value="'.$hl[$i]['filename'].'" dsname="'.htmlspecialchars(htmlspecialchars($hl[$i]['dsname'], ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8').'">'.$hl[$i]['name'];
			echo '<select>';
?>
	<div id="docs"></div>
	<script>
		window.holder = window.dialogArguments.length > 0 ? window.dialogArguments[0].parent : window.parent;
		try{
			window.parent.enableSelect(true);
		} catch(e){}
		var select = document.getElementById("s.docs");
		function preview(a, index) {
			var div = document.getElementById("pr" + index);
			var img = a.href;
			if(div.innerHTML)
				return;
			div.style.cssText = "padding:5px;";
			div.innerHTML = "<img src='" + img + "' align='absmiddle' style='border: dashed 1px red'>";
			div.childNodes[0].title = a.title;
			a.removeNode();
		}
		function doclink(index) {
			var url = "", title;
			title = "<?=H_IMG_FILENAME?>: " + select.options[index].value + "\n" + "<?=H_IMG_DS?>: " + select.options[index].dsname;
			return "<a href='" + select.options[index].value + "' onclick='preview(this," + index + ");return false;' title='" + title + "'>" + select.options[index].innerHTML + "</a> <div id='pr" + index + "'></div>";
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