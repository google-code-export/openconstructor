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
 * $Id: edituser.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');
	require_once(LIBDIR.'/security/userfactory._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	assert(isset($_GET['id']) == true);
	$multiple = strspn($_GET['id'], '0123456789') < strlen($_GET['id']);
	if(!$multiple) {
		$owner = &User::load($_GET['id']);
		$ownerGroup = &GroupFactory::getGroup($owner->groupId);
	} else {
		$owner = null;
		$ownerGroup = null;
		$user = &UserFactory::getAggregateUser($_GET['id']);
	}
	assert($multiple ? $user != null : $owner != null && $ownerGroup != null);

	$smartybackend->assign_by_ref("owner", $owner);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);
	$smartybackend->assign_by_ref("ownerGroup", $ownerGroup);

	$tpl_vars = array('id' => $_GET['id']);
	$smartybackend->assign("tpl_vars", $tpl_vars);

	$smartybackend->assign("multiple", $multiple);

	$authList = array();
	if(!$multiple){
		$sRes = &$owner->sRes;
		foreach($sRes->actions as $act) {
			$c = 'USER_'.strtoupper(strtr($act, '.', '_'));
			$title = defined($c) ? constant($c) : $act;
			$authList[] = array('act' => $act, 'title' => $title,
								'ownerBit' => $sRes->getOwnerBit($act) ? true : false,
								'groupBit' => $sRes->getGroupBit($act) ? true : false);
		}
	}
	else{
		$GLOBALS['states'] = array('', H_ALLOW, H_DENY);
		foreach($user[0]->sRes->actions as $act){
			$c = 'USER_'.strtoupper(strtr($act, '.', '_'));
			$title = defined($c) ? constant($c) : $act;
			$authList[] = array('act' => $act, 'title' => $title,
								'ownerStatus' => $user[0]->sRes->getOwnerBit($act) ? 1 : (!$user[1]->sRes->getOwnerBit($act) ? 2 : 0),
								'groupStatus' => $user[0]->sRes->getGroupBit($act) ? 1 : (!$user[1]->sRes->getGroupBit($act) ? 2 : 0)
								);
		}
		$smartybackend->assign("states", $GLOBALS['states']);
	}
	$smartybackend->assign("authList", $authList);

	$smartybackend->display('security/edituser.tpl');
?>