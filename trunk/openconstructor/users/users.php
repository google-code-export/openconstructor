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

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	if(isset($_GET['keyword'])) {
		$db = &WCDB::bo();
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
	$smartybackend->assign("users", @$users);

	$tpls_vars = array('keyword' => @$_GET['keyword'], 'type' => @$_GET['type']);
	$smartybackend->assign("tpls_vars", $tpls_vars);

	$smartybackend->display('users/users.tpl');
?>