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
 * $Id: create_hybrid.php,v 1.9 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	//userfriendly names
	$uf['ds_name']=DS_NAME;
	$uf['ds_key']=DS_KEY;
	$uf['description']=DS_DESCRIPTION;
	//default values
	$ds_key='';
	$ds_name='';
	$description='';
	//read values that have not been saved
	read_fail_header();
	
	$_dsm=new DSManager();
	$ds = $_dsm->getAll('hybrid');
?>
<html>
<head>
<title><?=WC.' | '.CREATE_DS_HYBRID?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
var rek=/^[a-zA-Z][a-zA-Z0-9]{0,15}$/g;
function dsb(){
	if(!f.ds_key.value.match(rek)||!f.ds_name.value.match(re)||<?=System::decide('data.dshybrid') ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=CREATE_DS_HYBRID?></h3>
<?php
	report_results(CREATE_DS_FAILED_W);
?>
<form name="f" method="POST" action="i_data.php">
	<input type="hidden" name="action" value="create_dshybrid">
	<fieldset style="padding:10"><legend><?=DS_GENERAL_PROPS?></legend>
		<div class="property"<?=is_valid('ds_key')?>>
			<span><?=$uf['ds_key']?>:</span>
			<input type="text" name="ds_key" value="<?=htmlspecialchars($ds_key, ENT_COMPAT, 'UTF-8')?>" size="64" maxlength="64" onpropertychange="dsb()">
		</div>
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
		<?=DS_PARENT?>:
		<select name="parent" size="1" align="absmiddle">
			<option value="0">-
<?php
	foreach($ds as $v)
		echo '<OPTION VALUE="'.$v['id'].'"'.($v['id'] == @$_GET['in']?' SELECTED':'').'>'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',substr_count($v['path'],',') - 1).
			$v['name'];
?>
		</select>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_CREATE_DS?>" name="create"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>