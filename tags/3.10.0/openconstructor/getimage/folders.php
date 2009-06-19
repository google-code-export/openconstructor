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
 * $Id: folders.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	header('Content-Type: text/html; charset=utf-8');
	
	$db = &WCDB::bo();
	$res = $db->query(
		'SELECT ds_id, name, description'.
		' FROM datasources'.
		' WHERE ds_type="file" AND internal = 0'
	);
	$folder=array();
	while($r=mysql_fetch_assoc($res))
		$folder[$r['ds_id']]=array(
			'name'=>$r['name'],
			'description'=>$r['description']
		);
	mysql_free_result($res);
?>
<html>
<head>
<title></title>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8">-->
<link href="<?=WCHOME?>/<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	window.returnValue=false;
	prev=null;
	function wxyopen(uri,x,y)
	{
		window.open(uri,'',"resizable=yes, scrollbars=yes, status=yes, height="+y+", width="+x);
	}
	function goto(a){
		window.parent.f.insert.disabled=true;
		window.parent.f.create.disabled=false;
		window.parent.fimages.location.href=a.href;
		if(prev){
			prev.style.fontWeight="normal";
		}
		a.style.fontWeight="bold";
		prev=a;
		prev.blur();
		return false;
	}
</script>
<style>
UL{
	margin:0px 0px 0px 10px;
	padding:0px;
	list-style-image:url(<?=WCHOME?>/i/<?=SKIN?>/t/f.gif);
	list-style-position:outside
}
LI{
	margin-bottom:5px;
}
</style>
</head>
<body style="border-style:inset;border-width:2px;padding:20;background:white">
<?php if(!sizeof($folder)) die('<h3>'.H_NO_FOLDERS.'</h3>');?>
<ul><?php
	foreach($folder as $id=>$v)
		echo '<li>&nbsp;<a href="images.php?ds_id='.$id.'" onclick="return goto(this);">'.$v['name'].'</a></li>';
?></ul>
</body>
</html>