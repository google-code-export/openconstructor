<?php
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/404/index.php') && !defined('WC_STATUS_404')) {
	define('WC_STATUS_404', 1);
	if(!headers_sent()) {
		header('HTTP/1.1 404 Not Found');
	}
	include($_SERVER['DOCUMENT_ROOT'].'/404/index.php');
	die();
}
header('HTTP/1.1 404 Not Found');
die();
?>