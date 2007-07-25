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
 * $Id: edit.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	if(!isset($_GET['ds_id'])||!isset($_GET['id'])) die();
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = &$dsm->load($_GET['ds_id']);
//	require_once($_SERVER['DOCUMENT_ROOT'].WCHOME.'/include/toolbar._wc');
	//userfriendly names
	$uf['name']=F_FILE_HEADER;
	$uf['description']=H_FILE_DESCRIPTION;
	$uf['fname']=F_FILENAME_REMOTE;
	//default values
	$name='';
	$description='';
	$fname='';
	if($_GET['id'] != 'new') {
		$_doc = $_ds->get_record($_GET['id']);
		assert($_doc != null);
		$name=$_doc['name'];
		$description = $_doc['description'];
		$fname=basename($_doc['filename']);
	}
	//read values that have not been saved
	read_fail_header();
	$allowedTypes = sizeof($_ds->filetypes) ? implode(', ', $_ds->filetypes) : F_FILETYPE_UNKNOWN;
	$sDoc = $_ds->wrapDocument($_doc);
	$save = @$_GET['hybridid'] > 0 || ($_GET['id'] == 'new' ? WCS::decide($_ds, 'createdoc') : WCS::decide($_ds, 'editdoc') || WCS::decide($sDoc, 'editdoc'));
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?=$_ds->name?> | <?=$_GET['id']!='new' ? htmlspecialchars($_doc['name'], ENT_COMPAT, 'UTF-8').' | '.EDIT_FILE : CREATE_FILE?></title>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
	<script>
	var re=new RegExp('[^\\s]','gi');
	var refn = <?=sizeof($_ds->filetypes) ? 'true' : 'false' ?> ? /^.+\.(<?=implode('|', $_ds->filetypes)?>)$/i : /^[^\.]+$/i;
	var valid='abcdefghijklmnopqrstuvwxyz0123456789_.-';
	var alph=	'йцукенгшщзхфывапролджэячсмитбю ЙЦУКЕНГШЩЗХФЫВАПРОЛДЖЭЯЧСМИТБЮ';
	var trans=	' y c u k e n gshsh z x f i v a p r o l d j eyach s m i t byu _';
	function dsb(){
		if(!f.name.value.match(re)||!(f.fname.disabled ? f.file.value.substr(f.file.value.lastIndexOf('\\') + 1).match(refn) : f.fname.value.match(refn))||<?=@$_GET['id']=='new'?'!f.file.value.match(re)||':''?><?= $save ? 'false' : 'true'?>)
		f.create.disabled=true; else f.create.disabled=false;
	}
	function generate(){
		text=f.fname.value;
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
		f.fname.value=fname;
	}
	function autoname()
	{
		if(f.fname.manual==1||!f.file.value) return;
		fn=f.file.value;
		pos=fn.lastIndexOf('\\');
		f.fname.value=fn.substr(pos+1).toLowerCase();
	}
	function checkform()
	{
<?php if($_GET['id']!='new'){?>
		fn = f.fname.value;
		pos = fn.lastIndexOf('.');
//		if(pos<1||!(pos+1<fn.length)) return false;
		f.type.value = fn.substr(pos + 1).toLowerCase();
		if(f.type.value!=f.oldtype.value)
		{
			alert('<?=INCONSISTENCY_BETWEEN_FILETYPES?>');
			return false;
		}
		if(!f.file.value)
			return true;
		fn=f.file.value;
		pos=fn.lastIndexOf('.');
		if(pos<1||!(pos+1<fn.length)) return false;
		f.type.value=fn.substr(pos+1,fn.length-pos-1).toLowerCase();
		if(f.type.value!=f.oldtype.value)
		{
			alert('<?=INCONSISTENCY_BETWEEN_FILETYPES?>');
			return false;
		}
		return true;
<?php }else{?>
		if(!f.file.value || f.file.value.substr(f.file.value.length - 1) == "\\") return false;
		if(!f.fname.disabled && f.fname.value.indexOf('.') >= 0)
			fn = f.fname.value;
		else {
			pos = f.file.value.lastIndexOf('\\');
			fn = f.file.value.substr(pos + 1).toLowerCase();
		}
		pos = fn.lastIndexOf('.');
		f.type.value= pos == -1 ? '' : fn.substr(pos + 1).toLowerCase();
		return true;
<?php }?>
	}
	</script>
	</head>
<body style="border:groove;border-width:2;margin:10;" ondrag="return false">
<nobr><h3 style="cursor:default">&nbsp;<?=$_GET['id']!='new'?EDIT_FILE:CREATE_FILE?></h3></nobr>
<?php
	report_results(CREATE_FILE_FAILED_W,CREATE_FILE_SUCCES_I.(isset($ok)?@$result:''));
