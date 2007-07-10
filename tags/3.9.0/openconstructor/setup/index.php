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
 * $Id: index.php,v 1.27 2007/04/13 12:28:12 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	WCS::_request(Authentication::getUserId() == WCS_ROOT_ID);
	error_reporting(E_ALL);
		
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/classes._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/setup._wc');
	
	$db = &WCDB::bo();
	
	$res=$db->query('SELECT VERSION(), USER(), DATABASE()');
	list($dbversion,$dbuser,$dbname)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT COUNT(*) FROM sitetree');
	list($pagecount)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT COUNT(*) FROM datasources');
	list($dscount)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT SUM(docs) FROM datasources');
	list($doccount)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT COUNT(*) FROM enums');
	list($enumcount)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT COUNT(*) FROM objects');
	list($objcount)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT COUNT(*) FROM wctemplates');
	list($tplcount)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT COUNT(*) FROM catalogtree');
	list($nodecount)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT COUNT(*) FROM wcsgroups');
	list($groupcount)=mysql_fetch_row($res);
	mysql_free_result($res);
	$res=$db->query('SELECT COUNT(*) FROM wcsusers');
	list($usercount)=mysql_fetch_row($res);
	mysql_free_result($res);
	
	require_once(LIBDIR.'/wcftp._wc');
	$ftp = & WCFTP::getNew();
	$ftpErrors = null;
	if($ftp->open(8)) { // timeout 8 seconds
		$isFtpValid = $ftp->isHostValid();
	} else {
		$isFtpValid = true;
	}
	$ftp->close();
	if(!$ftp->success())
		$ftpErrors = $ftp->get_message(true);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?=WC.' '.WC_VERSION_FULL?> | <?=H_SYSTEM_SETUP?> (<?=$_SERVER['HTTP_HOST']?>)</title>
	<link href="setup.css" type="text/css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="<?=WCHOME.'/i/'.SKIN?>/favicon.ico">
	<link rel="icon" type="image/gif" href="<?=WCHOME.'/i/'.SKIN?>/favicon.gif">
