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
 * $Id: index.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	$start = array('data', 'structure' => 'sitemap', 'catalog', 'objects', 'templates' => 'tpls','users');
	$auth = Authentication::getInstance();
	$pref = @$auth->profile['startpage'];
	if(($k = array_search($pref, $start)) !== false && System::decide($pref)) {
		sendRedirect('http://'.$_host.WCHOME.'/'.(is_int($k) ? $pref : $k), 302);
		die();
	}
	foreach($start as $k => $v)
		if(System::decide($v)) {
			sendRedirect('http://'.$_host.WCHOME.'/'.(is_int($k) ? $v : $k), 302);
			die();
		}
	WCS::_request(false);
?>