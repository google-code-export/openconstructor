<?php
/**
* @version $Id: strcasecmp.php,v 1.2 2007/03/15 06:56:30 sanjar Exp $
* @package utf8
* @subpackage strings
*/

//---------------------------------------------------------------
/**
* UTF-8 aware alternative to strcasecmp
* A case insensivite string comparison
* Note: requires utf8_strtolower
* @param string
* @param string
* @return int
* @see http://www.php.net/strcasecmp
* @see utf8_strtolower
* @package utf8
* @subpackage strings
*/
function utf8_strcasecmp($strX, $strY) {
    $strX = utf8_strtolower($strX);
    $strY = utf8_strtolower($strY);
    return strcmp($strX, $strY);
}