</head>
<body style="padding:3%">
	<h3><a href=".."><?=WC.' '.WC_VERSION_FULL?></a> | <?=H_SYSTEM_SETUP?></h3>
	<form name=f method=POST action="i_setup.php" style="margin:0;padding:0">
	<fieldset>
		<legend><?=H_SETUP_GENERAL_INFRO?></legend>
		<table border=0>
			<tr>
				<td class=param><?=PR_SETUP_HTTP_HOST?></td>
				<td class=value><?=$_SERVER['HTTP_HOST']?></td>
			</tr>
			<tr>
				<td class=param><?=PR_SETUP_SERVER_IP?></td>
				<td class=value><?=$_SERVER['SERVER_ADDR']?></td>
			</tr>
			<tr>
				<td class=param><?=PR_SETUP_WEBSERVER?></td>
				<td class=value><?=$_SERVER['SERVER_SOFTWARE']?></td>
			</tr>
			<tr>
				<td colspan=2><hr></td>
			</tr>
			<?php
				$local = $_SERVER['SERVER_ADDR'] == '127.0.0.1' || preg_match('~192\.168\.\d+\.\d+~', $_SERVER['SERVER_ADDR']);
			?>
			<tr>
				<td class=param><?=PR_WC_MODE_DEBUG?></td>
				<td class=value><?=WC_MODE_DEBUG ? '<font color="green">On</font>' : '<font color="red">Off</font>'?> [WC_MODE_DEBUG] <?=!$local && WC_MODE_DEBUG ? ' - <font color="red">'.H_WC_MODE_DEBUG_W.'</font>' : ''?></td>
			</tr>
			<tr>
				<td class=param><?=PR_WC_BLOCK_CACHING?></td>
				<td class=value><?=WC_BLOCK_CACHING ? '<font color="green">On</font>' : '<font color="red">Off</font>'?> [WC_BLOCK_CACHING]</td>
			</tr>
			<tr>
				<td class=param><?=PR_WC_PAGE_CACHING?></td>
				<td class=value><?=WC_PAGE_CACHING ? '<font color="green">On</font>' : '<font color="red">Off</font>'?> [WC_PAGE_CACHING]</td>
			</tr>
			<tr>
				<td colspan=2><hr></td>
			</tr>
			<tr>
				<td class=param><?=H_SETUP_WCS_USERS?>:</td>
				<td>&nbsp;
				<?php
					$res = $db->query('SELECT id, name, title FROM wcsgroups WHERE id IN('.WCS_USERGROUP.')');
					for($i = 0, $l = mysql_num_rows($res); $i < $l; $i++) {
						list($id, $key, $name) = mysql_fetch_row($res);
						echo sprintf("<a href='../users/?node=%d' title='$key'>%s</a>; ", $id, htmlspecialchars($name, ENT_COMPAT, 'UTF-8'));
					}
						
				?>
				</td>
			</tr>
			<tr>
				<td colspan=2><hr></td>
			</tr>
			<tr>
				<td class=param colspan=2><?=sprintf(H_SETUP_VIEW_LS_DIFFS, WCHOME.'/lib/languagesets/compare.php')?></td>
			</tr>
			<tr>
				<td class=param colspan=2><?=sprintf(H_SETUP_VIEW_ERROR_LOGS, FILES.'/log/')?></td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend><?=H_SETUP_PHP_PREFS?></legend>
		<table border=0>
			<tr>
				<td class=value>magic_quotes_gpc</td>
				<td class=value><?=($v = ini_get('magic_quotes_gpc')) ? 'On' : 'Off'?></td>
				<td class=param><?=!$v ? H_SETUP_OK_I : H_SETUP_DISABLE_W?></td>
			</tr>
			<tr>
				<td class=value>magic_quotes_runtime</td>
				<td class=value><?=($v = ini_get('magic_quotes_runtime')) ? 'On' : 'Off'?></td>
				<td class=param><?=!$v ? H_SETUP_OK_I : H_SETUP_DISABLE_W?></td>
			</tr>
			<tr>
				<td class=value>session.use_trans_sid</td>
				<td class=value><?=($v = ini_get('session.use_trans_sid')) ? 'On' : 'Off'?></td>
				<td class=param><?=!$v ? H_SETUP_OK_I : H_SETUP_DISABLE_W?></td>
			</tr>
			<tr>
				<td class=value>register_globals</td>
				<td class=value><?=($v = ini_get('register_globals')) ? 'On' : 'Off'?></td>
				<td class=param><?=!$v ? H_SETUP_OK_I : H_SETUP_DISABLE_W?></td>
			</tr>
			<tr>
				<td class=value>allow_call_time_pass_reference</td>
				<td class=value><?=($v = ini_get('allow_call_time_pass_reference')) ? 'On' : 'Off'?></td>
				<td class=param><?=$v ? H_SETUP_OK_I : H_SETUP_ENABLE_W?></td>
			</tr>
			<tr>
				<td colspan=3><hr></td>
			</tr>
			<tr>
				<td class=param colspan=3><?=sprintf(H_SETUP_VIEW_PHPINFO, './phpinfo.php')?></td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend><?=H_SETUP_FTP_PREFS?></legend>
		<table border=0>
			<tr>
				<td class=param><?=PR_SETUP_FTP_HOST?></td>
				<td class=value><?=WCFTP_HOST?></td>
			</tr>
			<tr>
				<td class=param><?=PR_SETUP_FTP_PORT?></td>
				<td class=value><?=WCFTP_PORT?></td>
			</tr>
			<tr>
				<td class=param><?=PR_SETUP_FTP_LOGIN?></td>
				<td class=value><?=WCFTP_USER?></td>
			</tr>
			<tr>
				<td class=param><?=PR_SETUP_FTP_SITEROOT?></td>
				<td class=value><?=WCFTP_SITEROOT?></td>
			</tr>
			<?php if(!$isFtpValid) :?>
			<tr>
				<td class="param" colspan="2"><hr><h4 class="fail"><?=H_ELSES_FTP_PREFS_GIVEN?></h4></td>
			</tr>
			<?php endif; ?>
			<?php if($ftpErrors) :?>
			<tr>
				<td colspan="2" class="param"><hr><div class="fail"><?=$ftpErrors?></div></td>
			</tr>
			<?php endif; ?>
		</table>
	</fieldset>
	<fieldset>
		<legend><?=H_SETUP_DB_PREFS?></legend>
		<table border=0>
			<tr>
				<td class=param><?=H_SETUP_DB_VERSION?></td>
				<td class=value><?=$dbversion?></td>
			</tr>
			<tr>
				<td class=param><?=PR_SETUP_DB_NAME?></td>
				<td class=value><?=$dbname?></td>
			</tr>
			<tr>
				<td class=param><?=PR_SETUP_DB_LOGIN?></td>
				<td class=value><?=$dbuser?></td>
			</tr>
			<tr>
				<td colspan=2><hr></td>
			</tr>
			<tr>
				<td class=param colspan=2><?=sprintf(H_SETUP_DB_OPTIMIZE, './optimizedb.php')?></td>
			</tr>
			<tr>
				<td colspan=2><hr></td>
			</tr>
			<tr>
				<td class=param colspan=2><?=sprintf(H_SETUP_DB_TABLES_INFO, './checktables.php')?></td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend><?=WC?></legend>
		<table border=0>
			<tr>
				<td colspan="3" class=param><?=H_SETUP_SYS_ALL?>:<hr></td>
			</tr>
			<tr>
				<td class=param><?=H_SETUP_SYS_PAGES?></td>
				<td class=value><?=$pagecount?></td>
				<td class=param><input type=checkbox name=removepages onclick="if(!this.checked) removeall.checked=false"> <?=H_SETUP_SYS_REMOVE?></td>
			</tr>
			<tr>
				<td class=param><?=H_SETUP_SYS_DS_DOCS?></td>
				<td class=value><?=$dscount?> / <?=$doccount?></td>
				<td class=param><input type=checkbox name=removedatasources onclick="if(!this.checked) {removeall.checked=false;removeenums.checked=false;removenodes.checked=false;}removeenums.disabled = !this.checked;removenodes.disabled = !this.checked;"> <?=H_SETUP_SYS_REMOVE?></td>
			</tr>
			<tr>
				<td class=param style="font-size: 90%;"> &nbsp; <?=H_SETUP_SYS_ENUMS?></td>
				<td class=value><?=$enumcount?></td>
				<td class=param><input type=checkbox disabled="yes" name=removeenums onclick="if(!this.checked) removeall.checked=false"> <?=H_SETUP_SYS_REMOVE?></td>
			</tr>
			<tr>
				<td class=param style="font-size: 90%;"> &nbsp; <?=H_SETUP_SYS_NODES?></td>
				<td class=value><?=$nodecount?></td>
				<td class=param><input type=checkbox disabled="yes" name=removenodes onclick="if(!this.checked) removeall.checked=false"> <?=H_SETUP_SYS_REMOVE?></td>
			</tr>
			<tr>
				<td class=param><?=H_SETUP_SYS_OBJECTS?></td>
				<td class=value><?=$objcount?></td>
				<td class=param><input type=checkbox name=removeobjects onclick="if(!this.checked) removeall.checked=false"> <?=H_SETUP_SYS_REMOVE?></td>
			</tr>
			<tr>
				<td class=param><?=H_SETUP_SYS_TPLS?></td>
				<td class=value><?=$tplcount?></td>
				<td class=param><input type=checkbox name=removetpls onclick="if(!this.checked) removeall.checked=false"> <?=H_SETUP_SYS_REMOVE?></td>
			</tr>
			<tr>
				<td class=param><?=H_SETUP_SYS_USERS_GROUPS?></td>
				<td class=value><?=$usercount?> / <?=$groupcount?></td>
				<td class=param><input type=checkbox name=removegroups onclick="if(!this.checked) removeall.checked=false"> <?=H_SETUP_SYS_REMOVE?></td>
			</tr>
			<tr>
				<td class=param colspan=3><input type=checkbox name=removeall onclick="removepages.checked=removedatasources.checked=removeobjects.checked=removeenums.checked=removetpls.checked=removenodes.checked=removegroups.checked=this.checked;removedatasources.onclick();"> <?=H_SETUP_SYS_REMOVE_ALL?></td>
			</tr>
			<tr>
				<td colspan="3" class=param>
					<hr>
					<?=sprintf(H_SETUP_SYS_BACKUP_SITE, './backup.php')?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend><?=H_SETUP_SMARTY_PREFS?></legend>
		<table border=0>
			<tr>
				<td class=param colspan=2><input type=checkbox name=clearcache> <?=H_SETUP_SMARTY_CLEAR_CACHE?></td>
			</tr>
			<tr>
				<td class=param colspan=2><input type=checkbox name=clearcompiled> <?=H_SETUP_SMARTY_CLEAR_COMPILED?></td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend><?=H_SETUP_MISC_PREFS?></legend>
		<table border=0>
			<tr>
				<td class=param colspan=2><input type=checkbox name=resavedss> <?=H_SETUP_MISC_RESAVE_DS?></td>
			</tr>
			<tr>
				<td class=param colspan=2><input type=checkbox name=resavepages> <?=H_SETUP_MISC_RESAVE_PAGES?></td>
			</tr>
		<?
			$res = $db->query('SELECT COUNT(*) FROM `index`');
			list($docs) = mysql_fetch_row($res);
			mysql_free_result($res);
		?>
			<tr>
				<td class=param colspan=2><input type=checkbox name=reindex> <?=sprintf(H_SETUP_MISC_UPDATE_INDEX, $docs, gmdate('H:i:s', (int) $docs * .1), gmdate('H:i:s', $docs * 5))?></td>
			</tr>
			<tr>
				<td class=param colspan=2><input type=checkbox name=clearCaptchaCache <?=WC_CAPTCHA_CACHE ? '' : 'disabled'?>> <?=H_SETUP_MISC_CLEAR_CAPTCHA?></td>
			</tr>
			<tr>
				<td class=param colspan=2>
					<input type=checkbox name=chmod  onclick="document.getElementById('span.mode').style.display = this.checked ? 'inline' : 'none'" <?=substr(PHP_OS,0,3) == 'WIN' ? 'disabled':''?>> <?=H_SETUP_MISC_CHMOD?>
					<br>&nbsp; &nbsp; &nbsp;
					<span id="span.mode" style="display: none;">Mode: 
						<select size=1 name="mode">
							<option value="777">777
							<option value="775">775
							<option value="755">755
						</select>
					</span>
				</td>
			</tr>
		</table>
	</fieldset>
	<p><input type=submit name=posted value="<?=BTN_APPLY?>" style="font-weight: bold;"> <input type=button value="<?=BTN_EXIT_SETUP?>" onclick="location.href = '../'"> <input type=button value="<?=BTN_EXIT_SYSTEM?>" onclick="location.href = '../logout.php'">
	</form>
</body>
</html>