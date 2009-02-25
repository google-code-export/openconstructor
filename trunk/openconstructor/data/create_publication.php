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
 * $Id: create_publication.php,v 1.5 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	
	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;
	
	//userfriendly names
	$uf['ds_name']=DS_NAME;
	$uf['description']=DS_DESCRIPTION;
	//default values
	$ds_name='';
	$description='';
	//read values that have not been saved
	read_fail_header();
	
	$ds_name = htmlspecialchars($ds_name, ENT_COMPAT, 'UTF-8');
	$reportResult = report_results(CREATE_DS_FAILED_W);
	$isValid['ds_name'] = is_valid('ds_name');
	$isValid['description'] = is_valid('description');
	$dis = System::decide('data.dspublication') ? 'false' : 'true';
	$smartybackend->assign("uf", $uf);
	$smartybackend->assign("ds_name", $ds_name);
	$smartybackend->assign("description", $description);
	$smartybackend->assign("dis", $dis);
	$smartybackend->assign("reportResult", $reportResult);
	$smartybackend->assign("isValid", $isValid);
	$smartybackend->display('data/create_publication.tpl');
?>