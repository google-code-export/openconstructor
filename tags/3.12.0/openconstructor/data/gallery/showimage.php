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
 * $Id: showimage.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
?>
<html>
<head>
	<title><?=H_VIEW_IMAGE?> <?=@$_GET['img']?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
		body{width:100%;height:100%;margin:0px;padding:5px;}
		h3{color:gray}
	</style>
</head>
<body ondrag="return false">
<?php
	$img=@$_GET['img'];
	if($img!='none'&&!@file_exists($_SERVER['DOCUMENT_ROOT'].$img))
		$img='new';
	switch($img){
		case 'none':
			echo '<h3>'.H_IMAGE_DISABLED.'</h3>';
		break;

		case 'new':
			echo '<h3>'.H_IMAGE_EMPTY.'</h3>';
		break;

		default:
			$img.='?j='.filemtime($_SERVER['DOCUMENT_ROOT'].$img);
		?>
			<table width="100%" height="100%" border=0><tr><td align="center">
			<a href="<?=$img?>" target="_blank"><img src="<?=$img?>" hspace="0" vspace="0" border="0"></a>
			</td></tr></table>
<?php }?>
</body>
</html>
	

