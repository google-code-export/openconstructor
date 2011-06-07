<?php
$path = LIBDIR_THIRD.'/openid';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once "Auth/OpenID/Consumer.php";
require_once "Auth/OpenID/FileStore.php";
require_once "Auth/OpenID/SReg.php";
//require_once "Auth/OpenID/PAPE.php";

/*
global $pape_policy_uris;
$pape_policy_uris = array(
			PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			PAPE_AUTH_MULTI_FACTOR,
			PAPE_AUTH_PHISHING_RESISTANT
		);
*/

function &getStore() {
	$store_path = "/tmp/_php_consumer_store";
	
	if (!file_exists($store_path) &&
		!mkdir($store_path)) {
		print "Could not create the FileStore directory '$store_path'. ".
				" Please check the effective permissions.";
		exit(0);
	}
	
	$authStore = new Auth_OpenID_FileStore($store_path);
	
	return $authStore;
}

function &getConsumer() {
	$store = getStore();
	$consumer = new Auth_OpenID_Consumer($store);
	return $consumer;
}

function getScheme() {
	$scheme = 'http';
	if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
		$scheme .= 's';
	}
	return $scheme;
}

function getReturnTo($url = false) {
	return sprintf("%s://%s:%s/%s", getScheme(), $_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $url);
}

function getTrustRoot($url = false) {
	return sprintf("%s://%s:%s/%s", getScheme(), $_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $url);
}

function escape($thing) {
	return htmlentities($thing);
}

?>