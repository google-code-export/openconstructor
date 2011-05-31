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
 * $Id: edit.php,v 1.10 2007/03/02 10:06:44 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');

	if(!isset($_GET['ds_id'])||!isset($_GET['id'])) die();
	require_once($_SERVER['DOCUMENT_ROOT'].WCHOME.'/include/toolbar._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = $dsm->load($_GET['ds_id']);
	if($_GET['id']!='new') {
		$_doc = $_ds->get_record($_GET['id']);
		assert($_doc !== null);
		if($_doc['id']!= $_doc['real_id']) {
			$_ds = $dsm->load($_doc['realDsId']);
			$_doc = $_ds->get_record($_doc['real_id']);
		}
	} else {
		$_doc['date'] = time();
		$_doc['real_id'] = null;
	}
	$m = array();
	preg_match_all('~<([A-Z0-9]+)~', strtoupper($_ds->allowedTags), $m , PREG_PATTERN_ORDER);
	$allowed = (array) @$m[1];
	$pages=1;//the number of pages in article
	if($_GET['id']!='new') $pages=count($_doc['header'])-1;
	$sDoc = $_ds->wrapDocument($_doc);
?>
<html>
<head>
	<title><?=$_ds->name?> | <?=$_GET['id']=='new'?CREATE_ARTICLE:htmlspecialchars($_doc['header'][0], ENT_COMPAT, 'UTF-8').' | '.EDIT_ARTICLE.($_doc['id']!=$_doc['real_id']?DOCUMENT_IS_ALIAS:'')?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
	<script>
		var host='<?=$_host?>', gre=new RegExp('[^\\s]','gi');
		var _pages=<?=$pages?>;
		var
			host='<?=$_host?>',
			skin='<?=SKIN?>',
			REMOVE_SELECTED_PAGES_Q='<?=REMOVE_SELECTED_PAGES_Q?>';
			;
		var pageText='<?=H_PAGE?> ', headText='<?=H_ENTIRE_ARTICLE?>';

		function sendData()
		{
			var ci = curIndex;
			if(content.source) {theHTML=content;editsource();}
			for(var i = 0; i < pages + 1; i++) {
				switchTo(i);
				repairHRefs(content);
			}
			switchTo(ci);

			for(var i = 0; i < pages + 1; i++)
			{
				head[i].setAttribute("name","header["+i+"]");
				cont[i].setAttribute("name","html["+i+"]");
			}
			content.document.body.disabled=true;
			content.document.designMode="Off";
			disableButton(btn_saveart,'<?=WCHOME?>/i/e/saveart_.gif');
			f.submit();
		}
		function deleteimg(type)
		{
			if(!mopen("../../confirm.php?q=<?=urlencode(CONFIRM_REMOVE_IMAGE_Q)?>?"+"&skin="+skin,350,150)) return;
			var d=new Date();
			if(!mopen("../deleteimg.php?ds=<?=$_ds->ds_id?>&id=<?=$_doc['real_id']?>&type="+type+'&j='+Math.ceil(d.getTime()/1000),350,150)) return;
			disableButton(document.all('btn_delimg'+type),"<?=WCHOME?>/i/default/e/deleteimg_.gif");
			document.all('ref_showimg'+type).outerHTML=document.all('ref_showimg'+type).innerHTML;
		}
		function setRelated()
		{
			var vars='related='+f.related.value+'&id=<?=$_doc['real_id']?>&';
			var d=new Date();
			f.related.value=mopen('getrelated/index.php?'+vars+'j='+Math.ceil(d.getTime()/1000),700,500);
		}
	</script>
	<script src="../editor.js"></script>
	<script src="editor.js"></script>
</head>
<body style="border:groove; border-width: 2;" ondrag="return false" onload="chkPnl()">
<table cellpadding=0 cellspacing=0 width="100%" height="100%" border=0>
<tr>
	<td id='mtools' colspan=3 valign="top" height='40px'>
	<div nowrap id='toolbar' width=100%><img src="<?=WCHOME?>/i/default/e/beginner.gif" style="margin:1px -4px 0px 0px;">
	<?php
		$save = @$_GET['hybridid'] > 0 || ($_GET['id'] == 'new' ? WCS::decide($_ds, 'createdoc') : WCS::decide($_ds, 'editdoc') || WCS::decide($sDoc, 'editdoc'));
		toolbar(array(
			BTN_NEW_DOCUMENT=>array('pic'=>'newarticle','action'=>'window.location.assign("?id=new&ds_id='.$_GET['ds_id'].'")'),
			BTN_SAVE=>array('pic'=>'saveart','action'=> $save ? 'sendData()' : ''),
			'separator_',
			BTN_ADD_PAGE=>array('pic'=>'addpage','action'=>'createPage(parseInt(curIndex)+1)'),
			BTN_SET_RELATED_ARTICLES=>array('pic'=>'related','action'=>'setRelated()'),
			'separator_',
			BTN_MOVE_PAGES_UP=>array('pic'=>'moveup','action'=>'moveItems(-1)'),
			BTN_MOVE_PAGES_DOWN=>array('pic'=>'movedown','action'=>'moveItems(1)'),
			'separator_',
			BTN_REMOVE_PAGES=>array('pic'=>'rmvpage','action'=>'removePages()')
		));
	?>
	</div>
	</td>
</tr>
<tr>
	<td height=100% width="30%" valign="top" style="display:<?=@$_COOKIE['panelstate']?>;padding:5 0 0 10;word-wrap:break-word;" id="view">
		&nbsp;&nbsp;<?=H_ARTICLE_PAGES?><br><div style="float:left;padding-top:5px;"><img src="<?=WCHOME?>/i/default/e/pages.gif"/></div>
		<ol id="olMenu">
		</ol>
	</td>
	<td width="8px" align="absmiddle" style="background:#ebebeb;">
		<div>
			<a href="javascript:switchpanel()" id="pnl" onclick="this.setAttribute('className',this.className=='pnlleft'?'pnlright':'pnlleft')"><img src="<?=WCHOME?>/i/1x1.gif" border="0" alt="<?=BTN_SHOW_HIDE_PAGES?>" width="8" height="50"></a>
		</div>
	</td>
	<td height=100% valign="top" style="padding-left:10px;">
	<table cellpadding=0 cellspacing=0 width="100%" height=100% border=0>
	<tr>
		<td colspan=2 valign="top" style="padding:5 10 0 0;">
		<div id="acl"><?=F_ARTICLE_HEADER?>:</div>
		<div id="pge" style="display:none;"><?=F_PAGE_HEADER?>:</div>
		<textarea name="hview" style="width:100%;" rows=2 cols=85 onpropertychange="disableButton(btn_saveart,!this.value.match(gre),this.inner)"><?=htmlspecialchars(@$_doc['header'][0], ENT_COMPAT, 'UTF-8')?></textarea>
		</td>
	</tr>
	<tr style="padding:0 10 0 0;">
		<td valign="top"><nobr>
		<form name="f" style="margin:0; padding:0;" method="POST" enctype="multipart/form-data" action="i_article.php">
			<?php
				for($i=0;$i<=$pages;$i++)
				{
			?>
			<textarea name="theader<?=$i?>" style="display:none;"><?=$_GET['id']=='new'?'':htmlspecialchars($_doc['header'][$i], ENT_COMPAT, 'UTF-8')?></textarea>
			<textarea name="thtml<?=$i?>" style="display:none;"><?=$_GET['id']=='new'?'':htmlspecialchars($_doc['content'][$i], ENT_COMPAT, 'UTF-8')?></textarea>
			<?php
				}
			?>
			<textarea name="intro" style="display:none;"><?=$_GET['id']=='new'?'':htmlspecialchars($_doc['intro'], ENT_COMPAT, 'UTF-8')?></textarea>
			<input type="hidden" name="action" value="<?=$_GET['id']=='new'?'create':'edit'?>_article">
			<input type="hidden" name="id" value="<?=$_doc['real_id']?>">
			<input type="hidden" name="ds_id" value="<?=$_ds->ds_id?>">
<input type="hidden" name="hybridid" value="<?=@$_GET['hybridid']?>">
<input type="hidden" name="fieldid" value="<?=@$_GET['fieldid']?>">
<input type="hidden" name="callback" value="<?=@$_GET['callback']?>">
			<input type="hidden" name="autointro" value="false">
			<input type="hidden" name="related" value="<?=@$_doc['related']?$_doc['related']:''?>">
			<table id="datetable" cellpadding=0 border=0 style="margin-top:3px;" cellspacing=0 width="100%">
				<tr>
					<td nowrap style="padding-right:15px;" valign="top" width="100%">
						<div style="width:100%;position:relative;">
						<div style="float:left;"><?=F_DATE_TIME?>:<br>
						<?php
							list($day, $month, $year) = explode('/', date('d/m/Y', $_doc['date']));
							echo '<input type="text" name="day" size=3 maxlength="2" value="'.$day.'">';
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
						?>&nbsp;
						<input type="text" name="time" size="8" maxlength="8" value="<?=date('H:i:s', $_doc['date'])?>">&nbsp;&nbsp;
						<input type=checkbox <?=@$_doc['published']?' checked':''?> <?=!WCS::decide($_ds, 'publishdoc')||$_GET['id']=='new'?'disabled':''?> name="published" value="true"> <?=F_IS_PUBLISHED?>&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
						<div style="float:left;">
<?php
	if($_GET['id']!='new'&&@$_ds->images['intro']) {
		if(@file_exists($_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.($_doc['real_id']?$_doc['real_id']:$_doc['id']).'.'.$_doc['img_type']))
		{
			list($w,$h)=explode(',',preg_replace('/(.*width=")(\d*)(".*height=")(\d*)(".*)/mi','\\2,\\4',$_doc['img_main']));
			echo '<a href="#" name="ref_showimgmain" onclick="window.open(\''.FILES.$_ds->imagepath.($_doc['real_id']?$_doc['real_id']:$_doc['id']).'.'.$_doc['img_type'].'\',\'\',\'resizable=yes, scrollbars=no, status=no, height='.($h+30).', width='.($w+20).'\');return false" title="'.TT_SHOW_CURRENT_IMAGE.'">'.F_IMAGEMAIN.'</a>';
		} else	echo F_IMAGEMAIN;
?>:<br>
	<input type="file" name="image">&nbsp;&nbsp;<a href="javascript:deleteimg('main')" class="tool1"><img src="<?=WCHOME?>/i/default/e/deleteimg.gif" align=absmiddle alt="<?=BTN_REMOVE_IMAGE?>" name="btn_delimgmain"></a>
	<script>
		if(!document.all('ref_showimgmain')&&f.image) disableButton(f.btn_delimgmain,"<?=WCHOME?>/i/e/deleteimg_.gif");
	</script>

<?php }?>
	<input type="button" accesskey="s" onclick="if(!document.all('btn_saveart').dbtn) sendData()" value="save" style="width:0px;height:0px;">
						</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan=4>
						<hr size="2">
					</td>
				</tr>
			</table>
		</form>
		</nobr>
		</td>
		</tr>
		<tr height=24>
			<td align="left" width=100% style="padding-right:10px;">
			<nobr>
			<div id='editbar' style='display:none;'>
			<?php
				toolbar(array(
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
			?>
				<IMG SRC="<?=WCHOME?>/i/default/e/separator.gif" align="top"> <SELECT size=1 id="tagID" align="absmiddle">
			<?php
				foreach(explode(',','H1,H2,H3,H4,DIV,SPAN,NOBR') as $tag)
					if(array_search($tag,$allowed)!==false)
						echo '<OPTION value="'.$tag.'">&lt;'.$tag.'&gt;';
			?></SELECT>
				<input align="top" type="button" value="<?=BTN_INSERT_TAG?>" onclick="if(tagID.options.length) intoTags(tagID.options(tagID.selectedIndex).value)" style="width:80px;margin-top:0px;">
			</div>
			</nobr>
			<?=$_GET['id']!='new'?'':'<script>//11disableButton(btn_saveart,"'.WCHOME.'/i/e/saveart_.gif");</script>'?>
			</td>
		</tr>
		<tr height="99%" valign="top" style="padding:0 10 15 0;"><td>
		<iframe name="content" style="border:none;" width="100%" style="margin:5 0;" height="100%" onfocus="theHTML=content"></iframe>
		</td></tr>
	</table>
	</td>
</tr>
</table>
<script defer>
	theHTML=content;
	content.document.designMode="On";
	window.inter=window.setInterval(setcontent,500);
	function setcontent(a)
	{
		if(content.document.body)
		{
			window.clearInterval(window.inter);
			content.document.body.innerHTML=f.intro.value;
			content.document.body.onkeypress=new Function('',"if(window.content.event.keyCode==28) {window.parent.editsource()}");
			content.focus();
			head=content.document.childNodes(0).childNodes(0);
			link=content.document.createElement('<link href="http://<?=$_host?>/css/content.css" type=text/css rel=stylesheet>');
			head.appendChild(link);
			content.document.body.style.background='white';
			content.document.body.style.margin='5px';

			load(_pages);
		}
	}
//	load(_pages);
</script>
</body>
</html>