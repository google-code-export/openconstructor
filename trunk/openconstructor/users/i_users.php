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
 * $Id: i_users.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();

switch(@$_POST['action'])
{
	case 'edit_group':
		require_once(LIBDIR.'/security/groupfactory._wc');
		$gf = &GroupFactory::getInstance();
		$group = $gf->getGroup(@$_POST['group_id']);
		assert($group->id > 0);
		$group->title = @$_POST['name'];
		$group->profileType = (int) @$_POST['profileType'];
		$group->umask = @$_POST['umask'];
		$sRes = $sys->sRes;
		$sRes->setAuthorities(0, 0);
		if(is_array(@$_POST['act']))
			foreach($_POST['act'] as $act => $j)
				$sRes->setGroupBit($act, true);
		$group->auths = $sRes->getGroupAuths();
		$gf->updateGroup($group);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;

	case 'add_group':
		require_once(LIBDIR.'/security/groupfactory._wc');
		$gf = &GroupFactory::getInstance();
		$group = new Group(@$_POST['key'], @$_POST['name']);
		$gf->createGroup($group);
		if($group->id)
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.location.href="editgroup.php?group_id='.$group->id.'";</script>Success';
	break;

	case 'remove_group':
		require_once(LIBDIR.'/security/groupfactory._wc');
		$gf = &GroupFactory::getInstance();
		$gf->removeGroup(@$_POST['group_id']);
//		header('Location: http://'.$_host.WCHOME.'/users/');
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/users/">');
	break;

	case 'remove_users':
		require_once(LIBDIR.'/security/userfactory._wc');
		UserFactory::removeUser(@$_POST['ids']);
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'enable_users':
		require_once(LIBDIR.'/security/userfactory._wc');
		UserFactory::setUserState(@$_POST['ids'], 1);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'disable_users':
		require_once(LIBDIR.'/security/userfactory._wc');
		UserFactory::setUserState(@$_POST['ids'], 0);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'edit_user':
		require_once(LIBDIR.'/security/userfactory._wc');
		$user = &User::load(@$_POST['login']);
		assert($user != null);
		$uf = &UserFactory::getInstance();
		$user->name = @$_POST['name'];
		$user->email = @$_POST['email'];
		$user->active = !isset($_POST['isDisabled']);
		$user->expiry = @$_POST['expiry'] ? strtotime($_POST['expiry']) : 0;
		if(@$_POST['newpassword'])
			$user->pwd = (string) @$_POST['password1'];
		if(@$_POST['groupId'] > 0)
			$user->groupId = (int) $_POST['groupId'];
		if($uf->updateUser($user)) {
			$uf->setUserMembership($user, (array) @$_POST['membership']);
			if($user->id == Authentication::getOriginalUserId()) {
				$a = &Authentication::create($user);
				$a->fetchProfile();
				$a->exportToSession();
			}
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
	break;

	case 'add_user':
		require_once(LIBDIR.'/security/userfactory._wc');
		$user = new User(@$_POST['login'], @$_POST['name']);
		$user->pwd = @$_POST['password1'];
		$uf = &UserFactory::getInstance();
		$uf->createUser(@$_POST['group_id'], $user);
		if($user->id)
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.location.href="edituser.php?id='.$user->id.'";</script>Success';
	break;

	case 'remove_member':
		require_once(LIBDIR.'/security/groupfactory._wc');
		$group = &GroupFactory::getGroup($_POST['group_id']);
		assert($group != null);
		$group->removeMember(@$_POST['ids']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'add_member':
		require_once(LIBDIR.'/security/groupfactory._wc');
		$group = &GroupFactory::getGroup($_POST['group_id']);
		assert($group != null);
		$group->addMember(@$_POST['ids']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'edit_secrets':
		require_once(LIBDIR.'/security/userfactory._wc');
		$user = &User::load(@$_POST['id']);
		assert($user != null);
		UserFactory::updateSecrets($user->id, @$_POST['pwd'], @$_POST['secretQ'], @$_POST['secretA']);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;

	case 'view_detail':
		foreach((array) @$_COOKIE['vd'] as $key => $val){
			if(!array_key_exists($key, (array) @$_POST['vdetail']))
				setcookie('vd['.$key.']', '', time() - 3600, WCHOME.'/users/');
		}
		foreach($_POST['vdetail'] as $key => $val)
			setcookie('vd['.$key.']', $key, 0, WCHOME.'/users/');
		setcookie('pagesize', $_POST['pagesize'], 0, WCHOME.'/users/');
		setcookie('vd[_touched]', '_touched', 0, WCHOME.'/users/');
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;

	default:
	break;
}
?>
