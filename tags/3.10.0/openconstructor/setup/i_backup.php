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
 * $Id: i_backup.php,v 1.4 2007/03/02 10:06:41 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
WCS::requireAuthentication();
WCS::_assert(Authentication::getUserId() == WCS_ROOT_ID);
	
require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/classes._wc');
require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/setup._wc');

$action = @$_POST['action'];
switch($action) {
	case 'create_backup':
		require_once(LIBDIR.'/backup/backupmanager._wc');
		$bm = new BackupManager();
		if(isset($_POST['max_file_size']))
			$bm->setMaxFileSize($_POST['max_file_size']);
		$id = $bm->newId();
		?>
		<html>
		<head>
			<title>-</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<link href="setup.css" type="text/css" rel="stylesheet">
		</head>
		<body style="border: none; font-family: monospace;">
			<b><?=sprintf(H_CREATING_BACKUP_ID, $id)?></b>
			<br><?=CREATING_BACKUP_BG_I?>
			<?php
				flush();
				$bm->create($id);
			?>
		</body>
		</html>
		<?php
	break;
	
	case 'restore_backup':
		require_once(LIBDIR.'/backup/backupmanager._wc');
		$bm = new BackupManager();
		$id = @$_POST['backup_id'];
		?>
		<html>
		<head>
			<title>-</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<link href="setup.css" type="text/css" rel="stylesheet">
		</head>
		<body style="border: none; font-family: monospace;">
			<b><?=sprintf(H_RESTORING_BACKUP_ID, $id)?></b>
			<br><?=RESTORING_BACKUP_BG_I?> 
			<?php
				flush();
				$bm->restore($id);
			?>
		</body>
		</html>
		<?php
	break;
}
?>