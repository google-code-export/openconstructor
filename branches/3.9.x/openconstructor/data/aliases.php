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
 * $Id: aliases.php,v 1.11 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	
	require_once(ROOT.WCHOME.'/include/headlines._wc');
	$pageSize = 50;
	if(isset($_GET['keyword']))
		switch(@$_GET['doctype']) {
			case 'publication':
			case 'event':
			case 'gallery':
				list($items, $hl) = get_alias_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'ds_id,header', $pageSize, 1, get_clause(@$_GET['keyword'], 'd1.header, d1.content, d.name'));
			break;
			case 'article':
				list($items, $hl) = get_alias_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'ds_id,header', $pageSize, 1, get_clause(@$_GET['keyword'], 'd1.header, d1.intro, d.name'));
			break;
			case 'textpool':
				list($items, $hl) = get_alias_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'ds_id,header', $pageSize, 1, get_clause(@$_GET['keyword'], 'd1.header, d1.html, d.name'));
			break;
			case 'guestbook':
				list($items, $hl) = get_alias_headline('ds'.$_GET['doctype'], intval(@$_GET['ds_id']), 'ds_id,subject as header', $pageSize, 1, get_clause(@$_GET['keyword'], 'd1.subject, d1.html, d.name'));
			break;
			default:
				assert(true == false);// Invalid type of document
		}
	function get_alias_headline($table, $ds_id, $fields, $pagesize, $page = 1, $searchclause = ''){
		$result = array(0, array());
		$db = &WCDB::bo();
		$page = intval($page);
		if(--$page < 0)
			$page = 0;
		$query = 
			'SELECT SQL_CALC_FOUND_ROWS d1.id, d1.date, d.name as dsName, '.preg_replace('/(^|,)\s*([A-Za-z0-9_])/u', '$1d1.$2', $fields).
			' FROM datasources d, '.$table.' d1 LEFT JOIN '.$table.' d2 ON(d1.id=d2.real_id AND d2.ds_id='.$ds_id.')'.
			' WHERE d.ds_id!='.$ds_id.' AND d.ds_id=d1.ds_id '.$searchclause.
			' AND d2.real_id IS NULL'.
			' ORDER BY d1.date DESC'.
			' LIMIT '.($page * $pagesize).','.$pagesize;
		$res = $db->query($query);
		if(mysql_num_rows($res) > 0) {
			$hl = array();
			$r = $db->query('SELECT FOUND_ROWS()');
			list($result[0]) = mysql_fetch_row($r);
			mysql_free_result($r);
			
			$fields = explode(',', strtolower($fields));
			for($i = 0, $l = sizeof($fields); $i < $l; $i++)
				if(utf8_strpos($fields[$i], ' as ') > 0)
					list($tmp, $fields[$i]) = explode(' as ', $fields[$i]);
			while($row = mysql_fetch_assoc($res)) {
				for($i = 0; $i < $l; $i++)
					$hl[$row['id']][$fields[$i]] = $row[$fields[$i]];
				$hl[$row['id']]['date'] = date('j M Y', ($t = strtotime($row['date'])) != -1 ? $t : $row['date']);
				$hl[$row['id']]['dsName'] = $row['dsName'];
			}
			$result[1] = &$hl;
		}
		mysql_free_result($res);
		return $result;
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
			echo '<select size="10"'.(@$_GET['type']=='multiple'?' multiple':'').' id="s.docs" style="display:none;">';
			foreach($hl as $id=>$v)
				echo '<option value="'.$id.'" dsId="'.$v['ds_id'].'" dsName="'.$v['dsName'].'">'.$v['header'];
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
			var url = "<?=WCHOME.'/data/'.$_GET['doctype']?>/edit.php?id=" + select.options[index].value + "&ds_id=" + select.options[index].getAttribute('dsId');
			return "<a href='" + url + "' onclick='window.holder.wxyopen(this.href, 800, 500);return false;' "
			+ "title='<?=H_DS_NAME?>: " + select.options[index].getAttribute('dsName') + "'>"
			+ select.options[index].innerHTML + "</a>";
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
		echo '<table width="100%" height="100%" style="font-size:110%;"><tr><td align="center">'.SEARCH_FOR_KEYWORD.'</td></tr></table>';
?>
</body>
</html>