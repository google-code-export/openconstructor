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
 * $Id: about.php,v 1.14 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?=ABOUT_WC?></title>
		<style>
			BODY {background-color:#f2f2f2;border:solid 1px #999;font-family:verdana,helvetica,arial,sans;font-size:12px;}
			DIV#top {background:transparent url(i/metallic/about_top.gif) top left repeat-x;font-size:6px;padding:0;height:9px;}
			DIV#logo {background-color:#CCC;padding:10px;border:solid 1px #999;border-width:1px 0;}
			DIV.info {background-color:#fff;padding:10px;height:360px;overflow-y: scroll;}
			DIV.bottom {padding:10px;border:solid 0 #999;border-top-width:1px;text-align:right;}
			DIV#team {display:none;}
			DIV#team LI {font-size:11px;}
			DIV#team A {font-size:12px;}
			DIV#teamCmd {display:none;text-align:left;}
			HR {margin:5px 0;}
			A {color:#0066CC;}
			A:hover {color:#c00;}
			A IMG {border:none;}
			UL {margin:20px 0px;padding:0px;list-style-type:none;}
			UL LI {margin:10px 10px 10px 20px;}
			UL LI A {font-size:100%;}
		</style>
		<script>
			function closeAbout() {
				window.close();
			}
			function acknowledgements() {
				document.all('info').style.display='none';
				document.all('team').style.display='block';
				document.all('infoCmd').style.display='none';
				document.all('teamCmd').style.display='block';
			}
			function back() {
				document.all('team').style.display='none';
				document.all('info').style.display='block';
				document.all('teamCmd').style.display='none';
				document.all('infoCmd').style.display='block';
			}
			function techSupport() {
				dialogArguments.techSupport();
			}
		</script>
	</head>
	<body onkeypress="if(event.keyCode==27) closeAbout()" onload="document.all('ack').focus()">
		<div id="top">&nbsp;</div>
		<div id="logo"><a href="<?=WC_HOMEPAGE_URI?>" target="_blank"><img src="i/metallic/logo_<?=LANGUAGE?>.gif"></a></div>
		<div class="info" id="info">
			<p><?=WC.' '.WC_VERSION_FULL?></p>
			<p><?=WC_COPYRIGHTS?></p>
			<div style="border-top: solid 1px #999;">
				<?php
					readfile(LIBDIR.'/languagesets/'.LANGUAGE.'/free_software.html');
				?>
			</div>
			<div>
				<p><?=sprintf(WC_READ_LICENSE_BEFORE_USING, WCHOME)?></p>
				<p><?=WC_READ_LICENSE_AT_GNU?></p>
			</div>
			<div style="border-top: solid 1px #999;">
				<?php
					readfile(LIBDIR.'/languagesets/'.LANGUAGE.'/interface_license.html');
				?>
			</div>
		</div>
		<div class="info" id="team">
			<?php include(LIBDIR.'/languagesets/'.LANGUAGE.'/acknowledgements.html');?>
		</div>
		<div class="bottom" id="infoCmd">
			<input type="button" value="<?=BTN_ACKNOWLEDGEMENTS?>" id="ack" onclick="acknowledgements()">
			<input type="button" value="<?=BTN_CLOSE?>" onclick="closeAbout()" style="margin-left:40px;">
		</div>
		<div class="bottom" id="teamCmd">
			<input type="button" value="<?=BTN_BACK?>" onclick="back()">
		</div>
	</body>
</html>