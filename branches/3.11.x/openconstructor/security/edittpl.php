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
 * $Id: edittpl.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/templates/wctemplates._wc');
	
	assert(isset($_GET['id']) == true);
	require_once(LIBDIR.'/security/groupfactory._wc');
	require_once(LIBDIR.'/security/user._wc');
	$multiple = strspn($_GET['id'], '0123456789') < strlen($_GET['id']);
	if(!$multiple) {
		$tpl = &WCTemplates::load($_GET['id']);
		assert($tpl != null);
		$owner = &User::load($tpl->sRes->owner);
		$ownerGroup = &GroupFactory::getGroup($tpl->sRes->group);
	} else {
		$tpl = &WCTemplates::getAggregateTemplate($_GET['id']);
		if($tpl[0]->sRes->owner == $tpl[1]->sRes->owner)
			$owner = &User::load($tpl[0]->sRes->owner);
		if($tpl[0]->sRes->group == $tpl[1]->sRes->group)
			$ownerGroup = &GroupFactory::getGroup($tpl[0]->sRes->group);
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=is_array($tpl) ? EDIT_TPLS_AUTHS : EDIT_TPL_AUTHS.' | '.$tpl->name?></title>
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
function dsb(){
	if(<?= ($multiple ? true : WCS::decide($tpl, 'edittpl.chmod')) ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=is_array($tpl) ? EDIT_TPLS_AUTHS : $tpl->name?></h3>
<form name="f" method="POST" action="i_security.php">
	<input type="hidden" name="action" value="edit_template">
	<input type="hidden" name="tpl_id" value="<?=$_GET['id']?>">
	<fieldset style="padding:10"><legend><?=H_RES_OWNERS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=PR_RES_OWNER?>:</td>
		<? if($multiple): ?>
			<td>
				<input type="checkbox" onclick="f.owner.disabled=!this.checked" <?=@$owner ? 'checked' : ''?>>
				<input type="text" name="owner" size="32" maxlength="32" <?=@$owner ? "value='$owner->login'" : 'disabled'?>>
			</td>
		<? else : ?>
			<td><input type="text" name="owner" size="32" maxlength="32" value="<?=$owner ? $owner->login : '???'?>"></td>
		<? endif; ?>
		</tr>
		<tr>
			<td><?=PR_RES_GROUP?>:</td>
		<? if($multiple): ?>
			<td>
				<input type="checkbox" onclick="f.ownerGroup.disabled=!this.checked" <?=@$ownerGroup ? 'checked' : ''?>>
				<input type="text" name="ownerGroup" size="32" maxlength="32" <?=@$ownerGroup ? "value='$ownerGroup->name'" : 'disabled'?>>
			</td>
		<? else : ?>
			<td><input type="text" name="ownerGroup" size="32" maxlength="32" value="<?=$ownerGroup ? $ownerGroup->name : '???'?>"></td>
		<? endif; ?>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=H_AUTHS?></legend>
	<table style="margin:5 0" cellspacing="3" id="auths">
		<tr>
			<td><?=H_ACTION?></td>
			<td><?=H_OWNER_AUTHS?></td>
			<td><?=H_GROUP_AUTHS?></td>
		</tr>
		<?php
		if(!$multiple):
			$sRes = &$tpl->sRes;
			foreach($sRes->actions as $act):
				$c = 'TPL_'.strtoupper(strtr($act, '.', '_'));
				$title = defined($c) ? constant($c) : $act;
			?>
			<tr>
				<td title='Key: <?=$act?>'><?=$title?></td>
				<td align="middle"><input type="checkbox" name="oAuths[<?=$act?>]" <?=$sRes->getOwnerBit($act) ? 'checked' : ''?>></td>
				<td align="middle"><input type="checkbox" name="gAuths[<?=$act?>]" <?=$sRes->getGroupBit($act) ? 'checked' : ''?>></td>
			</tr>
		<?	endforeach;
		else:
			$GLOBALS['states'] = array('', H_ALLOW, H_DENY);
			foreach($tpl[0]->sRes->actions as $act):
				$c = 'TPL_'.strtoupper(strtr($act, '.', '_'));
				$title = defined($c) ? constant($c) : $act;
			?>
			<tr>
				<td title='Key: <?=$act?>'><?=$title?></td>
				<td align="middle"><? ctl("oAuths[$act]", $tpl[0]->sRes->getOwnerBit($act), $tpl[1]->sRes->getOwnerBit($act))?></td>
				<td align="middle"><? ctl("gAuths[$act]", $tpl[0]->sRes->getGroupBit($act), $tpl[1]->sRes->getGroupBit($act))?></td>
			</tr>
		<?	endforeach;
		endif;
		function ctl($name, $state1, $state2) {
			$status = $state1 ? 1 : (!$state2 ? 2 : 0);
			$states = &$GLOBALS['states'];
			echo "<select name='$name'>";
			for($i = 0, $l = sizeof($states); $i < $l; $i++)
				echo sprintf("<option value='$i' %s %s>{$states[$i]}", $status == $i ? 'SELECTED' : '', $i == 0 ? 'style="background-color:#eee;"' : '');
			echo '</select>';
		}
		?>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE_CHANGES?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>