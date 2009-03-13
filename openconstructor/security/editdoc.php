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
 * $Id: editdoc.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/dsmanager._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$dsm = new DSManager();
	$ds = $dsm->load(@$_GET['ds_id']);
	assert($ds != null && @$_GET['id']);
	$smartybackend->assign_by_ref("ds", $ds);

	$multiple = strspn($_GET['id'], '0123456789') < strlen($_GET['id']);
	require_once(LIBDIR.'/security/user._wc');
	if(!$multiple) {
		$doc = $ds->getDocument($_GET['id']);
		assert($doc != null);
		$owner = &User::load($doc->sRes->owner);
		$smartybackend->assign_by_ref("doc", $doc);
	} else
		$owner = &User::load($ds->getDocOwner($_GET['id']));


	$Authentication = new Authentication();
	$smartybackend->assign_by_ref("Authentication", $Authentication);
	$smartybackend->assign("multiple", $multiple);
	$smartybackend->assign_by_ref("owner", $owner);

	$tpl_vars = array('id' => $_GET['id']);
	$smartybackend->assign("tpl_vars", $tpl_vars);

	$smartybackend->display('security/editdoc.tpl');
?>