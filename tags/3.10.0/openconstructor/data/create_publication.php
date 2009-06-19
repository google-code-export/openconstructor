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
 * $Id: create_publication.php,v 1.5 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	//userfriendly names
	$uf['ds_name']=DS_NAME;
	$uf['description']=DS_DESCRIPTION;
	//default values
	$ds_name='';
	$description='';
	//read values that have not been saved
	read_fail_header();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=WC.' | '.CREATE_DS_PUBLICATION?></title>
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.ds_name.value.match(re)||<?=System::decide('data.dspublication') ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
}
</script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=CREATE_DS_PUBLICATION?></h3>
<?php
	report_results(CREATE_DS_FAILED_W);
?>
<form name="f" method="POST" action="i_data.php">
	<input type="hidden" name="action" value="create_dspublication">
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
		<tr>
			<td><?=DS_INTROSIZE?>:</td>
			<td><input type="text" name="introsize" size="5" maxlength="4"> <?=DS_CHARS?></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=DS_SEARCH?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><input type="checkbox" name="isindexable" checked> <?=IS_INDEXABLE?></td><td></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=DS_GRAPH_PROPS?></legend>
		<fieldset style="padding:10"><legend><?=DS_GRAPH_BOUNDS?></legend>
		<table style="margin:5 0" cellspacing="3">
			<tr>
				<td><?=DS_GRAPH_MIN_RECT?>:</td>
				<td nowrap valign="top"><?=DS_GRAPH_WIDTH?> <input type="text" name="xmin" size="4" maxlength="4">&nbsp;&nbsp;<?=DS_GRAPH_HEIGHT?> <input type="text" name="ymin" size="4" maxlength="4"></td>
			</tr>
			<tr>
				<td><?=DS_GRAPH_MAX_RECT?>:</td>
				<td nowrap><?=DS_GRAPH_WIDTH?> <input type="text" name="xmax" size="4" maxlength="4">&nbsp;&nbsp;<?=DS_GRAPH_HEIGHT?> <input type="text" name="ymax" size="4" maxlength="4"></td>
			</tr>
		</table>
		</fieldset><br>
		<fieldset style="padding:10"><legend><?=DS_GRAPH_IMAGES?></legend>
		<table style="margin:5 0" cellspacing="3" width="100%">
			<tr>
				<td nowrap><input type="checkbox" name="img_main"> <?=DS_GRAPH_IMAGEMAIN?></td><td></td>
			</tr>
			<tr>
				<td colspan="2"><hr size="1"></td>
			</tr>
			<tr>
				<td nowrap valign="top"><input type="checkbox" name="img_intro" onclick="f.intro.disabled=!this.checked" value="enabled"> <?=DS_GRAPH_IMAGEINTRO?></td>
				<td></td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;<?=DS_GRAPH_IMAGEINTRO_SIZE?>:</td>
				<td width="100%"><input type="text" name="intro" size="4" maxlength="3" disabled><?=DS_GRAPH_PERCENT_10_100?></td>
			</tr>
		</table>
		</fieldset><br>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=DS_ATTACHED_GALLERY?></legend>
		<br><input type="checkbox" name="attach_gallery" onclick="document.all.ag.style.display=this.checked?'block':'none'" value="true"> <?=DS_AUTO_ATTACH_GALLERY?>
		<fieldset style="padding:10;display:none;" id="ag"><legend><?=DS_PROPS?></legend>
			<fieldset style="padding:10"><legend><?=DS_GRAPH_BOUNDS?></legend>
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td><?=DS_GRAPH_MIN_RECT?>:</td>
					<td nowrap valign="top"><?=DS_GRAPH_WIDTH?> <input type="text" name="g_xmin" size="4" maxlength="4">&nbsp;&nbsp;<?=DS_GRAPH_HEIGHT?> <input type="text" name="g_ymin" size="4" maxlength="4"></td>
				</tr>
				<tr>
					<td><?=DS_GRAPH_MAX_RECT?>:</td>
					<td nowrap><?=DS_GRAPH_WIDTH?> <input type="text" name="g_xmax" size="4" maxlength="4">&nbsp;&nbsp;<?=DS_GRAPH_HEIGHT?> <input type="text" name="g_ymax" size="4" maxlength="4"></td>
				</tr>
			</table>
			</fieldset><br>
			<fieldset style="padding:10"><legend><?=DS_GRAPH_IMAGES?></legend>
			<table style="margin:5 0" cellspacing="3" width="100%">
				<tr>
					<td nowrap><input type="checkbox" name="g_img_main"> <?=DS_GRAPH_IMAGEMAIN?></td><td></td>
				</tr>
				<tr>
					<td colspan="2"><hr size="1"></td>
				</tr>
				<tr>
					<td nowrap valign="top"><input type="checkbox" name="g_img_intro" onclick="f.g_intro.disabled=!this.checked" value="enabled"> <?=DS_GRAPH_IMAGEINTRO?></td>
					<td></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<?=DS_GRAPH_IMAGEINTRO_SIZE?>:</td>
					<td width="100%"><input type="text" name="g_intro" size="4" maxlength="3" disabled><?=DS_GRAPH_PERCENT_10_100?></td>
				</tr>
			</table>
			</fieldset><br>
		</fieldset><br>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_CREATE_DS?>" name="create"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
	<br><br><br>
</form>
<script>dsb();</script>
</body>
</html>