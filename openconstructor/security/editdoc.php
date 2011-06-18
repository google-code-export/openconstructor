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
 * $Id: editdoc.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/security._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	
	$dsm = new DSManager();
	$ds = $dsm->load(@$_GET['ds_id']);
	assert($ds != null && @$_GET['id']);
	$multiple = strspn($_GET['id'], '0123456789') < strlen($_GET['id']);
	require_once(LIBDIR.'/security/user._wc');
	if(!$multiple) {
		$doc = $ds->getDocument($_GET['id']);
		assert($doc != null);
		$owner = User::load($doc->sRes->owner);
	} else
		$owner = User::load($ds->getDocOwner($_GET['id']));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$multiple ? EDIT_DOCS_AUTHS : EDIT_DOC_AUTHS.' | '.$doc->header?></title>
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
function dsb(){
	if(<?=Authentication::getUserId() == WCS_ROOT_ID ? 'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=$multiple ? EDIT_DOCS_AUTHS : $doc->header ?></h3>
<form name="f" method="POST" action="i_security.php">
	<input type="hidden" name="action" value="edit_doc">
	<input type="hidden" name="ds_id" value="<?=$ds->ds_id?>">
	<input type="hidden" name="doc_id" value="<?=$_GET['id']?>">
	<fieldset style="padding:10"><legend><?=H_RES_OWNERS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=PR_RES_OWNER?>:</td>
			<td><input type="text" name="owner" size="32" maxlength="32" value="<?=$owner ? $owner->login : ($multiple ? '' : '???')?>"></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE_CHANGES?>" name="save"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>