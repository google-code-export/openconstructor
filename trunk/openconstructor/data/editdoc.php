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
 * $Id: editdoc.php,v 1.5 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	$type = @$_GET['type'];
	$id = (int) @$_GET['id'];
	assert(!empty($type) && $id > 0);
	assert(preg_match('/^[a-z]+$/', $type) != false);
	
	$db = &WCDB::bo();
	$res = $db->query("SELECT d.ds_id, d.id FROM ds$type d, ds$type d0 WHERE d0.id = $id AND d.id = d0.real_id");
	$r = mysql_fetch_row($res);
	mysql_free_result($res);
	if($r) {
		header('Location: http://'.$_SERVER['HTTP_HOST'].WCHOME."/data/$type/edit.php?ds_id={$r[0]}&id={$r[1]}");
		die();
	}
	assert(true === false);
?>