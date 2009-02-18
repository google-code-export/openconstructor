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
 * $Id: editgroup.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('users');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/users._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$gf = &GroupFactory::getInstance();
	$group = $gf->getGroup(@$_GET['group_id']);
	assert($group != null);

	$smartybackend->assign_by_ref("group", $group);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);

	$dis = !WCS::privileged(Authentication::getInstance()) ? 'disabled' : '';
	$smartybackend->assign("dis", $dis);

	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = & new DSManager();
	$ds = $dsm->getAll('hybrid');
	$smartybackend->assign("ds", $ds);

	$sRes = $sys->sRes;
	$sRes->setAuthorities($sRes->getOwnerAuths(), $group->auths);
	$powers = array();
	foreach($sRes->actions as $action) {
		$state = $sRes->getGroupBit($action) ? true : false;
		$c = 'SYS_'.strtoupper(strtr($action, '.', '_'));
		$title = defined($c) ? constant($c) : $action;
		$level = substr_count($action,'.') + 1;
		$powers[] = array('name' => $action, 'title' => $title, 'level' => $level, 'state' => $state);
	}
	$smartybackend->assign("powers", $powers);

	$smartybackend->display('users/editgroup.tpl');
?>