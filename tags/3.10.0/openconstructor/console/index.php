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
 * $Id: index.php,v 1.5 2007/03/02 10:06:44 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	require_once(LIBDIR.'/router._wc');
	$router = new Router('/console/', 'console', @$_SERVER['PATH_INFO']);
	require_once(LIBDIR.'/context._wc');
	$ctx = &Context::getInstance();
	$ctx->router = &$router;
	header('Content-Type: text/plain; charset=utf-8');
	
	ob_start();
	require_once(LIBDIR.'/console/taskrunner._wc');
	
	echo "\n-\n- Open Constructor console\n-\n";
	
	WCS::runAs(WCS_ROOT_ID);
	ConsoleTaskRunner::runFromRouter($router);
	WCS::stopRunAs();
	
	exitConsole();
	
	function exitConsole() {
		echo "\nExiting";
		$ctx = &Context::getInstance();
		if($ctx->getParam('console.verbose'))
			ob_end_flush();
		else
			ob_end_clean();
	}
?>