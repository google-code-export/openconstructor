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
 * $Id: edit.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	
	$db = &WCDB::bo();
	$res = $db->query('SELECT id, ds_type, obj_type FROM objects WHERE id='.$_GET['id']);
	$obj = mysql_fetch_assoc($res);
	mysql_free_result($res);
	if($obj) {
		header('Location: '.$obj['ds_type'].'/'.$obj['obj_type'].'.php?id='.$obj['id']);
		die();
	}
	assert(true === false);
?>