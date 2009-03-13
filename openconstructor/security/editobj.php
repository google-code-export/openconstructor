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
 * $Id: editobj.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/objmanager._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	assert(isset($_GET['id']) == true);
	require_once(LIBDIR.'/security/groupfactory._wc');
	require_once(LIBDIR.'/security/user._wc');
	$multiple = strspn($_GET['id'], '0123456789') < strlen($_GET['id']);
	if(!$multiple) {
		$obj = &ObjManager::load($_GET['id']);
		assert($obj != null);
		$owner = &User::load($obj->sRes->owner);
		$ownerGroup = &GroupFactory::getGroup($obj->sRes->group);
	} else {
		$obj = &ObjManager::getAggregateObject($_GET['id']);
		if($obj[0]->sRes->owner == $obj[1]->sRes->owner)
			$owner = &User::load($obj[0]->sRes->owner);
		if($obj[0]->sRes->group == $obj[1]->sRes->group)
			$ownerGroup = &GroupFactory::getGroup($obj[0]->sRes->group);
	}

	$smartybackend->assign_by_ref("obj", $obj);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);

	$smartybackend->assign("multiple", $multiple);
	$smartybackend->assign_by_ref("owner", @$owner);
	$smartybackend->assign_by_ref("ownerGroup", @$ownerGroup);

	$tpl_vars = array('id' => $_GET['id']);
	$smartybackend->assign("tpl_vars", $tpl_vars);

	$authList = array();
	if(!$multiple){
		$sRes = &$obj->sRes;
		foreach($sRes->actions as $act) {
			$c = 'OBJ_'.strtoupper(strtr($act, '.', '_'));
			$title = defined($c) ? constant($c) : $act;
			$authList[] = array('act' => $act, 'title' => $title,
								'ownerBit' => $sRes->getOwnerBit($act) ? true : false,
								'groupBit' => $sRes->getGroupBit($act) ? true : false);
		}
	}
	else{
		$GLOBALS['states'] = array('', H_ALLOW, H_DENY);
		foreach($obj[0]->sRes->actions as $act){
			$c = 'OBJ_'.strtoupper(strtr($act, '.', '_'));
			$title = defined($c) ? constant($c) : $act;
			$authList[] = array('act' => $act, 'title' => $title,
								'ownerStatus' => $obj[0]->sRes->getOwnerBit($act) ? 1 : (!$obj[1]->sRes->getOwnerBit($act) ? 2 : 0),
								'groupStatus' => $obj[0]->sRes->getGroupBit($act) ? 1 : (!$obj[1]->sRes->getGroupBit($act) ? 2 : 0)
								);
		}
		$smartybackend->assign("states", $GLOBALS['states']);
	}
	$smartybackend->assign("authList", $authList);

	$smartybackend->display('security/editobj.tpl');
?>