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
 * $Id: logout.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	if(!session_id())
		session_start();
	Authentication::destroy();
	session_unset();
	session_destroy();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?=WC.' | '.H_EXIT?></title>
	<link href="metallic.css" type=text/css rel=stylesheet>
	<link rel="shortcut icon" type="image/x-icon" href="<?=WCHOME.'/i/'.SKIN?>/favicon.ico">
	<link rel="icon" type="image/gif" href="<?=WCHOME.'/i/'.SKIN?>/favicon.gif">
	<style>
		DIV.top {background:transparent url(i/metallic/about_top.gif) top left repeat-x;font-size:6px;padding:0;height:10px;}
		DIV#logo {background-color:#CCC;padding:10px 40px;border-top:solid 1px #999; border-bottom: solid 1px #666;}
	</style>
</head>
<body style="background:#ccc;">
	<table cellspacing="0" cellpadding="10" border="0" height="100%" width="100%"><tr><td align="center" valign="center">
		<div style="border:solid 1px #666;width:620px;text-align:left;background:#e9e9e9;">
			<div class="top">&nbsp;</div>
			<div id="logo"><a href="<?=WC_HOMEPAGE_URI?>"><img src="i/metallic/logo_<?=LANGUAGE?>.gif" border="0"></a></div>
			<div style="padding:0px 40px 40px;border-top: solid 1px #fff;">
				<?php include(LIBDIR.'/languagesets/'.LANGUAGE.'/exit.html');?>
			</div>
			<div class="top" style="border-top:solid 1px #666;border-bottom:solid 1px #999;height:12px;">&nbsp;</div>
			<div style="clear:both;height:40px;background:#ccc;width:100%;border-top:solid #cccccc;border-width:1px;padding-left:5px;padding-bottom:10px;">
				<div id="copyrights" style="padding-left:40px;padding-right:40px;"><?=WC_COPYRIGHTS?></div>
			</div>
		</div>
	</td></tr></table>
</body>
</html>