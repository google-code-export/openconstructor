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
 * $Id: optimizedb.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	WCS::_assert(Authentication::getUserId() == WCS_ROOT_ID);
		
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/classes._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/setup._wc');
	
	require_once(LIBDIR.'/db/dbsetup._wc');
	$db = &WCDB::bo();
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?=WC.' '.WC_VERSION_FULL?> | <?=H_SETUP_OPTIMIZE_TABLES?></title>
	<link href="setup.css" type="text/css" rel="stylesheet">
</head>
<body style="padding:3%">
<h1><?=H_SETUP_OPTIMIZE_TABLES?></h1>
<ul>
<?php
	$t = DBSetup::getExistingWCTables();
	foreach($t as $table) {
		$query = "OPTIMIZE TABLE `$table`";
		$db->query($query);
		echo "<li>$query ... ".($db->errorCode() > 0 ? '<b>Failed</b>' : 'Ok'); 
	}
?>
</ul>
<a href="index.php"><?=H_SETUP_BACK?></a>
<p><a href="../."><?=WC?></a>
</body>
</html>