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
 * $Id: viewtpl.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/templates._wc');
	assert(isset($_GET['type']));
	require_once(LIBDIR.'/templates/wctemplates._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$tpls = & new WCTemplates();
	$map = &$tpls->get_map();
	$types = array();
	foreach($map as $k => $v)
		$types = array_merge($types, current($v));
	$type = $_GET['type'];
	assert(isset($types[$type]));
	require_once(LIBDIR.'/syntax/syntaxhighlighter._wc');
	$tpl = implode('', file(LIBDIR.'/tpl/'.$type.'.tpl'));

	$SyntaxHighlighter = new SyntaxHighlighter();
	$smartybackend->assign_by_ref("SyntaxHighlighter", $SyntaxHighlighter);

	$smartybackend->assign("types", $types);
	$smartybackend->assign("type", $type);
	$smartybackend->assign("tpl", $tpl);

	$smartybackend->display('templates/viewtpl.tpl');
?>