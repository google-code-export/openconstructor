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
 * $Id: users.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/users._wc');
	require_once(ROOT.WCHOME.'/include/headlines._wc');
	if(isset($_GET['keyword'])) {
		$db = WCDB::bo();
		$keyword = addslashes(utf8_strtolower($_GET['keyword']));
		$likeKeyword = '%'.str_replace('%','%%', $keyword).'%';
		$res = $db->query(
			'SELECT u.id, u.login, u.name, u.active, u.email'.
			' FROM wcsusers u, wcsgroups g'.
			' WHERE u.group_id = g.id '.
			"  AND (login = '$keyword' OR u.name LIKE '$likeKeyword' OR g.name = '$keyword' OR u.email = '$keyword')"
		);
		for($i = 0, $l = mysql_num_rows($res); $i < $l; $i++)
			$users[$i] = mysql_fetch_assoc($res);
		mysql_free_result($res);
	}
?>
<html>
<head>
<title>Users</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Pragma" content="no-cache">
	<script src="../lib/js/base.js"></script>
	<script src="../lib/js/widgets.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	</script>
	<style>
		BODY {border:none;padding:0;margin:0;font:normal 13px arial;}
		TABLE {font-size:100%;}
		A {color:#06c;}
		#users {height:100%;}
		.users {width:100%;}
		.users TR.r1 {background:#f2f2f2;}
		.users TR.r0 {background:#fff;}
		.users TD {padding:4px 6px 5px;border-bottom:solid 1px #f8f8f8;}
		.users TD.input {width:20px;}
		A.gray {color:#666;}
	</style>
</head>
<body>
<?php
	if(isset($_GET['keyword'])){ 
		if(sizeof(@$users) > 0) {
			echo '<select size="10"'.(@$_GET['type']=='multiple'?' multiple':'').' id="s.users" style="display:none">';
			for($i = 0, $l = sizeof($users); $i < $l; $i++)
				echo '<option value="'.$users[$i]['id'].'" active="'.intval($users[$i]['active']).'" login="'.$users[$i]['login'].'" email="'.htmlspecialchars(htmlspecialchars($users[$i]['email'], ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8').'">'.$users[$i]['name'];
			echo '<select>';
?>
	<div id="users"></div>
	<script>
		window.holder = window.dialogArguments.length > 0 ? window.dialogArguments[0].parent : window.parent;
		try{
			window.parent.enableSelect(true);
		} catch(e){}
		var select = document.getElementById("s.users");
		function doclink(index) {
			var url = "<?=WCHOME?>/users/edituser.php?id=" + select.options[index].value;
			return "<a href='" + url + "' onclick='window.holder.wxyopen(this.href, 600, 500);return false;' "
				+ (select.options[index].getAttribute('active') == 1 ? "" : "class='gray'")
				+ " title='<?=USR_LOGIN?>: " + select.options[index].getAttribute("login") + "\n<?=USR_EMAIL?>: "
				+ select.options[index].getAttribute("email") + "'"
				+ ">" + select.options[index].innerHTML
				+ "</a>";
		}
		var table = widgetUtils.createTableFromSelect(select, doclink);
		table.className = "users";
		table.cellPadding = 0;
		table.cellSpacing = 0;
		document.getElementById("users").appendChild(table);
		
	</script>
<?php
		} else
			echo '<table width="100%" height="100%" style="font-size:110%;"><tr><td align="center">'.H_NO_USERS_FOR_KEYWORD.' "'.@$_GET['keyword'].'"</td></tr></table>';
	} else
		echo '<table width="100%" height="100%" style="font-size:110%;"><tr><td align="center">'.H_ENTER_USR_KEYWORD.'</td></tr></table>';
?>
</body>
</html>