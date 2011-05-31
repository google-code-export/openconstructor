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
 * $Id: i_security.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
WCS::requireAuthentication();
require_once(LIBDIR.'/security/groupfactory._wc');
require_once(LIBDIR.'/security/user._wc');
$owner = &User::load(@$_POST['owner']);
$group = &GroupFactory::getGroupByName(@$_POST['ownerGroup']);
switch(@$_POST['action'])
{
	case 'edit_ds':
		require_once(LIBDIR.'/dsmanager._wc');
		$dsm = new DSManager();
		$ds = $dsm->load(@$_POST['ds_id']);
		assert($ds != null);
		if($owner != null)
			$ds->sRes->setOwner($owner->id);
		if($group != null)
			$ds->sRes->setGroup($group->id);
		$ds->sRes->setAuthorities(0, 0);
		foreach($ds->sRes->actions as $act) {
			$ds->sRes->setOwnerBit($act, isset($_POST['oAuths'][$act]));
			$ds->sRes->setGroupBit($act, isset($_POST['gAuths'][$act]));
		}
		$doc = $ds->wrapDocument($rec = array());
		$doc->sRes->setAuthorities(0, 0);
		foreach($doc->sRes->actions as $act)
			$doc->sRes->setOwnerBit($act, isset($_POST['docAuths'][$act]));
		$ds->docAuths = $doc->sRes->getOwnerAuths();
		$ds->updateAuths();
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'edit_doc':
		require_once(LIBDIR.'/dsmanager._wc');
		$dsm = new DSManager();
		$ds = $dsm->load(@$_POST['ds_id']);
		assert($ds != null && @$_POST['doc_id']);
		if($owner != null)
			$ds->updateDocAuths($_POST['doc_id'], $owner->id);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'edit_enum':
		require_once(LIBDIR.'/enum/wcenumfactory._wc');
		$ef = &WCEnumFactory::getInstance();
		$enum = $ef->load(@$_POST['enum_id']);
		assert($enum != null);
		if($owner != null)
			$enum->sRes->setOwner($owner->id);
		if($group != null)
			$enum->sRes->setGroup($group->id);
		$enum->sRes->setAuthorities(0, 0);
		foreach($enum->sRes->actions as $act) {
			$enum->sRes->setOwnerBit($act, isset($_POST['oAuths'][$act]));
			$enum->sRes->setGroupBit($act, isset($_POST['gAuths'][$act]));
		}
		$ef->updateAuths($enum);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'edit_group':
		$gf = &GroupFactory::getInstance();
		$gr = $gf->getGroup(@$_POST['group_id']);
		assert($gr != null);
		if($owner != null)
			$gr->sRes->setOwner($owner->id);
		if($group != null)
			$gr->sRes->setGroup($group->id);
		$gr->sRes->setAuthorities(0, 0);
		foreach($gr->sRes->actions as $act) {
			$gr->sRes->setOwnerBit($act, isset($_POST['oAuths'][$act]));
			$gr->sRes->setGroupBit($act, isset($_POST['gAuths'][$act]));
		}
		$gf->updateAuths($gr);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'edit_user':
		require_once(LIBDIR.'/security/userfactory._wc');
		$uf = &UserFactory::getInstance();
		$multiple = strspn(@$_POST['user_id'], '0123456789') < strlen(@$_POST['user_id']);
		if($multiple) {
			assert(@$_POST['user_id'] > 0);
			$user = &User::load(WCS_ROOT_ID);
			$user->sRes->setAuthorities(0, 0);
			$setSRes = $user->sRes->copy();
			$unsetSRes = $user->sRes->copy();
			foreach($user->sRes->actions as $act) {
				$setSRes->setOwnerBit($act, @$_POST['oAuths'][$act] == 1);
				$setSRes->setGroupBit($act, @$_POST['gAuths'][$act] == 1);
				$unsetSRes->setOwnerBit($act, @$_POST['oAuths'][$act] == 2 ? false : true);
				$unsetSRes->setGroupBit($act, @$_POST['gAuths'][$act] == 2 ? false : true);
			}
			$uf->updateUsersAuths($_POST['user_id'], $setSRes, $unsetSRes);
		} else {
			$user = &User::load(@$_POST['user_id']);
			assert($user != null);
			$user->sRes->setAuthorities(0, 0);
			foreach($user->sRes->actions as $act) {
				$user->sRes->setOwnerBit($act, isset($_POST['oAuths'][$act]));
				$user->sRes->setGroupBit($act, isset($_POST['gAuths'][$act]));
			}
			$uf->updateAuths($user);
		}
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'edit_object':
		require_once(LIBDIR.'/objmanager._wc');
		$multiple = strspn(@$_POST['obj_id'], '0123456789') < strlen(@$_POST['obj_id']);
		if($multiple) {
			assert(@$_POST['obj_id'] > 0);
			$obj = &ObjManager::load((int) $_POST['obj_id']);
			$obj->sRes->setAuthorities(0, 0);
			$obj->sRes->setOwner(0); $obj->sRes->setGroup(0);
			$setSRes = $obj->sRes->copy();
			$unsetSRes = $obj->sRes->copy();
			foreach($obj->sRes->actions as $act) {
				$setSRes->setOwnerBit($act, @$_POST['oAuths'][$act] == 1);
				$setSRes->setGroupBit($act, @$_POST['gAuths'][$act] == 1);
				$unsetSRes->setOwnerBit($act, @$_POST['oAuths'][$act] == 2 ? false : true);
				$unsetSRes->setGroupBit($act, @$_POST['gAuths'][$act] == 2 ? false : true);
			}
			if($owner != null)
				$setSRes->setOwner($owner->id);
			if($group != null)
				$setSRes->setGroup($group->id);
			ObjManager::updateObjectsAuths($_POST['obj_id'], $setSRes, $unsetSRes);
		} else {
			$obj = &ObjManager::load(@$_POST['obj_id']);
			assert($obj != null);
			if($owner != null)
				$obj->sRes->setOwner($owner->id);
			if($group != null)
				$obj->sRes->setGroup($group->id);
			$obj->sRes->setAuthorities(0, 0);
			foreach($obj->sRes->actions as $act) {
				$obj->sRes->setOwnerBit($act, isset($_POST['oAuths'][$act]));
				$obj->sRes->setGroupBit($act, isset($_POST['gAuths'][$act]));
			}
			ObjManager::updateAuths($obj);
		}
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'edit_template':
		require_once(LIBDIR.'/templates/wctemplates._wc');
		$multiple = strspn(@$_POST['tpl_id'], '0123456789') < strlen(@$_POST['tpl_id']);
		if($multiple) {
			assert(@$_POST['tpl_id'] > 0);
			$tpl = &WCTemplates::load((int) $_POST['tpl_id']);
			$tpl->sRes->setAuthorities(0, 0);
			$tpl->sRes->setOwner(0); $tpl->sRes->setGroup(0);
			$setSRes = $tpl->sRes->copy();
			$unsetSRes = $tpl->sRes->copy();
			foreach($tpl->sRes->actions as $act) {
				$setSRes->setOwnerBit($act, @$_POST['oAuths'][$act] == 1);
				$setSRes->setGroupBit($act, @$_POST['gAuths'][$act] == 1);
				$unsetSRes->setOwnerBit($act, @$_POST['oAuths'][$act] == 2 ? false : true);
				$unsetSRes->setGroupBit($act, @$_POST['gAuths'][$act] == 2 ? false : true);
			}
			if($owner != null)
				$setSRes->setOwner($owner->id);
			if($group != null)
				$setSRes->setGroup($group->id);
			WCTemplates::updateTemplatesAuths($_POST['tpl_id'], $setSRes, $unsetSRes);
		} else {
			$tpl = &WCTemplates::load(@$_POST['tpl_id']);
			assert($tpl != null);
			if($owner != null)
				$tpl->sRes->setOwner($owner->id);
			if($group != null)
				$tpl->sRes->setGroup($group->id);
			$tpl->sRes->setAuthorities(0, 0);
			foreach($tpl->sRes->actions as $act) {
				$tpl->sRes->setOwnerBit($act, isset($_POST['oAuths'][$act]));
				$tpl->sRes->setGroupBit($act, isset($_POST['gAuths'][$act]));
			}
			WCTemplates::updateAuths($tpl);
		}
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'edit_tree':
		require_once(LIBDIR.'/tree/sqltree._wc');
		$node = &SqlTreeReader::getRootNode(@$_POST['node_id']);
		assert($node != null && $node->id == @$_POST['node_id'] && $node->id != 1);
		if($owner != null)
			$node->sRes->setOwner($owner->id);
		if($group != null)
			$node->sRes->setGroup($group->id);
		$node->sRes->setAuthorities(0, 0);
		foreach($node->sRes->actions as $act) {
			$node->sRes->setOwnerBit($act, isset($_POST['oAuths'][$act]));
			$node->sRes->setGroupBit($act, isset($_POST['gAuths'][$act]));
		}
		$sqltree = new SqlTree();
		$sqltree->updateAuths($node);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'edit_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = &PageFactory::getInstance();
		$page = $pf->getPage(@$_POST['page_id']);
		assert($page != null);
		if($owner != null)
			$page->sRes->setOwner($owner->id);
		if($group != null)
			$page->sRes->setGroup($group->id);
		$page->sRes->setAuthorities(0, 0);
		foreach($page->sRes->actions as $act) {
			$page->sRes->setOwnerBit($act, isset($_POST['oAuths'][$act]));
			$page->sRes->setGroupBit($act, isset($_POST['gAuths'][$act]));
		}
		$pf->updateAuths($page);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	default:
		assert(true == false);
	break;
}
?>
