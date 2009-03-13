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
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$group = &GroupFactory::getGroup(@$_GET['id']);
	assert($group != null);
	$smartybackend->assign_by_ref("group", $group);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);

	require_once(LIBDIR.'/security/user._wc');
	$owner = &User::load($group->sRes->owner);
	$ownerGroup = &GroupFactory::getGroup($group->sRes->group);
	$smartybackend->assign_by_ref("owner", $owner);
	$smartybackend->assign_by_ref("ownerGroup", $ownerGroup);

	$sRes = &$group->sRes;
	$authList = array();
	foreach($sRes->actions as $act) {
		$c = 'GROUP_'.strtoupper(strtr($act, '.', '_'));
		$title = defined($c) ? constant($c) : $act;
		$authList[] = array('act' => $act, 'title' => $title,
							'ownerBit' => $sRes->getOwnerBit($act) ? true : false,
							'groupBit' => $sRes->getGroupBit($act) ? true : false);
	}
	$smartybackend->assign("authList", $authList);

	$smartybackend->display('security/editgroup.tpl');
?>