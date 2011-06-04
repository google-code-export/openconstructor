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
 * $Id: i_structure.php,v 1.13 2007/03/02 21:11:17 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();

switch(@$_POST['action'])
{
	case 'create_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$id = $pf->createPage(@$_POST['parent_id'], @$_POST['page_name'], @$_POST['header']);
		if($id > 0)
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.location.href="edit_page.php?node='.$id.'"</script>Succesfully created!';
	break;

	case 'remove_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		if($pf->removePage(@$_POST['page_id']))
			die('<meta http-equiv="Refresh" content="0; URL=/openconstructor/structure/"/>');
	break;

	case 'add_object':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		if($pf->addObject(@$_POST['uri_id'], @$_POST['obj_id']))
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.close();</script>';
	break;

	case 'remove_objects':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		if($pf->removeObject(@$_POST['uri_id'], @$_POST['ids']))
			die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'save_blocks':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$page = $pf->getPage(@$_POST['uri_id']);
		assert($page != null);

		$result = true;

		if(WCS::decide($page, 'editpage') && $page->tpl != intval(@$_POST['tplId'])) {
			$page->tpl = (int) @$_POST['tplId'];
			$result = $result && $pf->updatePage($page);
		}

		if(isset($_POST['block'])) {
			$page->getObjects();
			foreach((array) $_POST['block'] as $id => $pos) {
				$isObserver = substr($pos, 0, 1) == '@';
				$block = $isObserver ? substr($pos, 1) : $pos;
				$isCrumbs = isset($_POST['crumbs'][$id]);
				$page->setObjectProps($id, $block == '+' ? null : $block, $isObserver, $isCrumbs);
			}
			$result = $result && $pf->updateBlocks($page);
		}
		if($result)
			die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'edit_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$page = $pf->getPage(@$_POST['uri_id']);
		assert($page != null && trim(@$_POST['header']) != '');

		if(isset($_POST['uri_name']))
			$page->name = @$_POST['uri_name'];
		$page->header = $_POST['header'];
		$page->title = @$_POST['title'];
		$page->addTitle = @$_POST['appendparent'] == 'true';
		$page->tpl = (int) @$_POST['template'];
		$page->setUsers(@$_POST['users']);
		$page->profilesInherit = $page->uri != '/' && @$_POST['profiles_inherit'] == 'true';
		if(!$page->profilesInherit) {
			$page->profilesLoad = (int) @$_POST['profiles_load'];
			$page->profilesDynamic = $page->profilesLoad && !(@$_POST['profiles_fetch_once'] == 'true');
		}
		$page->setCss(@$_POST['css']);
		$page->contentType = (string) @$_POST['contentType'];
		$page->meta['keywords'] = @$_POST['m_keywords'];
		$page->meta['description'] = @$_POST['m_description'];
		$page->robots = (int) @$_POST['robots'];
		$page->location = (string) @$_POST['location'];
		$page->router = @$_POST['router'] == 'true';
		$page->published = @$_POST['published'] == 'true';
		$page->caching = @$_POST['caching'] == 'true';
		if($page->caching) {
			$page->cacheLife = intval(@$_POST['cacheLife']);
			$page->cacheGz = @$_POST['cacheGz'] == 'true';
			$page->setCacheVary(@$_POST['cacheVary']);
		}
		$pf->updatePage($page);
		if(@$_POST['usrEnforceChildren'] == 'true')
			$pf->setSubtreeUsers($page->id);
		if(@$_POST['published'] == 'true' && @$_POST['recursive'] == 'true')
			$pf->publishPage($page->id, true);
		header('Location: '.$_SERVER['HTTP_REFERER']);
		die();
	break;

	case 'move_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$result = $pf->movePage(@$_POST['uri_id'], @$_POST['dest_id']);
		if($result) {
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.close();</script>Succesfully moved!';
		} else
			echo 'Failed!';
	break;

	case 'copy_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$result = $pf->copyPage(@$_POST['uri_id'], @$_POST['dest_id'], @$_POST['page_name'], @$_POST['header']);
        if(@$_POST['published'] == 'true')
        	$pf->publishPage($result, true);
		if($result) {
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.close();</script>Succesfully moved!';
		} else
			echo 'Failed!';
	break;

	case 'move_up':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$pf->incrementPriority(@$_POST['uri_id']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'move_down':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$pf->decrementPriority(@$_POST['uri_id']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'publish_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		if($pf->publishPage(@$_POST['uri_id']))
			die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'unpublish_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$pf->unpublishPage(@$_POST['uri_id']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'republish_page':
		require_once(LIBDIR.'/site/pagefactory._wc');
		$pf = PageFactory::getInstance();
		$pf->republishPages(@$_POST['uri_id']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	default:
		die('<meta http-equiv="Refresh" content="0; URL='.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/openconstructor/structure/').'">');
	break;
}
?>
