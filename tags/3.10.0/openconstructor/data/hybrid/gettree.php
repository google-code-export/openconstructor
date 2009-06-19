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
 * $Id: gettree.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	require_once(LIBDIR.'/tree/sqltreereader._wc');
	
	$reader = new SqlTreeReader();
	$tree = $reader->getTree((int) @$_GET['id']);
	assert($tree != null);
	require_once(LIBDIR.'/tree/export/ajaxview._wc');
	$view = new AjaxView();
	header("Content-type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo '<!--?xml-stylesheet type="text/xsl" href="'.WCHOME.'/skins/'.SKIN.'/tree.xsl"?-->';
	$tree->export($view);
?>
