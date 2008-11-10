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
 * $Id: backup.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	WCS::_request(Authentication::getUserId() == WCS_ROOT_ID);

	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/classes._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/setup._wc');
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?=WC.' '.WC_VERSION_FULL?> | <?=H_SETUP_BACKUPS?></title>
	<link href="setup.css" type="text/css" rel="stylesheet">
	<link rel="shortcut icon" href="<?=WCHOME?>/i/<?=SKIN?>/favicon.ico">
	<link rel="icon" type="image/gif" href="<?=WCHOME?>/i/<?=SKIN?>/favicon.gif">
</head>
<body style="padding:3%">
<h1><?=H_SETUP_BACKUPS?></h1>
<form name="f_restore" action="i_backup.php?disableGzResponse=1" method="POST" target="restore_results" onsubmit="document.getElementById('fs.backup').disabled = true; this.restore.disabled = true; return true;">
<input type="hidden" name="action" value="restore_backup">
<fieldset id="fs.restore">
	<legend><?=H_SETUP_RESTORE_BACKUP?></legend>
	<table border=0>
		<tr>
			<td class=param><?=PR_SETUP_BACKUP_ID?>:</td>
			<td class=value>
				<select name="backup_id" onchange="f_restore.restore.disabled = this.selectedIndex == 0;">
					<option value="">-
					<?php
						$backups = glob($_SERVER['DOCUMENT_ROOT'].FILES.'/backup/*');
						rsort($backups);
						foreach($backups as $v)
							if(is_dir($v) && @file_exists($v.'/backup.ini')) {
								$bid = basename($v);
								echo "<option value='$bid'>$bid";
							}
					?>
				</select>
				<?php if(sizeof($backups) > 0) : ?>
				<input type="button" value="<?=BTN_SELECT_LATEST_BACKUP?>" onclick="for(i = 0; i < f_restore.backup_id.options.length; i++) {var o = f_restore.backup_id.options[i]; if(o.value.match(/^\d{4}-\d{2}-\d{2}-\d{6}-\d{3}$/)) {o.selected = true; f_restore.backup_id.onchange(); break;}}">
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="param">
				<hr>
				<input type="submit" name="restore" value="<?=BTN_RESTORE_BACKUP?>" disabled onclick="document.getElementById('rrf').style.display = 'block';">
			</td>
		</tr>
	</table>
	<iframe id="rrf" name="restore_results"  style="border:none; margin: 10px; display: none;" width="100%" height="80"></iframe>
</fieldset>
</form>
<form name="f_backup" action="i_backup.php" method="POST" target="backup_results" onsubmit="document.getElementById('fs.restore').disabled = true; this.backup.disabled = true; return true;">
<input type="hidden" name="action" value="create_backup">
<fieldset id="fs.backup">
	<legend><?=H_SETUP_CREATE_BACKUP?></legend>
	<table border=0>
		<tr>
			<td class=param nowrap="yes"><?=PR_BACKUP_MAX_ARCHIVABLE?>:</td>
			<td class=value><input name="max_file_size" type="text" value="1" size="5"></td>
		</tr>
		<tr>
			<td class=param><?=PR_BACKUP_TIMEOUT?>:</td>
			<td class=value><input name="timeout" type="text" value="0" size="5"></td>
		</tr>
		<tr>
			<td colspan="2" class="param">
				<hr>
				<input type="submit" value="<?=BTN_CREATE_BACKUP?>" name="backup" onclick="document.getElementById('brf').style.display = 'block';">
			</td>
		</tr>
	</table>
	<iframe id="brf" name="backup_results"  style="border:none; margin: 10px; display: none;" width="100%" height="80"></iframe>
</fieldset>
</form>
<a href="index.php"><?=H_SETUP_BACK?></a>
<p><a href="../."><?=WC?></a>
</body>
</html>