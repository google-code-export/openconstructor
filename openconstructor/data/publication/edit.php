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
 * $Id: edit.php,v 1.11 2007/03/02 10:06:44 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');

	if(!isset($_GET['ds_id'])||!isset($_GET['id'])) die();
	require_once($_SERVER['DOCUMENT_ROOT'].WCHOME.'/include/toolbar._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = &$dsm->load($_GET['ds_id']);
	if($_GET['id'] != 'new') {
		$_doc = $_ds->get_record($_GET['id']);
		assert($_doc !== null);
		if($_doc['id']!= $_doc['real_id']) {
			$_ds = &$dsm->load($_doc['realDsId']);
			$_doc = &$_ds->get_record($_doc['real_id']);
		}
	} else {
		$_doc['date'] = time();
		$_doc['real_id'] = null;
	}
	$m = array();
	preg_match_all('~<([A-Z0-9]+)~', strtoupper($_ds->allowedTags), $m, PREG_PATTERN_ORDER);
	$allowed = (array) @$m[1];
	$sDoc = $_ds->wrapDocument($_doc);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?=$_ds->name?> | <?=$_GET['id'] == 'new' ? CREATE_PUBLICATION : htmlspecialchars($_doc['header']).' | '.EDIT_PUBLICATION.($_doc['id']!=$_doc['real_id'] ? DOCUMENT_IS_ALIAS : '')?></title>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
<script>
	var
		host='<?=$_host?>',
		skin='<?=SKIN?>'
		;
	function sendData()
	{
		if(content.source) {theHTML=content;editsource();}
		if(introcontent.source) {theHTML=introcontent;editsource();}
		repairHRefs(content);
		repairHRefs(introcontent);
		f.html.value=content.document.body.innerHTML;
		f.intro.value=introcontent.document.body.innerHTML;
		content.document.body.disabled=true;
		content.document.designMode="Off";
		introcontent.document.body.disabled=true;
		introcontent.document.designMode="Off";
		disableButton(btn_save,'<?=WCHOME?>/i/default/e/save_.gif');
		f.header.onpropertychange='';
		f.submit();
	}
	function deleteimg(type)
	{
		if(!mopen("../../confirm.php?q=<?=urlencode(CONFIRM_REMOVE_IMAGE_Q)?>"+"&skin="+skin,350,150)) return;
		var d=new Date();
		if(!mopen("../deleteimg.php?ds=<?=$_ds->ds_id?>&id=<?=$_doc['real_id']?>&type="+type+'&j='+Math.ceil(d.getTime()/1000),350,150)) return;
		disableButton(document.all('btn_delimg'+type),"<?=WCHOME?>/i/default/e/deleteimg_.gif");
		document.all('ref_showimg'+type).outerHTML=document.all('ref_showimg'+type).innerHTML;
	}
</script>
<script src="../editor.js"></script>
</head>
<body style="border:groove;border-width:2;margin:10;" ondrag="return false">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="99%">
<tr><td style="padding-bottom:5px">
<form method="POST" style="margin:0;padding:0" enctype="multipart/form-data" name="f" action="i_publication.php">
<textarea name="html" style="display:none"><?=$_GET['id'] == 'new' ? '' : htmlspecialchars($_doc['content'], ENT_COMPAT, 'UTF-8')?></textarea>
<textarea name="intro" style="display:none"><?=$_GET['id'] == 'new' ? '' : htmlspecialchars($_doc['intro'], ENT_COMPAT, 'UTF-8')?></textarea>
<input type=hidden name="action" value="<?=$_GET['id']=='new'?'create':'edit'?>_publication">
<input type=hidden name="id" value="<?=$_doc['real_id']?>">
<input type="hidden" name="ds_id" value="<?=$_ds->ds_id?>">
<input type="hidden" name="hybridid" value="<?=@$_GET['hybridid']?>">
<input type="hidden" name="fieldid" value="<?=@$_GET['fieldid']?>">
<input type="hidden" name="callback" value="<?=@$_GET['callback']?>">
<input type="hidden" name="main_publication" value="no">
<input type="hidden" name="autointro" value="false">
<?=F_PUBLICATION_HEADER?>:<input type="button" accesskey="s" onclick="if(!document.all('btn_save').dbtn) sendData()" value="save" style="width:0px;height:0px;"><br>
<textarea style="width:100%" name="header" rows=2 cols=85 onpropertychange="if(!this.value.match(new RegExp('[^\\s]','gi'))) disableButton(btn_save,'<?=WCHOME?>/i/default/e/save_.gif'); else disableButton(btn_save,false)"><?=htmlspecialchars(@$_doc['header'], ENT_COMPAT, 'UTF-8')?></textarea>
<table cellspacing="0" cellpadding="0" style="margin-top:3px;" width="100%">
<tr><td nowrap style="padding-right:15px;" valign="top"><?=F_DATE_TIME?>:<br>
<?php
	list($day, $month, $year) = explode('/', date('d/m/Y', $_doc['date']));
	echo '<input type="text" name="day" size="3" maxlength="2" value="'.$day.'">';
	echo '<select size=1 name="month">';
	for($i=1;$i<=12;$i++)
	{
		echo '<option value='.$i;
		if($i==$month) echo ' selected';
		echo '>'.constant('MONTH_'.$i);
	}
	echo '</select>';
	echo '<select size=1 name="year">';
	for($i=1999;$i<=2010;$i++)
	{
		echo '<option value='.$i;
		if($i==$year) echo ' selected';
		echo '>'.$i;
	}
	echo '</select>';
?>&nbsp;<input type="text" name="time" size="8" maxlength="8" value="<?=date('H:i:s', $_doc['date'])?>"></td>
<td nowrap colspan=2 width="100%">
<br><input type=checkbox <?=@$_doc['main']?' checked was=1':''?> onclick="f.main_publication.value=this.checked?'yes':(this.was?'was':'no')" value="true"> <?=F_IS_MAIN_PUBLICATION?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox <?=@$_doc['published']?' checked':''?> <?=!WCS::decide($_ds, 'publishdoc') || $_GET['id']=='new' ? 'disabled' : ''?> name="published" value="true"> <?=F_IS_PUBLISHED?></td>
</tr>
<?php if($_GET['id']!='new') {
		if(@$_ds->images['main']||$_ds->images['intro']) echo '<tr><td colspan=3><hr style="margin:0px;padding:0px;"></td></tr>';
?>
<tr><td nowrap valign="top">
<?php if($_GET['id']!='new'&&@$_ds->images['main']) {
if(@file_exists($_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.($_doc['real_id']?$_doc['real_id']:$_doc['id']).'.'.$_doc['img_type']))
{
	list($w,$h)=explode(',',preg_replace('/(.*width=")(\d*)(".*height=")(\d*)(".*)/mi','\\2,\\4',$_doc['img_main']));
	echo '<a href="#" name="ref_showimgmain" onclick="window.open(\''.FILES.$_ds->imagepath.($_doc['real_id']?$_doc['real_id']:$_doc['id']).'.'.$_doc['img_type'].'\',\'\',\'resizable=yes, scrollbars=no, status=no, height='.($h+30).', width='.($w+20).'\');return false" title="'.TT_SHOW_CURRENT_IMAGE.'">'.F_IMAGEMAIN.'</a>';
}
else
	echo F_IMAGEMAIN;?>:<br>
<input type="file" name="image">&nbsp;&nbsp;<a href="javascript:deleteimg('main')" class="tool1"><img src="<?=WCHOME?>/i/default/e/deleteimg.gif" align=absmiddle alt="<?=BTN_REMOVE_IMAGE?>" name="btn_delimgmain"></a>
<?php }?></td>
<td nowrap valign="top">
<?php if($_GET['id']!='new'&&$_ds->images['intro']) {
echo '<input type="checkbox" name="manual_img" value="true" checked onclick="f.image_intro.disabled=!this.checked" align="bottom" style="margin:-1px 1px 0px"></td><td width="100%">';
if(@file_exists($_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.($_doc['real_id']?$_doc['real_id']:$_doc['id']).'_.'.$_doc['img_type']))
{
	list($w,$h)=explode(',',preg_replace('/(.*width=")(\d*)(".*height=")(\d*)(".*)/mi','\\2,\\4',$_doc['img_intro']));
	echo '<a href="#" name="ref_showimgintro" onclick="window.open(\''.FILES.$_ds->imagepath.($_doc['real_id']?$_doc['real_id']:$_doc['id']).'_.'.$_doc['img_type'].'\',\'\',\'resizable=yes, scrollbars=no, status=no, height='.($h+30).', width='.($w+20).'\');return false" title="'.TT_SHOW_CURRENT_IMAGE.'">'.F_IMAGEINTRO.'</a>';
}
else
	echo F_IMAGEINTRO;?>:<br>
<input type="file" name="image_intro">&nbsp;&nbsp;<a href="javascript:deleteimg('intro')" class="tool1"><img src="<?=WCHOME?>/i/default/e/deleteimg.gif" align=absmiddle alt="<?=BTN_REMOVE_IMAGE?>" name="btn_delimgintro"></a>
<?php } if(@$_doc['gallery']){?>&nbsp;&nbsp;<a href="javascript:wxyopen('../internal_ds.php?node=<?=@$_doc['gallery']?>',800,600)"><img src="<?=WCHOME?>/i/<?=SKIN?>/f/imagefolder.gif" border=0 align=absbottom alt="<?=F_TT_ATTACHED_GALLERY?>" hspace=5><?=F_ATTACHED_GALLERY?></a><?php }?>
</td>
</tr>
<script>if(!document.all('ref_showimgmain')&&f.image) disableButton(f.btn_delimgmain,"<?=WCHOME?>/i/default/e/deleteimg_.gif");if(!document.all('ref_showimgintro')&&f.image_intro) disableButton(f.btn_delimgintro,"<?=WCHOME?>/i/default/e/deleteimg_.gif");</script>
<?php }?>
</table>
</form>
<hr size="2">
<table width="100%" cellpadding="0" cellspacing="0" border="0" height="24"><tr><td align="left">
<nobr>
<?php
	$save = @$_GET['hybridid'] > 0 || ($_GET['id'] == 'new' ? WCS::decide($_ds, 'createdoc') : WCS::decide($_ds, 'editdoc') || WCS::decide($sDoc, 'editdoc'));
	toolbar(array(
		BTN_NEW_DOCUMENT=>array('pic'=>'newdocument','action'=>'window.location.assign("?id=new&ds_id='.$_GET['ds_id'].'")'),
		BTN_SAVE=>array('pic'=>'save','action'=>$save ? 'sendData()' : ''),
		'separator_',
		BTN_CUT=>array('pic'=>'cut','action'=>'excmd("Cut")'),
		BTN_COPY=>array('pic'=>'copy','action'=>'excmd("Copy")'),
		BTN_PASTE=>array('pic'=>'paste','action'=>'excmd("Paste")'),
		BTN_REMOVE_FORMAT=>array('pic'=>'unformat','action'=>'excmd("RemoveFormat")'),
		BTN_REMOVE_STYLES=>array('pic'=>'removecss','action'=>'removeCSS()'),
		'separator_',
		BTN_INSERT_IMAGE=>array('pic'=>'image','action'=>array_search('IMG',$allowed)!==false?'insertImage()':''),
		BTN_INSERT_LINK=>array('pic'=>'link','action'=>'excmd("CreateLink")'),
		'separator_',
		BTN_BOLD=>array('pic'=>'bold','action'=>'excmd("Bold")'),
		BTN_ITALIC=>array('pic'=>'italic','action'=>'excmd("Italic")'),
		'separator_',
		BTN_ALIGN_LEFT=>array('pic'=>'left','action'=>'excmd("JustifyLeft")'),
		BTN_ALIGN_CENTER=>array('pic'=>'center','action'=>'excmd("JustifyCenter")'),
		BTN_ALIGN_RIGHT=>array('pic'=>'right','action'=>'excmd("JustifyRight")'),
		'separator_',
		BTN_INDENT=>array('pic'=>'indent','action'=>'excmd("Indent")'),
		BTN_OUTDENT=>array('pic'=>'outdent','action'=>'excmd("Outdent")'),
		'br',
		'separator_',
		BTN_INSERT_UL=>array('pic'=>'ulist','action'=>'excmd("InsertUnorderedList")'),
		BTN_INSERT_OL=>array('pic'=>'olist','action'=>'excmd("InsertOrderedList")'),
		'separator_',
		BTN_EDIT_STYLE=>array('pic'=>'style','action'=>'editStyle()'),
		BTN_EDIT_TAG_PROPS=>array('pic'=>'attribute','action'=>'editProps()'),
		BTN_EDIT_SOURCE=>array('pic'=>'editsrc','action'=>'editsource()'),
		BTN_IMPORT_TABLE=>array('pic'=>'importtbl','action'=>'editTable()')
	));
?><IMG SRC="<?=WCHOME?>/i/default/e/separator.gif" align="top"> <SELECT size=1 id="tagID" align="absmiddle"><?php
	foreach(explode(',','H1,H2,H3,H4,DIV,SPAN,NOBR') as $tag)
		if(array_search($tag,$allowed)!==false)
			echo '<OPTION value="'.$tag.'">&lt;'.$tag.'&gt;';
?></SELECT> <input align="top" type="button" value="<?=BTN_INSERT_TAG?>" onclick="if(tagID.options.length) intoTags(tagID.options(tagID.selectedIndex).value)" style="width:80px;margin-top:0px;">
</nobr>
</td></tr></table>
<?=$_GET['id']!='new'?'':'<script>disableButton(btn_save,"'.WCHOME.'/i/default/e/save_.gif");</script>'?>
</td></tr><tr height="66%" valign="top"><td>
<iframe name="content" style="border:none" width="100%" style="margin:0 0" height="100%" onfocus="theHTML=content"></iframe>
</td></tr><tr><td style="padding:2px 0px">
<nobr><input type="checkbox" name="autointro" onclick="if(this.checked){f.autointro.value='true'}else{f.autointro.value='false'}"> <?=F_AUTOGENERATE_INTRO?></nobr>
</td></tr><tr height="33%" valign="top"><td>
<iframe name="introcontent" style="border:none" width="100%" style="margin:0" height="100%" onfocus="theHTML=introcontent"></iframe>
</td></tr><table>
<script defer>
	theHTML=content;
	content.document.designMode="On";
	introcontent.document.designMode="On";
	window.inter=window.setInterval(setcontent,500);
	function setcontent(a)
	{
		if(content.document.body)
		{
			window.clearInterval(window.inter);
			content.document.body.innerHTML=f.html.value;
			content.document.body.onkeypress=new Function('',"if(window.content.event.keyCode==28) {window.parent.editsource()}");
			content.focus();
			head=content.document.childNodes(0).childNodes(0);
			link=content.document.createElement('<link href="http://<?=$_host?>/css/content.css" type=text/css rel=stylesheet>');
			head.appendChild(link);
			content.document.body.style.background='white';
			content.document.body.style.margin='5px';
		}
	}
	window.inter1=window.setInterval(setcontent1,500);
	function setcontent1(a)
	{
		if(introcontent.document.body)
		{
			window.clearInterval(window.inter1);
			introcontent.document.body.innerHTML=f.intro.value;
			introcontent.document.body.onkeypress=new Function('',"if(window.introcontent.event.keyCode==28) {window.parent.editsource()}");
			head=introcontent.document.childNodes(0).childNodes(0);
			link=introcontent.document.createElement('<link href="http://<?=$_host?>/css/content.css" type=text/css rel=stylesheet>');
			head.appendChild(link);
			introcontent.document.body.style.background='white';
			introcontent.document.body.style.margin='5px';
		}
	}
</script>
</body>
</html>