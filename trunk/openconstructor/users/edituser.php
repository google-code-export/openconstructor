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
 * $Id: edituser.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/users._wc');
	require_once(LIBDIR.'/security/user._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$user = &User::load(@$_GET['id']);
	assert($user != null);
	WCS::_request(WCS::decide($user, 'edit') || System::decide('users'));
	$group = &GroupFactory::getGroup($user->groupId);

	$smartybackend->assign_by_ref("group", $group);
	$smartybackend->assign_by_ref("user", $user);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);

	$secret = false;
	if(WCS::decide($user, 'edit.pwd') && $user->id == Authentication::getOriginalUserId()) {		$secret = true;
		loadClass('userfactory', '/security/userfactory._wc');
		$secretQ = UserFactory::getSecretQuestion($user->id);
		$smartybackend->assign("secretQ", $secretQ);
	}
	$smartybackend->assign("secret", $secret);

	$groups = &GroupFactory::getAllGroups();
	$smartybackend->assign("groups", $groups);

	$smartybackend->display('users/edituser.tpl');
?>