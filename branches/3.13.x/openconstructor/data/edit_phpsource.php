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
 * $Id: edit_phpsource.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	assert(@$_GET['ds_id'] > 0);
	
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	assert($_ds = $dsm->load($_GET['ds_id']));
	WCS::request($_ds, 'editds');
	//userfriendly names
	$uf['ds_name']=DS_NAME;
	$uf['description']=DS_DESCRIPTION;
	//default values
	$ds_name=$_ds->name;
	$description=$_ds->description;
	//read values that have not been saved
	read_fail_header();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=WC.' | '.EDIT_DS_PHPSOURCE.' | '.$_ds->name?></title>
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.ds_name.value.match(re)||<?=WCS::decide($_ds, 'editds') ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=EDIT_DS_PHPSOURCE?></h3>
<?php
	report_results(SAVE_DS_FAILED_W,SAVE_DS_SUCCESS_I);
?>
<form name="f" method="POST" action="i_data.php">
	<input type="hidden" name="action" value="edit_dsphpsource">
	<input type="hidden" name="ds_id" value="<?=$_GET['ds_id']?>">
	<fieldset style="padding:10"><legend><?=DS_GENERAL_PROPS?></legend>
		<div class="property"<?=is_valid('ds_name')?>>
			<span><?=$uf['ds_name']?>:</span>
			<input type="text" name="ds_name" value="<?=htmlspecialchars($ds_name, ENT_COMPAT, 'UTF-8')?>" size="64" maxlength="64" onpropertychange="dsb()">
		</div>
		<div class="property"<?=is_valid('description')?>>
			<span><?=$uf['description']?>:</span>
			<textarea cols="51" rows="5" name="description"><?=$description?></textarea>
		</div>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=DS_PROPS?></legend>
		<table style="margin:5 0" cellspacing="3">
			<tr>
				<td><?=DS_SIZE?>:</td>
				<td><input type="text" name="dssize" size="5" maxlength="4" value="<?=$_ds->size?>"> <?=DS_RECORDS?></td>
			</tr>
		</table>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE_CHANGES_DS?>" name="create"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>