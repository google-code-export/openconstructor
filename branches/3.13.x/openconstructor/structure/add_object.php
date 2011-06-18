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
 * $Id: add_object.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/structure._wc');
	require_once(LIBDIR.'/site/pagereader._wc');
	require_once(LIBDIR.'/objmanager._wc');
	
	$pr = PageReader::getInstance();
	$to = $pr->getPage(@$_GET['node']);
	assert($to != null);
	$super = $pr->superDecide($to->id, 'managesub');
	$_objm = new ObjManager();
	$objs = $_objm->get_all_objects();
	$ex = $to->getObjects();
	foreach($ex as $id => $j)
		unset($objs[$id]);
	$c_obj_type = @current($objs);
	header("Content-type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo '<?xml-stylesheet type="text/xsl" href="'.WCHOME.'/skins/'.SKIN.'/properties.xsl"?>';
?>

<interface>
	<messages>
		<msg id=""></msg>
	</messages>
	<session>
		<site host="<?=$_host?>" insight="/openconstructor"/>
		<user name="<?=$auth->userName?>" id="<?=$auth->userLogin?>"/>
	</session>
	<title><?=WC.' | '.ADD_OBJECT?></title>
	<header><?=ADD_OBJECT?></header>
	<style>
		H3.l1{color:black;font-size:130%;background:#ccc;padding:7 5;margin:10 0 7;clear:both;}
		H3.l2{color:#888;font-size:110%;background:white;padding:0;margin:10 0 10 -10;}
		HR{clear:both;margin:5px 0px 5px -10px;}
		DIV.item {padding:7 5px;text-align:center;display:inline;vertical-align:top;cursor:hand;width:80px;}
		DIV.selecteditem {padding:7 5px;text-align:center;display:inline;vertical-align:top;background:#efefef;width:80px;}
		DIV.selecteditem SPAN {}
	</style>
	<script>
		var
			wchome=<?='"'.WCHOME.'"'?>,
			skin=<?='"'.SKIN.'"'?>,
			imghome=wchome+'/i/'+skin,
			selected=null
			;
		function choose(div){
			if(selected)
				unselect(selected);
			select(div);
		}
		function select(div){
			selected=div;
			div.className='selecteditem';
			f.add.disabled = <?=$super || WCS::decide($to, 'pageblock.manage') ? 'false' : 'true'?>;
			f.obj_id.value=div.obj_id;
			f.obj_type.value=div.obj_type;
		}
		function unselect(div){
			div.className='item';
			f.add.disabled=true;
			selected=null;
		}
	</script>
	<script src="<?=WCHOME?>/common.js"></script>
	<form name="f" method="POST" action="i_structure.php" onsubmit="if(!selected){alert('<?=H_SELECT_OBJECT_I?>');return false;}">
		<input type="hidden" name="action" value="add_object"/>
		<input type="hidden" name="uri_id" value="<?=$_GET['node']?>"/>
		<input type="hidden" name="obj_type" value="<?=$c_obj_type['obj_type']?>"/>
		<input type="hidden" name="obj_id" value=""/>
		<objects>
	<?php
		$j=NULL;$b=0;$k=NULL;$c=0;
		foreach($objs as $id=>$v)
		{
			if($j!=$v['obj_type']){
				if($b++>0) echo '</type>';
				if($k!=$v['ds_type'])
					echo ($c++<1?'':'</datasource>').'<datasource type="'.$v['ds_type'].'">';
				echo '<type name="'.$v['obj_type_f'].'" id="'.$v['obj_type'].'">';
			}
			echo '<item id="'.$id.'" name="'.htmlspecialchars($v['name'], ENT_COMPAT, 'UTF-8').'">'.htmlspecialchars($v['description'], ENT_COMPAT, 'UTF-8').'</item>';
			$k=$v['ds_type'];
			$j=$v['obj_type'];
		}
		if($b>0)
			echo '</type>';
		if($c>0)
			echo '</datasource>';
	?>
		</objects>
		<br/>
		<div align="right"><input type="submit" value="<?=BTN_ADD_OBJECT?>" name="add" disabled=""/>&#160;<input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"/></div>
	</form>
</interface>