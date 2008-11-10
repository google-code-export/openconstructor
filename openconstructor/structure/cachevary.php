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
 * $Id: cachevary.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/structure._wc');
	require_once(LIBDIR.'/site/pagereader._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$pr = &PageReader::getInstance();
	$page = $pr->getPage(@$_GET['id']);
	assert($page != null);
	require_once(LIBDIR.'/site/cachevarysuggest._wc');
	require_once(LIBDIR.'/objmanager._wc');
	$objIds = array_keys($page->getObjects());
	$vary = array();
	foreach($objIds as $id)
		if($page->objects[$id]['block']) {
			$obj = &ObjManager::load($id);
			$vary = array_merge($vary, CacheVarySuggest::suggest($obj));
		}
	asort($vary);
	$vary = array_unique($vary);

	$smartybackend->assign("vary", $vary);

	$smartybackend->display('structure/cachevary.tpl');
?>