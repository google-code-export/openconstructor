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
 * $Id: manage_tpl.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/templates._wc');
	
	require_once(LIBDIR.'/templates/wctemplates._wc');
	$tpls = new WCTemplates();
	$tpl = &$tpls->load(@$_GET['id']);
	assert($tpl != null);
	WCS::assert($tpl, 'edittpl');
	require_once(LIBDIR.'/smarty/wcsmartycache._wc');
	$smc = WCSmartyCache::getInstance();
	$clearcache = @$_GET['clearcache'];
	$clearcompiled = @$_GET['clearcompiled'];
	if($tpl->type == 'page')
		$smc->pagetpl_updated($tpl->id, $clearcompiled, $clearcache);
	else
		$smc->tpl_updated($tpl->id, $clearcache, $clearcompiled);
?>
<html>
<head>
<title><?=@$_GET['msg']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	window.returnValue=true;
</script>
</head>
<body style="padding:10">
<br>
<center>
	<img src="<?=WCHOME.'/i/'.SKIN?>/ico/ico-info.gif" align="left"><?=@$_GET['msg']?>
	<br><br><br>
	<input type="button" value="<?=BTN_CLOSE?>" onclick="window.close();" style="width:100">
</center>
</body>
</html>