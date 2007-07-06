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
 * $Id: index.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	$id=$_GET['id'];
	$related='';
	if(@$_GET['related']) $related=$_GET['related'];
?>
<html>
<head>
<title><?=H_RELATED_ARTICLES?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	editor=dialogArguments[0].parent;
	var related="<?=$related?>";
	var restore=related;
	var count=related!=''?related.split(',').length:0;
	function init(){
		f.insert.disabled=count==0?true:false;
		window.returnValue=related;
	}
	function sbmt(){
		window.close();
	}
	function search(){
		farticles.location.href="articles.php?id=<?=$id?>&related="+related+"&search="+document.all('keyword').value+'&j=<?=time()?>';
	}
	function remove(id){
		frelated.document.all('art'+id).parentElement.removeNode(true);
		if(farticles.document.all('art'+id)!=null){
			farticles.document.all('art'+id).added='false';
			farticles.document.all('ico'+id).src="<?=WCHOME?>/i/default/e/plus.gif";
			farticles.document.all('ico'+id).alt='<?=TT_ADD_ARTICLE?>';
		}
		count--;
		empty();
		update(-1, id);
	}
	function add(id){
		farticles.document.all("art"+id).added='true';
		farticles.document.all('ico'+id).src="<?=WCHOME?>/i/default/e/minus.gif";
		farticles.document.all('ico'+id).alt='<?=TT_REMOVE_ARTICLE?>';
		li=frelated.document.createElement('<li>');
		li.className='rel';
		li.innerHTML=farticles.document.all('art'+id).parentElement.innerHTML;
		frelated.document.all('list').appendChild(li);
		frelated.document.all('ico'+id).onclick=new Function('','remove('+id+')');
		count++;
		empty();
		update(1, id);
	}
	function empty(){
		if(count==0){
			frelated.document.all('empty').style.display='inline';
			related='';
		}else{
			frelated.document.all('empty').style.display='none';
		}
		f.insert.disabled=false;
	}
	function update(step, id){
		if(step<0){
			if(count==0||related==''){
				window.returnValue=related;
				return false;
			}
			ids=related.split(',');
			related='';
			for(var i=0; i<ids.length; i++){
				if(ids[i]!=id&&related=='') related=ids[i];
				else if(ids[i]==id) continue;
				else related+=','+ids[i];
			}
		}else{
			related=related==''?id:(related+','+id);
		}
		window.returnValue=related;
	}
</script>
</head>
<body style="border-style:groove;border-width:2px;padding:5 10;" onload="f.keyword.focus()">
<br>
<form name="f" onsubmit="search();return false" style="margin:0;">
<div style="padding:0 0 5 5;"><span style="vertical-align:middle; height:20px;"><?=SEARCH_FOR_KEYWORD?>:&nbsp;</span>
<input type="text" name="keyword" value=""><input type="submit" name="go" value="<?=FIND_NOW?>"></div>
<table border="0" width="100%" height="85%"><tr>
<td width="50%"><iframe src="empty.php?j=<?=time()?>" width="100%" height="100%" name="farticles"></iframe></td>
<td width="50%"><iframe src="related.php?related=<?=$related?>&id=<?=$id?>" width="100%" height="100%" name="frelated"></iframe></td>
</tr>
</table>
<div align="right"><input type="button" disabled name="insert" value="<?=BTN_SET_ARTICLES?>" onclick="sbmt()"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.returnValue=restore;window.close()"></div>
</form>
<script>
	init();
</script>
</body>
</html>