?>
<form method="POST" style="margin:0;padding:0" enctype="multipart/form-data" name="f" action="i_file.php" onsubmit="return checkform()">
<input type=hidden name="action" value="<?=$_GET['id']=='new'?'create':'edit'?>_file">
<input type=hidden name="id" value="<?=$_GET['id']?>">
<input type="hidden" name="ds_id" value="<?=$_GET['ds_id']?>">
<input type="hidden" name="hybridid" value="<?=@$_GET['hybridid']?>">
<input type="hidden" name="fieldid" value="<?=@$_GET['fieldid']?>">
<input type="hidden" name="callback" value="<?=@$_GET['callback']?>">
<input type=hidden name="type">
	<fieldset style="padding:10"><legend><?=H_GENERAL_PROPS?></legend>
		<div class="property"<?=is_valid('name')?>>
			<span><?=$uf['name']?>:</span>
			<textarea cols="51" rows="2" name="name" onpropertychange="dsb()"><?=@htmlspecialchars($name, ENT_COMPAT, 'UTF-8')?></textarea>
		</div>
		<div class="property"<?=is_valid('description')?>>
			<span><?=$uf['description']?>:</span>
			<textarea cols="51" rows="5" name="description"><?=@htmlspecialchars($description, ENT_COMPAT, 'UTF-8')?></textarea>
		</div>
		<div class="property">
			<span><?=@$_doc['filename']?'<A HREF="'.htmlspecialchars($_doc['filename'], ENT_COMPAT, 'UTF-8').'" target=_blank>'.F_FILENAME_LOCAL.'</A>':F_FILENAME_LOCAL?>:</span>
			<input type="file" name="file" onpropertychange="autoname();dsb()">
			<div class="tip" style="display:<?=$_GET['id']=='new' && $_ds->autoName ? 'block' : 'none'?>;"><?=DS_ALLOWED_FILETYPES?>: <b><?=$allowedTypes?></b></div>
		</div>
		<div class="property"<?=is_valid('fname')?> style="display:<?=$_GET['id']=='new' && $_ds->autoName ? 'none' : 'block'?>;">
			<span><?=$uf['fname']?>:</span>
			<div class="tip"><?=DS_ALLOWED_FILETYPES?>: <b><?=$allowedTypes?></b></div>
			<input type="text" name="fname"<?=@$_GET['id']!='new'?' manual=1':''?> value="<?=htmlspecialchars($fname, ENT_COMPAT, 'UTF-8')?>" size="64" maxlength="64" <?=$_GET['id']=='new' && $_ds->autoName ? 'disabled' : ''?> onfocus="this.isfocus=true" onblur="this.isfocus=false;if(this.value=='') autoname();" onpropertychange="if(this.value>''){if(this.manual!=1&&this.isfocus) this.manual=1;}else{if(this.manual!=0&&this.isfocus)this.manual=0;}dsb()">
			<input type="button" class="def" value="<?=BTN_CORRECT_FILENAME?>" name="btnvalid" onclick="generate()" style="float:right">
			<div class="tip" align="right"><?=TT_CORRECT_FILENAME?></div>
		</div>
	</fieldset><br>

<?php if($_GET['id']!='new'){?>
	<fieldset style="padding:10"><legend><?=H_PROPS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=F_FILETYPE?>:</td>
			<td><input type="text" name="oldtype" value="<?=$_doc['type']?>" DISABLED></td>
		</tr>
		<tr>
			<td><?=F_FILESIZE?>:</td>
			<td><input type="text" name="size" value="<?=$_doc['size']?>" DISABLED></td>
		</tr>
		<tr>
			<td nowrap><?=F_FILEURL?>:</td>
			<td><input type="text" name="filename" size="40" value="<?='http://'.$_SERVER['HTTP_HOST'].$_doc['filename']?>" DISABLED> <input type="button" value="<?=BTN_COPY_TO_CLIPBOARD?>" onclick="f.filename.disabled=false;f.filename.select();t=document.selection.createRange();t.execCommand('Copy',true);document.selection.empty();f.filename.disabled=true;"></td>
		</tr>
	</table>
	</fieldset><br>
<?php }?>
	<div align="right">
		<?php if($_GET['id'] != 'new') {?>
			<input type="button" value="<?=BTN_NEW_FILE?>" name="new" style="float: left;" onclick="window.location.href = window.location.href.replace(/((?:\?|&)id)=\d+(&|$)/i, '$1=new$2');">
		<?php }?>
		<input type="submit" value="<?=BTN_SAVE?>" name="create">
		<input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()">
	</div>
</form>
<script>dsb()</script>
</body>
</html>