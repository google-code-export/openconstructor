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
 * @author Sanjar Akhmedov
 * 
 * $Id$
 */
	
	$redirected = @$_SERVER['REDIRECT_STATUS'] == 403 && @$_SERVER['REDIRECT_URL'];
	list($width, $height, $length, $fsize) = array((int) @$_GET['width'], (int) @$_GET['height'], (int) @$_GET['length'], (int) @$_GET['fontsize']);
	$cid = basename($_SERVER['REDIRECT_URL'], '.png');
	if($redirected && $width && $height && $length && $fsize && $cid && strpos(@$_SERVER['HTTP_REFERER'], "http://{$_SERVER['HTTP_HOST']}") === 0) {
		require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/commons._wc');
		session_start();
		if(isset($_SESSION['_cid'][$cid]) || WC_MAX_IDLE_CAPTCHA == 0 || WC_MAX_IDLE_CAPTCHA > sizeof((array) @$_SESSION['_cid'])) {
			require_once('wordgenerator._wc');
			$word = WordGenerator::generate($length);
			$_SESSION['_cid'][$cid] = strtolower($word);
			header('HTTP/1.1 200 Ok');
			header('Status: 200 Ok');
			header('Content-Type: image/png');
			header('Pragma: no-cache');
			header('Cache-Control: no-cache, no-store, must-revalidate');
			$file = WC_CAPTCHA_CACHE.'/'.strtolower($word).'.png';
			if(WC_CAPTCHA_CACHE && file_exists($file)) {
				readfile($file);
			} else {
				require_once('captcha._wc');
				$png = Captcha::generate($word, $width, $height, $fsize, isset($_GET['truetype']));
				echo $png;
				umask(0);
				if(WC_CAPTCHA_CACHE && ($f = fopen($file, 'wb'))) {
					fwrite($f, $png);
					fclose($f);
				}
			}
			die();
		}
	}
	@include($_SERVER['DOCUMENT_ROOT'].'/openconstructor/404.php');
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
	die();
?>