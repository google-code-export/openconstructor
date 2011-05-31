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
 * $Id: assertions.php,v 1.5 2007/02/27 11:23:19 sanjar Exp $
 */
function defaultAssertHandler($file, $line, $code) {
	require_once(LIBDIR.'/languagesets/'.(defined('LANGUAGE') ? constant('LANGUAGE') : DEFAULT_LANGUAGE).'/main._wc');
	$head = "<img src='{$GLOBALS['_wchome']}/i/metallic/ico/ico-vnm.gif'>".H_CANNOT_HANDLE_REQUEST;
	$content = "<div class='subhead'>".H_POSSIBLE_REASONS.":</div>".TXT_POSSIBLE_REASONS;
	
	if($code)
		$code = 'Code: '.$code;
	$additional = "<nobr>Version: ".WC_VERSION_FULL."</nobr><br>
					<nobr>Location: {$_SERVER['REQUEST_METHOD']} <script>document.writeln(location.href);</script></nobr><br>
					<nobr>Source: {$file}</nobr><br>
					<nobr>Line: {$line}</nobr><br>
					{$code}
			";
	_assertTemplate(H_CANNOT_HANDLE_REQUEST, $head, $content, $additional);
	die();
}

function wcsAssertHandler($file, $line, $code, $resName = null, $act = null) {
	require_once(LIBDIR.'/languagesets/'.(defined('LANGUAGE') ? constant('LANGUAGE') : DEFAULT_LANGUAGE).'/main._wc');
	$head = "<img src='{$GLOBALS['_wchome']}/i/metallic/ico/stop.gif'>".H_ACCESS_DENIED_RES;
	$content = $resName && $act ? "<div class='subhead'>".sprintf(TPL_RES_REQURES_AUTH, "<span class='red'>$resName</span>", "<span class='red'>$act</span>")."</div>" : '';
	
	if($code)
		$code = 'Code: '.$code;
	$additional = "<nobr>Version: ".WC_VERSION_FULL."</nobr><br>
					<nobr>Location: {$_SERVER['REQUEST_METHOD']} <script>document.writeln(location.href);</script></nobr><br>
					<nobr>Source: {$file}</nobr><br>
					<nobr>Line: {$line}</nobr><br>
					{$code}
			";
	_assertTemplate(H_ACCESS_DENIED_RES, $head, $content, $additional);
	die();
}

function _assertTemplate($title, $head, $content, $additional) {
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
		<title><?=WC?> | <?=$title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style>
			BODY {background: #ccc; font-size:12px; font-family: arial, sans-serif; padding:0;margin:0;}
			TABLE {font-size: 100%;}
			DIV#center {border:solid 1px #666; width:620px; text-align:left; background:#e9e9e9; border-bottom: none;}
			DIV.block {padding:20px 50px; border-top: solid 1px #fff; border-bottom: solid 1px #666;}
			DIV#firstblock {border-top:none;}
			DIV.head {margin: 20px -33px; font-weight: bold; font-size: 125%;}
			DIV.head IMG {vertical-align:middle;margin-right:5px;}
			DIV.subhead {font-size: 115%;}
			UL {margin:10px 0 0px 20px;padding:0;list-style-type:circle;color:#666;}
			UL LI {margin:0;padding:0;}
			DIV.pre {
				width:520px; border: solid 1px #7e9db9;	background: #fff; margin: 10px 0 0;
				padding: 5px;font-family: monospace;
				overflow: scroll;
			}
			A, A:visited {color:#06c;}
			A:hover {color:#f60;}
			DIV.tabs {padding:10px 0 10px;}
			DIV.tabs A {width:33%; padding: 10px 20px 10px 0;}
			DIV#logout {text-align:right;}
			DIV#logout A {font-weight: bold;color:#666;}
			.red {color:#cb3435;}
		</style>
	</head>
	<body>
		<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%"><tr><td align="center" valign="center">
			<div id="center">
				<div class="block" id="firstblock">
					<div class="head"><?=$head?></div>
					<?=$content?>
					<?php if($additional):?>
					<div class="subhead" style="padding-top:20px;"><?=H_ADDITIONAL_ASSERT_INFO?>:</div>
					<div class="pre">
						<?=$additional?>
					</div>
					<?php endif;?>
				</div>
				<?php
					require_once(LIBDIR.'/../include/sections._wc');
					$tabs = getTabs();
					if(sizeof($tabs)):
				?>
				<div class="block">
					<div class="subhead"><?=H_GOTO_TAB?>:</div>
					<div class="tabs">
					<?
						foreach($tabs as $href => $title)
							echo "<a href='{$GLOBALS['_wchome']}/$href' class='tab'>$title</a>";
					?>
					</div>
					<div id="logout">
						<a href="<?=$GLOBALS['_wchome']?>/logout.php"><?=H_EXIT_SYSTEM?></a>
					</div>
				</div>
				<?php endif;?>
			</div>
		</td></tr></table>
	</body>
	</html>
	<?php
}
?>