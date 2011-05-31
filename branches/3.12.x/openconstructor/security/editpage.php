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
 * $Id: editpage.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/site/pagereader._wc');
	
	$pr = &PageReader::getInstance();
	$page = &$pr->getPage(@$_GET['id']);
	assert($page != null);
	$super = $pr->superDecide($page->id, 'managesub');
	require_once(LIBDIR.'/security/groupfactory._wc');
	require_once(LIBDIR.'/security/user._wc');
	$owner = &User::load($page->sRes->owner);
	$ownerGroup = &GroupFactory::getGroup($page->sRes->group);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=EDIT_PAGE_AUTHS.' '.$page->uri?></title>
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
function dsb(){
	if(<?=$super || WCS::decide($page, 'editpage.chmod') ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=$page->uri?></h3>
<form name="f" method="POST" action="i_security.php">
	<input type="hidden" name="action" value="edit_page">
	<input type="hidden" name="page_id" value="<?=$page->id?>">
	<fieldset style="padding:10"><legend><?=H_RES_OWNERS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=PR_RES_OWNER?>:</td>
			<td><input type="text" name="owner" size="32" maxlength="32" value="<?=$owner ? $owner->login : '???'?>"></td>
		</tr>
		<tr>
			<td><?=PR_RES_GROUP?>:</td>
			<td><input type="text" name="ownerGroup" size="32" maxlength="32" value="<?=$ownerGroup ? $ownerGroup->name : '???'?>"></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=H_AUTHS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=H_ACTION?></td>
			<td><?=H_OWNER_AUTHS?></td>
			<td><?=H_GROUP_AUTHS?></td>
		</tr>
		<?php
		$sRes = &$page->sRes;
		foreach($sRes->actions as $act):
			$c = 'PAGE_'.strtoupper(strtr($act, '.', '_'));
			$title = defined($c) ? constant($c) : $act;
		?>
		<tr>
			<td title='Key: <?=$act?>'><?=$title?></td>
			<td align="middle"><input type="checkbox" name="oAuths[<?=$act?>]" <?=$sRes->getOwnerBit($act) ? 'checked' : ''?>></td>
			<td align="middle"><input type="checkbox" name="gAuths[<?=$act?>]" <?=$sRes->getGroupBit($act) ? 'checked' : ''?>></td>
		</tr>
		<? endforeach;?>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE_CHANGES?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>