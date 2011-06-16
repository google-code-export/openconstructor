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
 * $Id: secureaccess.php,v 1.4 2007/02/27 11:23:21 sanjar Exp $
 */
	$redirected = false;
	$redirected = @$_SERVER['REDIRECT_STATUS'] == 403 && @$_SERVER['REDIRECT_URL'];
	if(!$redirected || !@$_GET['require'])
		die();
	$filename = $_SERVER['DOCUMENT_ROOT'].$_SERVER['REDIRECT_URL'];
	if(!@file_exists($filename)) {
		header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
		die();
	}
	require_once('../commons._wc');
	if(strpos(@$_SERVER['REDIRECT_URL'], WCHOME.'/') === 0)
		die();
	session_start();
	require_once(LIBDIR.'/security/resource._wc');
	require_once(LIBDIR.'/security/wcs._wc');
	Authentication::importFromSession();
	
	class FileStub {
		var $name;
		var $sRes, $authenticationPage;
		
		function FileStub($name) {
			$this->name = $name;
			$this->sRes = null;
		}
	}
	
	$sFile = new FileStub($filename);
	$sFile->authenticationPage = _getAuthenticationPage();
	$groups = explode(',', $_GET['require']);
	$sFile->sRes = new WCSResource($filename, WCS_ROOT_ID, $groups);
	WCS::requireAuthentication($sFile);
	require_once('../fileresponse._wc');
	$response = new FileResponse($filename);
	$response->doResponse();
	die();
?>