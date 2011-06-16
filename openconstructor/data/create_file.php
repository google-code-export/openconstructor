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
 * $Id: create_file.php,v 1.6 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	//userfriendly names
	$uf['ds_name']=DS_NAME;
	$uf['description']=DS_DESCRIPTION;
	$uf['folder']=DS_FOLDER_NAME;
	//default values
	$ds_name='';
	$description='';
	$folder='';
	//read values that have not been saved
	read_fail_header();
?>
<html>
<head>
<title><?=WC.' | '.CREATE_DS_FILE?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
var valid='abcdefghijklmnopqrstuvwxyz0123456789-_.';
var alph='йцукенгшщзхфывапролджэячсмитбю ЙЦУКЕНГШЩЗХФЫВАПРОЛДЖЭЯЧСМИТБЮ';
var trans=' y c u k e n gshsh z x f i v a p r o l d j eyach s m i t byu _';
function dsb(){
	if(!f.ds_name.value.match(re)||(false && !f.folder.value.match(re))||<?=System::decide('data.dsfile')?'false':'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
function generate(){
	text=f.folder.value;
	fname='';
	for(var i=0; i<text.length; i++){
		cur=text.charAt(i);
		if(valid.indexOf(cur)<0){
			p=alph.indexOf(cur);
			if(p>=0){
				p=p%31;
				cur=trans.substr(p*2,2);
				if(cur.charAt(0)==' ')
					cur=cur.substr(1);
			} else
				cur='';
		}
		fname+=cur;
	}
	f.folder.value=fname;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=CREATE_DS_FILE?></h3>
<?php
	report_results(CREATE_DS_FAILED_W);
?>
<form name="f" method="POST" action="i_data.php">
	<input type="hidden" name="action" value="create_dsfile">
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
				<td><input type="text" name="dssize" size="5" maxlength="4"> <?=DS_RECORDS?></td>
			</tr>
		</table>
		<div class="property"<?=is_valid('folder')?>>
			<span><?=$uf['folder']?>:</span>
			<div class="tip"><?=DS_TT_FOLDER_NAME?></div>
			<input type="text" value="<?=$folder?>" name="folder" size="32" maxlength="32" onpropertychange="dsb()">
			<input type="button" class="def" value="<?=BTN_CORRECT_FOLDER_NAME?>" onclick="generate()" style="float:right">
			<div class="tip" align="right"><?=DS_TT_CORRECT_FOLDER_NAME?></div>
		</div>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_CREATE_DS?>" name="create"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
<script>dsb();</script>
</body>
</html>