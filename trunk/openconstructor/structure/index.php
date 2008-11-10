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
 * $Id: index.php,v 1.15 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('sitemap');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/site/pagereader._wc');
	require_once(LIBDIR.'/tree/export/siteview._wc');
	require_once('../include/sections._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$siteroot = @$_COOKIE['siteroot'] ? $_COOKIE['siteroot'] : null;
	$pr = &PageReader::getInstance();
	$sitemap = &$pr->getTree($siteroot);
	if($sitemap == null) {
		if($siteroot != 1) {
			$siteroot = 1;
			$sitemap = &$pr->getTree($siteroot);
		}
		assert($sitemap != null);
	}
	$curnode = isset($_GET['node']) ? $_GET['node'] : @$_COOKIE['curnode'];
	$page = &$pr->getPage($curnode);
	if($page == null) {
		$curnode = $sitemap->root->id;
		$page = &$pr->getPage($curnode);
	}
	$router = $page->router ? $page->id : $pr->getPageRouter($page->id);
	$super = $pr->superDecide($page->id, 'managesub');
	setcookie('curnode', $curnode, 0, WCHOME.'/structure/');

	$smartybackend->assign_by_ref("page", $page);
	$smartybackend->assign_by_ref("sitemap", $sitemap);
	$smartybackend->assign_by_ref("auth", $auth);

	$smartybackend->assign("cur_section", 'structure');
	$smartybackend->assign("menu", getTabs('structure'));

	include('toolbar._wc');
	$smartybackend->assign_by_ref("toolbar", $toolbar);

	require_once(LIBDIR.'/templates/wctemplates._wc');
	$wct = & new WCTemplates();
	$smartybackend->assign("tpls", $wct->get_all_tpls('page'));
	$smartybackend->assign("super", $super);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);
	if($page->tpl)
		$tpl = &$wct->load($page->tpl);
	else
		$tpl = null;
	$smartybackend->assign_by_ref("tpl", $tpl);

	$view = & new TreeSiteView();
	$view->setSelected($page->id);
	$smartybackend->assign("tree", $sitemap->export($view));

    /*echo "<pre>";
    var_dump(get_object_vars($sitemap->export($view)));
    echo "</pre>";*/

	include('objtypes._wc');
	include('headline._wc');
	$smartybackend->assign("o_t", $o_t);
	$smartybackend->assign("i_t", $i_t);
	$smartybackend->assign("obj", $obj);
	$smartybackend->assign("blocks", $blocks);
	$smartybackend->assign("icon", @$icon);
	$smartybackend->assign("fields", $fields);
	$smartybackend->assign("objIds", $objIds);
	$smartybackend->assign("stubs", $stubs);

	$smartybackend->assign("editor_width", 660);
	$smartybackend->assign("editor_height", 'null');
	$smartybackend->assign("editor", WCHOME.'/objects/edit.php?j=1');

	$smartybackend->assign("pageHref", $pageHref);

	$smartybackend->display('structure/main.tpl');
?>
