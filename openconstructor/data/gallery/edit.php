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
 * $Id: edit.php,v 1.9 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');

	if(!isset($_GET['ds_id'])||!isset($_GET['id'])) die();
	require_once($_SERVER['DOCUMENT_ROOT'].WCHOME.'/include/toolbar._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = $dsm->load($_GET['ds_id']);
	if($_GET['id']!='new')
	{
		$_doc=$_ds->get_record($_GET['id']);
		assert($_doc !== null);
		if($_doc['id']!= $_doc['real_id']) {
			$_ds = $dsm->load($_doc['realDsId']);
			$_doc = $_ds->get_record($_doc['real_id']);
		}
	} else {
		$_doc=array(
			'id'=>'new',
			'img_type'=>'type',
			'real_id'=>NULL
		);
	}
	$m = array();
	preg_match_all('~<([A-Z0-9]+)~', strtoupper($_ds->allowedTags), $m, PREG_PATTERN_ORDER);
	$allowed = (array) @$m[1];
	$sDoc = $_ds->wrapDocument($_doc);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?=$_ds->name?> | <?=$_GET['id']=='new' ? CREATE_IMAGE : htmlspecialchars($_doc['header'], ENT_COMPAT, 'UTF-8').' | '.EDIT_IMAGE.($_doc['id']!=$_doc['real_id']?DOCUMENT_IS_ALIAS:'')?></title>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
<script src="/openconstructor/lib/js/base.js"></script>
<script src="/openconstructor/lib/js/widgets.js"></script>
<script>
	var
		host='<?=$_host?>',
		skin='<?=SKIN?>'
		;
	function init() {
		new CalendarWidget(document.getElementById('date'),document.getElementById('btn.date'),2000,2010,"calendar","<?=LANGUAGE?>");
	}
	function sendData()
	{
		if(content.source) {theHTML=content;editsource();}
		repairHRefs(content);
		f.html.value=content.document.body.innerHTML;
		content.document.body.disabled=true;
		content.document.designMode="Off";
		disableButton(f.btn_save,'<?=WCHOME?>/i/e/save_.gif');
		f.header.onpropertychange='';
		try{
			f.onsubmit();
		} catch(e) {
		}
		f.submit();
	}
	function deleteimg(type)
	{
		if(!mopen("../../confirm.php?q=<?=urlencode(CONFIRM_REMOVE_IMAGE_Q)?>?"+"&skin="+skin,350,150)) return;
		var d=new Date();
		if(!mopen("../deleteimg.php?ds=<?=$_ds->ds_id?>&id=<?=$_doc['real_id']?>&type="+type+'&j='+Math.ceil(d.getTime()/1000),350,150)) return;
		disableButton(document.all('btn_delimg'+type),"<?=WCHOME?>/i/default/e/deleteimg_.gif");
		document.all('img_'+type).src='showimage.php?img=new.type';
	}
</script>
<script src="../editor.js"></script>
<style>
	@import url(/openconstructor/lib/js/api.css);
	IMG.btn {
		width:24px;
		height:24px;
		cursor:pointer;
	}
</style>
</head>
<body style="border:groove;border-width:2;margin:10;" ondrag="return false" onload="init();">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="99%">
<tr><td style="padding-bottom:5px">
<form method="POST" style="margin:0;padding:0" enctype="multipart/form-data" name="f" action="i_gallery.php">
<textarea name="html" style="display:none"><?=$_GET['id']=='new'?'':htmlspecialchars($_doc['content'], ENT_COMPAT, 'UTF-8')?></textarea>
<input type=hidden name="action" value="<?=$_GET['id']=='new'?'create':'edit'?>_image">
<input type=hidden name="id" value="<?=$_doc['real_id']?>">
<input type="hidden" name="ds_id" value="<?=$_ds->ds_id?>">
<input type="hidden" name="hybridid" value="<?=@$_GET['hybridid']?>">
<input type="hidden" name="fieldid" value="<?=@$_GET['fieldid']?>">
<input type="hidden" name="callback" value="<?=@$_GET['callback']?>">
<?=F_IMAGE_HEADER?>:<input type="button" accesskey="s" onclick="if(!document.all('btn_save').dbtn) sendData()" value="save" style="width:0px;height:0px;"><br>
<textarea style="width:100%" name="header" rows=2 cols=85 onpropertychange="if(!this.value.match(new RegExp('[^\\s]','gi'))) disableButton(btn_save,'<?=WCHOME?>/i/default/e/save_.gif'); else disableButton(btn_save,false)"><?=htmlspecialchars(@$_doc['header'], ENT_COMPAT, 'UTF-8')?></textarea>
<div style="padding: 5px 0px 0px 0px;">
<?=F_DATE_TIME?>: <input type="text" name="date" value="<?=date("d/m/Y H:i:s", @$_doc['date']?$_doc['date']:time())?>" size="25"> <img align="absmiddle" src="<?=WCHOME?>/i/default/e/h/calendar.gif" class="btn" alt="<?=H_SELECT_VALUE?>" id="btn.date">
</div>
<hr size="2">
<table width="100%" cellpadding="0" cellspacing="0" border="0" height="24"><tr><td align="left">
<nobr>
<?php
	$save = @$_GET['hybridid'] > 0 || ($_GET['id'] == 'new' ? WCS::decide($_ds, 'createdoc') : WCS::decide($_ds, 'editdoc') || WCS::decide($sDoc, 'editdoc'));
	toolbar(array(
		BTN_NEW_DOCUMENT=>array('pic'=>'newdocument','action'=>'window.location.assign("?id=new&ds_id='.$_GET['ds_id'].'")'),
		BTN_SAVE=>array('pic'=>'save','action'=> $save ? 'sendData()' : ''),
		'separator',
		BTN_CUT=>array('pic'=>'cut','action'=>'excmd("Cut")'),
		BTN_COPY=>array('pic'=>'copy','action'=>'excmd("Copy")'),
		BTN_PASTE=>array('pic'=>'paste','action'=>'excmd("Paste")'),
		BTN_REMOVE_FORMAT=>array('pic'=>'unformat','action'=>'excmd("RemoveFormat")'),
		BTN_REMOVE_STYLES=>array('pic'=>'removecss','action'=>'removeCSS()'),
		'separator',
		BTN_INSERT_IMAGE=>array('pic'=>'image','action'=>array_search('IMG',$allowed)!==false?'insertImage()':''),
		BTN_INSERT_LINK=>array('pic'=>'link','action'=>'excmd("CreateLink")'),
		'separator',
		BTN_BOLD=>array('pic'=>'bold','action'=>'excmd("Bold")'),
		BTN_ITALIC=>array('pic'=>'italic','action'=>'excmd("Italic")'),
		'separator',
		BTN_ALIGN_LEFT=>array('pic'=>'left','action'=>'excmd("JustifyLeft")'),
		BTN_ALIGN_CENTER=>array('pic'=>'center','action'=>'excmd("JustifyCenter")'),
		BTN_ALIGN_RIGHT=>array('pic'=>'right','action'=>'excmd("JustifyRight")'),
		'separator',
		BTN_INDENT=>array('pic'=>'indent','action'=>'excmd("Indent")'),
		BTN_OUTDENT=>array('pic'=>'outdent','action'=>'excmd("Outdent")'),
		'separator',
		BTN_INSERT_UL=>array('pic'=>'ulist','action'=>'excmd("InsertUnorderedList")'),
		BTN_INSERT_OL=>array('pic'=>'olist','action'=>'excmd("InsertOrderedList")'),
		'separator',
		BTN_EDIT_STYLE=>array('pic'=>'style','action'=>'editStyle()'),
		BTN_EDIT_TAG_PROPS=>array('pic'=>'attribute','action'=>'editProps()'),
		BTN_EDIT_SOURCE=>array('pic'=>'editsrc','action'=>'editsource()')
		));
?><wbr><IMG SRC="<?=WCHOME?>/i/default/e/separator.gif" align="top"> <SELECT size=1 id="tagID" align="absmiddle"><?php
	foreach(explode(',','H1,H2,H3,H4,DIV,SPAN,NOBR') as $tag)
		if(array_search($tag,$allowed)!==false)
			echo '<OPTION value="'.$tag.'">&lt;'.$tag.'&gt;';
?></SELECT> <input align="top" type="button" value="<?=BTN_INSERT_TAG?>" onclick="if(tagID.options.length) intoTags(tagID.options(tagID.selectedIndex).value)" style="width:80px;margin-top:0px;">
</nobr>
</td></tr></table>
<?=$_GET['id']!='new'?'':'<script>disableButton(f.btn_save,"'.WCHOME.'/i/default/e/save_.gif");</script>'?>
</td></tr>
<tr height="33%" valign="top"><td>
<iframe name="content" style="border:none" width="100%" style="margin:0 0" height="100%" onfocus="theHTML=content"></iframe>
</td></tr>
<tr height="66%" valign="top"><td>
	<table width="100%" height="100%">
		<tr><td nowrap valign="top">
		<?=F_IMAGEMAIN?>:<br><input type="file" name="image" <?=!@$_ds->images['main']?'DISABLED':''?>>
		&nbsp;&nbsp;<a href="javascript:deleteimg('main')" class="tool1"><img src="<?=WCHOME?>/i/default/e/deleteimg.gif" align=absmiddle alt="<?=BTN_REMOVE_IMAGE?>" name="btn_delimgmain"></a>
		</td>
		<td nowrap valign="top" style="padding-left:5px">
		<?=F_IMAGEINTRO?>:<br><input type="checkbox" <?=!@$_ds->images['intro']?'DISABLED':''?> name="manual_img" value="true" checked onclick="f.image_intro.disabled=!this.checked" align="bottom" style="margin:-1px 1px 0px;"><input type="file" name="image_intro" <?=!$_ds->images['intro']?'DISABLED':''?>>
		&nbsp;&nbsp;<a href="javascript:deleteimg('intro')" class="tool1"><img src="<?=WCHOME?>/i/default/e/deleteimg.gif" align=absmiddle alt="<?=BTN_REMOVE_IMAGE?>" name="btn_delimgintro"></a>
		</td>
		</tr>
		<tr height="100%"><td width="60%" valign="top" align="center">
		<iframe name="img_main" src="showimage.php?img=<?=!@$_ds->images['main']?'none':FILES.$_ds->imagepath.($_doc['real_id']?$_doc['real_id']:$_doc['id']).'.'.$_doc['img_type']?>" style="border:none;margin:0 0" width="100%" height="100%"></iframe>
		</td>
		<td width="40%" valign="top" align="center">
		<iframe name="img_intro" src="showimage.php?img=<?=!@$_ds->images['intro']?'none':FILES.$_ds->imagepath.($_doc['real_id']?$_doc['real_id']:$_doc['id']).'_.'.$_doc['img_type']?>" style="border:none;margin:0 0" width="100%" height="100%"></iframe>
		</td></tr>
	</table>
</td></tr>
</table>
<script>if(<?=@$_doc['img_main']?'false':'true'?>) disableButton(f.btn_delimgmain,"<?=WCHOME?>/i/default/e/deleteimg_.gif");if(<?=@$_doc['img_intro']?'false':'true'?>) disableButton(f.btn_delimgintro,"<?=WCHOME?>/i/default/e/deleteimg_.gif");</script>
<script defer>
	theHTML=content;
	content.document.designMode="On";
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
</script>
</body>
</html>