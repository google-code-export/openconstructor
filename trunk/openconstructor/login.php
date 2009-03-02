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
 * $Id: login.php,v 1.13 2007/03/24 20:14:45 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
$auth = &Authentication::getInstance();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="ROBOTS" content="NOINDEX,FOLLOW">
	<title><?=WC.' | '.H_AUTHORIZE?></title>
	<link href="metallic.css" type=text/css rel=stylesheet>
	<link rel="shortcut icon" href="<?=WCHOME?>/i/<?=SKIN?>/favicon.ico">
	<link rel="icon" type="image/gif" href="<?=WCHOME?>/i/<?=SKIN?>/favicon.gif">
	<script type="text/javascript">
		var agent = navigator.userAgent.toLowerCase();
		var browser = ((agent.indexOf("msie") != -1) || (agent.indexOf("gecko") != -1));
	</script>
	<style>
		DIV.top {background:transparent url(i/metallic/about_top.gif) top left repeat-x;font-size:8px;padding:0;height:10px;}
		DIV#logo {background-color:#CCC;padding:10px 40px;border-top:solid 1px #999; border-bottom: solid 1px #666;}
		.blueborder {border: solid 1px #7e9db9;}
		TR.loginpwd TD {padding-bottom: 5px;}
	</style>
</head>
<body style="background:#ccc;" onload="f.<?=!isset($_GET['authlack']) && @$_COOKIE['wcsUserLogin'] ? 'password' : 'login'?>.focus()">
	<table cellspacing="0" cellpadding="10" border="0" height="100%" width="100%"><tr><td align="center" valign="center">
		<div style="border:solid 1px #666;width:620px;text-align:left;background:#e9e9e9;">
			<div class="top">&nbsp;</div>
			<div id="logo"><a href="<?=WC_HOMEPAGE_URI?>"><img src="i/metallic/logo_<?=LANGUAGE?>.gif" border="0"></a></div>
			<?php if(isset($_GET['authlack']) && $auth->userId > 0):?>
				<div style="padding:10px 40px;border-top: solid 1px #fff; border-bottom: solid 1px #666;" id="stop">
					<div style="width:100%">
					<table border="0" cellspacing="0" cellpadding="0" style="color:#cb3435; font-size: 110%;" width="100%"><tr>
						<td><img src="i/<?=SKIN?>/ico/stop.gif" alt="Stop" width="24" height="23"></td>
						<td style="padding-left:10px;">
							<?=$auth->userName?>, <?=WCS_LACK_OF_AUTHORITIES_I?>
						</td>
					</tr></table>
					</div>
				</div>
			<?php endif;?>
			<?php if(isset($_GET['firstlogon']) && $_SESSION['firstlogon']):?>
				<div style="padding:10px 40px;border-top: solid 1px #fff; border-bottom: solid 1px #666;" id="stop">
					<div style="width:100%">
					<table border="0" cellspacing="0" cellpadding="0" style="color:#060; font-size: 110%;" width="100%"><tr>
						<td><img src="i/<?=SKIN?>/ico/ico-info.gif" alt="Info" width="28" height="28"></td>
						<td style="padding-left:10px;">
							<?=INSTALLER_FIRST_LOGON_I?>
						</td>
					</tr></table>
					</div>
				</div>
			<?php endif;?>
			<div style="padding:0px 40px 40px;border-top: solid 1px #fff;">
				<div style="padding:25px 0px 0px;margin:0px;">
					<form name="f" method="post" action="i_login.php" style="margin:0px;padding:0px;"><input name="autologin" type="hidden" value="disabled">
						<input type="hidden" name="next" value="<?=htmlspecialchars(@$_GET['next'], ENT_QUOTES, 'UTF-8')?>">
						<table cellpadding="0" cellspacing="0" border="0" style="font-size:200%;font-family:verdana,tahoma,sans-serif;">
							<tr class="loginpwd">
								<td><?=USR_LOGIN?>:</td>
								<td>&nbsp;&nbsp;<input type="text" name="login" style="font-size:75%" class="blueborder" value="<?=!isset($_GET['authlack']) && @$_COOKIE['wcsUserLogin'] ? $_COOKIE['wcsUserLogin'] : ''?>"></td>
							</tr>
							<tr class="loginpwd">
								<td><?=USR_PASSWORD?>:</td>
								<td>&nbsp;&nbsp;<input type="password" name="password" style="font-size:75%" class="blueborder"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td style="font-size: 50%; padding: 0 0 5px 12px;"><input type="checkbox" name="remember" id="ch.remember" style="margin-bottom: 0px;"> <label for="ch.remember"><?=USR_REMEMBER_ME?></label></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;&nbsp;<input type="submit" value="<?=BTN_AUTHORIZE?>" name="submit" style="font-family:sans-serif; font-size:70%; padding:0 5px"></td>
							</tr>
						</table>
					</form>
				</div>
				<?php include(LIBDIR.'/languagesets/'.LANGUAGE.'/login_info.html');?>
				<script type="text/javascript">
					if(!browser)
						document.getElementById("browser_req").style.color = "#cb3435";
				</script>
			</div>
			<div class="top" style="border-top:solid 1px #666;border-bottom:solid 1px #999;height:10px;">&nbsp;</div>
			<div>
				<div id="copyrights" style="padding-left:40px;padding-right:40px;"><?=WC_COPYRIGHTS?></div>
			</div>
		</div>
	</td></tr></table>
</body>
</html>
