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
 * $Id$
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	
	require_once(LIBDIR.'/templates/wctemplates._wc');
	$wt = new WCTemplates();
	$tpl = $wt->load(@$_GET['id']);
	assert(is_object($tpl) && $tpl->type == 'importtables');
	require_once(LIBDIR.'/smarty/wcsmarty._wc');
	$smarty = new WCSmarty();
	$smarty->caching = 0;
	$smarty->display($tpl->id.'.tpl');
	unset($smarty, $wt, $tpl);
?>