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
 * $Id: edittpl.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/templates/wctemplates._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	assert(isset($_GET['id']) == true);
	require_once(LIBDIR.'/security/groupfactory._wc');
	require_once(LIBDIR.'/security/user._wc');
	$multiple = strspn($_GET['id'], '0123456789') < strlen($_GET['id']);
	if(!$multiple) {
		$tpl = &WCTemplates::load($_GET['id']);
		assert($tpl != null);
		$owner = &User::load($tpl->sRes->owner);
		$ownerGroup = &GroupFactory::getGroup($tpl->sRes->group);
	} else {
		$tpl = &WCTemplates::getAggregateTemplate($_GET['id']);
		if($tpl[0]->sRes->owner == $tpl[1]->sRes->owner)
			$owner = &User::load($tpl[0]->sRes->owner);
		if($tpl[0]->sRes->group == $tpl[1]->sRes->group)
			$ownerGroup = &GroupFactory::getGroup($tpl[0]->sRes->group);
	}

	$smartybackend->assign_by_ref("tpl", $tpl);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);

	$smartybackend->assign("multiple", $multiple);
	$smartybackend->assign_by_ref("owner", @$owner);
	$smartybackend->assign_by_ref("ownerGroup", @$ownerGroup);

	$tpl_vars = array('id' => $_GET['id']);
	$smartybackend->assign("tpl_vars", $tpl_vars);

	$authList = array();
	if(!$multiple){
		$sRes = &$tpl->sRes;
		foreach($sRes->actions as $act) {
			$c = 'TPL_'.strtoupper(strtr($act, '.', '_'));
			$title = defined($c) ? constant($c) : $act;
			$authList[] = array('act' => $act, 'title' => $title,
								'ownerBit' => $sRes->getOwnerBit($act) ? true : false,
								'groupBit' => $sRes->getGroupBit($act) ? true : false);
		}
	}
	else{
		$GLOBALS['states'] = array('', H_ALLOW, H_DENY);
		foreach($tpl[0]->sRes->actions as $act){
			$c = 'TPL_'.strtoupper(strtr($act, '.', '_'));
			$title = defined($c) ? constant($c) : $act;
			$authList[] = array('act' => $act, 'title' => $title,
								'ownerStatus' => $tpl[0]->sRes->getOwnerBit($act) ? 1 : (!$tpl[1]->sRes->getOwnerBit($act) ? 2 : 0),
								'groupStatus' => $tpl[0]->sRes->getGroupBit($act) ? 1 : (!$tpl[1]->sRes->getGroupBit($act) ? 2 : 0)
								);
		}
		$smartybackend->assign("states", $GLOBALS['states']);
	}
	$smartybackend->assign("authList", $authList);

	$smartybackend->display('security/edittpl.tpl');
?>