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
 * $Id: editgroup.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('users');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/users._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');
	
	$gf = &GroupFactory::getInstance();
	$group = $gf->getGroup(@$_GET['group_id']);
	assert($group != null);
	$sRes = $sys->sRes;
	$sRes->setAuthorities($sRes->getOwnerAuths(), $group->auths);
?>
<html>
<head>
<title><?=WC.' | '.EDIT_USERGROUP?> | <?=htmlspecialchars($group->title, ENT_COMPAT, 'UTF-8')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.name.value.match(re)||<?=WCS::decide($group, 'editgroup')?'false':'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
</script>
<style>
	TR.l1 TD {
		padding: 15px 0 5px 10px;
		font-size: 115%;
	}
	TR.l2 TD {
		padding: 2px 0 2px 30px;
	}
	TR.l3 TD {
		padding: 0 0 0 50px;
	}
	TD.c {
		padding-left: 0 !important;
	} 
</style>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_USERGROUP?></h3>
<form name="f" method="POST" action="i_users.php">
	<input type="hidden" name="group_id" value="<?=$group->id?>">
	<input type="hidden" name="action" value="edit_group">
	<fieldset style="padding:10"><legend><?=USR_USERGROUP?></legend>
	<table style="margin:10 0">
	<tr><td><?=USR_USERGROUP_KEY?>:</td><td><input type="text" name="key" value="<?=$group->name?>" disabled size="32" maxlength="32">
	<tr><td><?=USR_USERGROUP_NAME?>:</td><td><input type="text" name="name" value="<?=htmlspecialchars($group->title, ENT_COMPAT, 'UTF-8')?>" size="64" maxlength="128" onpropertychange="dsb()">
	</td></tr></table>
	</fieldset><br>
	<fieldset style="padding:10" <?=!WCS::privileged(Authentication::getInstance()) ? 'disabled' : ''?>><legend><?=USR_PROFILES?></legend>
	<table style="margin:10 0">
		<tr><td><?=USR_PROFILES_DS?>:</td>
			<td><select name="profileType" size="1"><option value="0">-
	<?php
		require_once(LIBDIR.'/dsmanager._wc');
		$dsm = & new DSManager();
		$ds = $dsm->getAll('hybrid');
		foreach($ds as $v)
			echo '<option value="'.$v['id'].'"'.($v['id'] == $group->profileType ? ' selected' : '').'>'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',substr_count($v['path'],',') - 1).
				$v['name'];
	?>
		</select></td></tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10" <?=!WCS::decide($group, 'editgroup.umask') ? 'disabled' : ''?>><legend><?=USR_MASK?></legend>
	<table style="margin:10 0">
		<tr><td valign="top"><?=USR_MASK_PATTERN?>:</td>
		<td><textarea name="umask" cols="40" rows="3"><?=htmlspecialchars($group->umask, ENT_COMPAT, 'UTF-8')?></textarea>
	</table>
	</fieldset><br>
	<fieldset style="padding:10" <?=!WCS::privileged(Authentication::getInstance()) ? 'disabled' : ''?>><legend><?=USR_AUTHS?></legend>
	<table style="margin:10 0">
	<?php
		$i = 0;
		foreach($sRes->actions as $action) {
			$state = $sRes->getGroupBit($action) ? 'checked' : '';
			$i++;
			$c = 'SYS_'.strtoupper(strtr($action, '.', '_'));
			$title = defined($c) ? constant($c) : $action;
			$class = 'l'.(substr_count($action,'.') + 1);
			echo "<tr class='$class'><td class='c'><input type='checkbox' id='ch$i' name='act[$action]' $state></td><td><label for='ch$i' title='$action'>$title</label></td></tr>";
		}
	?>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form><br><br>
<script>dsb();</script>
</body>
</html>