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
 * $Id: editprops.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	if(!isset($_GET['tag']))
		die('<script>window.returnValue=false;window.close()</script>');
	$props=array(
		'A'=>array(
			PR_TITLE=>'title',
			PR_HREF=>'href',
			PR_TARGET=>'target',
			PR_INNERHTML=>'innerHTML'
		),
		'DIV'=>array(
			PR_TITLE=>'title',
			'Align'=>'align'
		),
		'P'=>array(
			PR_TITLE=>'title',
			'Align'=>'align'
		),
		'H1'=>array(
			PR_TITLE=>'title',
			'Align'=>'align'
		),
		'IMG'=>array(
			PR_TITLE=>'title',
			'Alt'=>'alt',
			'Align'=>'align',
			'Border'=>'border',
			'Width'=>'width',
			'Height'=>'height',
			'HSpace'=>'HSpace',
			'VSpace'=>'VSpace'
		),
		'LI'=>array(
			PR_TITLE=>'title',
			'Type'=>'type',
			'Value'=>'value'
		),
		'OL'=>array(
			PR_TITLE=>'title',
			'Type'=>'type'
		),
		'UL'=>array(
			PR_TITLE=>'title',
			'Type'=>'type'
		),
		'PRE'=>array(
			PR_TITLE=>'title',
			'Wrap'=>'wrap'
		),
		'TABLE'=>array(
			PR_TITLE=>'title',
			'Align'=>'align',
			'CellPadding'=>'cellpadding',
			'CellSpacing'=>'cellspacing',
			'Border'=>'border',
			'Width'=>'width',
			'Height'=>'height',
			'HSpace'=>'HSpace',
			'VSpace'=>'VSpace'
		),
		'TD'=>array(
			PR_TITLE=>'title',
			'Align'=>'align',
			'VAlign'=>'valign',
			'ColSpan'=>'colspan',
			'RowSpan'=>'rowspan',
			'Width'=>'width',
			'Height'=>'height'
		),
		'TR'=>array(
			PR_TITLE=>'title',
			'Align'=>'align',
			'Width'=>'width',
			'Height'=>'height'
		),
	);
	for($i=2;$i<=6;$i++) $props['H'+$i]=&$props['H1'];
	$defProps=array(PR_TITLE=>'title');
	if(isset($props[$_GET['tag']]))
		$prop=&$props[$_GET['tag']];
	else
		$prop=&$defProps;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=H_EDIT_TAG_PROPERTIES?></title>
<link href="style.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	window.returnValue=false;
	tag=dialogArguments[0].parent.curTag;
	var thref=false,tx='null',ty='null';
	function sbmt()
	{
<?php
foreach($prop as $k=>$v)
	echo 'if(tag.'.$v.'||f.all("inp_'.$v.'").value) tag.'.$v.'=f.all("inp_'.$v.'").value;';
?>
		window.close();
	}
	function jshref(v){
		if(v){
			f.inp_href.value=thref;
			return;
		}
		if(!thref)
			thref=f.inp_href.value;
		tx=f.xv.value>0?f.xv.value:'null';
		ty=f.yv.value>0?f.yv.value:'null';
		ref='javascript:wxyopen(\''+thref+'\','+tx+','+ty+')';
		f.inp_href.value=ref;
	}
</script>
</head>
<body style="border-style:groove;border-width:2px;padding:5 10;">
<br>
<h3><?=H_EDIT_TAG_PROPERTIES?></h3>
<form name="f" onsubmit="sbmt();return false">
<fieldset style="padding:0 10">
<legend><?=H_TAG_PROPERTIES?></legend>
<table border="0" style="margin:5 0" width="100%">
<?php
foreach($prop as $k=>$v)
	echo '<tr><td NOWRAP>'.$k.':</td><td width="100%"><input type="text" name="inp_'.$v.'" style="width:100%"></td></tr>';
if($_GET['tag']=='A'){?>
	<tr><td NOWRAP colspan=2><input type="checkbox" name="jsch" onclick="f.yv.disabled=f.xv.disabled=!this.checked;jshref(!this.checked)"> <?=H_OPEN_IN_NEW_WINDOW_JS?></td></tr>
	<tr><td NOWRAP colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;X:<input size="4" maxlength="4" type="text" onpropertychange="jshref()" name="xv" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Y:<input type="text" size="4" maxlength="4" disabled onpropertychange="jshref()" name="yv"></td></tr>
	<script>if(tag.href.substr(0,11)=='javascript:') {
		f.jsch.checked=true;
		f.xv.disabled=f.yv.disabled=false;
		if(tag.href.substr(0,19)!='javascript:wxyopen(') f.jsch.disabled=true; else {
			t=tag.href.substr(20);thref=t.substr(0,t.indexOf("'"));
			t=t.substr(thref.length+1);
			r=new RegExp(",(\\w+),(\\w+)\\)");
			a=t.match(r);
			tx=a[1];
			ty=a[2];
			if(tx>0) f.xv.value=tx;
			if(ty>0) f.yv.value=ty;
	}}</script>
<?php }
?></table>
</fieldset>
<br><div align="right"><input type="submit" value="<?=BTN_OK?>" style="width:100"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()" style="width:100"></div>
</form>
<script><?php
foreach($prop as $k=>$v)
	echo 'f.all("inp_'.$v.'").value=tag.'.$v.'?tag.'.$v.':"";';
?>
//s='Properties:\n';for(k in tag)s+=k+'='+tag[k]+'\t';alert(s);</script>
</body>
</html>