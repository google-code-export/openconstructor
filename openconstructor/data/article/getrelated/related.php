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
 * $Id: related.php,v 1.7 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	$related=-1;
	if(@$_GET['related']) $related=$_GET['related'];
	$db = &WCDB::bo();
	$res = $db->query(
		'SELECT id, ds_id, header, intro'.
		' FROM dsarticle'.
		' WHERE id IN ('.$related.') AND published!=0'.
		' ORDER BY date DESC'
	);
	$article=array();
	if(mysql_num_rows($res)>0)
	{ 
		while($row=mysql_fetch_assoc($res))
			$article[$row['id']]=array(
				'ds_id'=>$row['ds_id'],
				'header'=>$row['header'],
				'intro'=>$row['intro']
			);
	}
	mysql_free_result($res);
?>
<html>
<head>
<title>Related Articles</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
function o(href){window.parent.editor.wxyopen(href, 788, 520);}
function remove(id){
	window.parent.remove(id);
}
</script>
<style>
H3 {
	font-weight:normal;
}
LI {
list-style-type: none;
margin-bottom:3px;
padding:0px 5px 0px 5px;
}

LI A {
vertical-align: middle;
height:20px;
}
IMG{
margin: 0 5 0 0;
border:none;
}
</style>
</head>
<body style="border-style:inset;border-width:2px;padding:10 10;background:white">
<?php echo '<h3 id="empty" style="display:'.($related<0||!sizeof($article)?'inline':'none').'">'.H_NO_RESULTS_FOR_KEYWORD.'</h3>';?>
<ul style="margin:0px 0px 0px 0px;padding:0px;" id="list"><?php
	foreach($article as $id=>$v){
		echo '<li><a href="#"><img src="'.WCHOME.'/i/default/e/minus.gif" onclick="remove('.$id.')" id="ico'.$id.'" alt="'.TT_REMOVE_ARTICLE.'">';
		echo '</a><img src="'.WCHOME.'/i/default/e/articlepage.gif">'.
			'<a href="../edit.php?ds_id='.$v['ds_id'].'&id='.$id.'" onclick="o(this.href); return false" title="'.$v['intro'].'" id="art'.$id.'" added="true">'.$v['header'].'</a>'.
			'</li>';
	}
?></ul>
</body>
</html>
