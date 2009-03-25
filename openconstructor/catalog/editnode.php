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
 * $Id: editnode.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/catalog._wc');
	require_once(LIBDIR.'/tree/sqltreereader._wc');
	
	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;
	
	$reader = new SqlTreeReader();
	$node = &$reader->getNode(@$_GET['id']);
	assert($node != null);
	$rootNode = &$reader->getRootNode($node->id);
	
	$dis = WCS::decide($rootNode, 'edittree') ? 'false' : 'true';
	
	$smartybackend->assign("node", $node);
	$smartybackend->assign("dis", $dis);
	$smartybackend->display('catalog/editnode.tpl');
	
//	$reader = new SqlTreeReader();
//	$tree = $reader->getTree(1);
//	$in = (int) @$_GET['id'];
//	if(!$tree->exists($in))
//		$in = 1;
?>