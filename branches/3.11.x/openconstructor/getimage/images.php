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
 * $Id: images.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	$ds_id=-1;
	if(@$_GET['ds_id']) $ds_id=intval($_GET['ds_id']);
	$db = &WCDB::bo();
	$res = $db->query(
		'SELECT id, name, filename, type, size'.
		' FROM dsfile'.
		' WHERE ds_id='.$ds_id.' AND type IN ("jpg","gif","png")'.
		' ORDER BY date DESC'
	);
	$image=array();
	if(mysql_num_rows($res)>0)
	{ 
		while($row=mysql_fetch_assoc($res))
			$image[$row['id']]=array(
				'name'=>$row['name'],
				'filename'=>$row['filename'],
				'type'=>$row['type'],
				'size'=>$row['size']
			);
	}
	mysql_free_result($res);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="<?=WCHOME?>/<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var folderID=<?=$ds_id?>;
function chk(obj){window.parent.f.insert.disabled=false;window.parent.f.img.value=obj.value;}
function o(id){window.parent.editor.wxyopen('showimage.php?id='+id,200,200);}
</script>
<style>
LI{
background:transparent url(<?=WCHOME?>/i/<?=SKIN?>/f/img.gif) no-repeat 2px left;
margin-bottom:3px;padding:3px 0px 5px 20px;
}
LI INPUT{float:left;margin-left:-40px;position:relative;top:0px;}
</style>
</head>
<body style="border-style:inset;border-width:2px;padding:20 10;background:white">
<?php if($ds_id<0) die('<h3>'.H_CHOOSE_FOLDER.'</h3>');?>
<?php if(!sizeof($image)) die('<h3>'.H_FOLDER_IS_EMPTY.'</h3>');?>
<ul style="margin:0px 0px 0px 40px;padding:0px;"><?php
	foreach($image as $id=>$v)
		echo '<li><input type="radio" name="n" value="'.$v['filename'].'" onclick="chk(this)">'.
		'<a href="javascript:o('.$id.')" title="'.$v['size'].'">'.$v['name'].'.'.$v['type'].'</a></li>';
?></ul>
</body>
</html>