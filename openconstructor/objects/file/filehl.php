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
 * $Id: filehl.php,v 1.11 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');


	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$obj = &ObjManager::load(@$_GET['id']);
	assert($obj != null);

	$smartybackend->assign_by_ref("obj", $obj);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);
	include('../select_tpl._wc');
	include('../select_data._wc');
	$fields = array(
		FHL_SORT_NONE=>PR_FILE_SORT_NONE,
		FHL_SORT_NAME=>PR_FILE_SORT_NAME,
		FHL_SORT_BASENAME=>PR_FILE_SORT_BASENAME,
		FHL_SORT_EXT=>PR_FILE_SORT_EXT,
		FHL_SORT_SIZE=>PR_FILE_SORT_SIZE,
		FHL_SORT_CREATED=>PR_FILE_SORT_CREATED,
		FHL_SORT_UPDATED=>PR_FILE_SORT_UPDATED
	);
	$smartybackend->assign("fields", $fields);

	$smartybackend->display('objects/file/filehl.tpl');
?>