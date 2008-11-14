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
 * $Id: index.php,v 1.9 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('objects');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/objmanager._wc');
	require_once('../include/sections._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$siteroot='objRoot';
	$curnode=@$_GET['node']?$_GET['node']:(@$_COOKIE['curnode']?$_COOKIE['curnode']:'htmltextbody');

	setcookie('curnode',$curnode,0,WCHOME.'/objects/');
	$objm=new ObjManager();
	foreach($objm->map as $k=>$v)
		foreach($v as $v1)
			if(@$v1[$curnode]) $nodetype=$k;
	$map[$siteroot][OBJECTS]=&$objm->map;

	$opened[$nodetype]=true;
	$opened[$siteroot]=true;

	$smartybackend->assign("curnode", $curnode);
	$smartybackend->assign("nodetype", $nodetype);
	$smartybackend->assign("cur_section", 'objects');
	$smartybackend->assign_by_ref("auth", $auth);

	$smartybackend->assign("menu", getTabs('objects'));

	include('toolbar._wc');
	$smartybackend->assign_by_ref("toolbar", $toolbar);

	$smartybackend->assign("map", print_tree($map));

	include('headline._wc');
	$smartybackend->assign("fields", $fields);
	$smartybackend->assign("objs", $hl);

    $smartybackend->assign("editor_width", 660);
	$smartybackend->assign("editor_height", 'null');
	$smartybackend->assign("editor", $nodetype . '/' . $curnode . '.php?j=1');
	$smartybackend->assign("icon", 'object');

	$smartybackend->assign("search_text", htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8'));
    $smartybackend->assign("fieldnames", $fieldnames);
    $smartybackend->assign("pagesize", $pagesize);

    /*$fields1=$fields;
	foreach((array) $fieldnames as $k=>$v)
		if(!@$fields1[$k]||@$fields1[$k]===true)
			$fields1[$k]=$v;
		foreach($fields1 as $k=>$v)
			if($v!==true)
				echo 'name=' . $v . ', id=' . $k . ', title=' . @$fieldnames[$k] . ', enabled=' . (isset($fields[$k])?'yes':'no') . ', visible=' . (isset($fields[$k])&&@$fields[$k]!==true?'yes':'no');
                */


    /*echo "<pre>";
    print_r($hl);
    echo "</pre>";*/

	$smartybackend->display('objects/main.tpl');
	die();
	header("Content-type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	if(!isset($_GET['rawXml']))
		echo '<?xml-stylesheet type="text/xsl" href="'.WCHOME.'/skins/'.SKIN.'/main.xsl"?>';
?>

<interface uri="<?=htmlspecialchars($_SERVER['REQUEST_URI'])?>" node="<?=$curnode?>">
	<?php
		set_xslt_vars(array(
			'WC',
			'EDITING_SITE',
			'CURRENT_USER',
			'WC_SETUP',
			'ABOUT_WC',
			'SWITCH_USER',
			'LOGOUT',
			'SELECT_ALL',
			'REFRESH',
			'GOTO_FIRST_PAGE',
			'GOTO_PREVIOUS_PAGE',
			'GOTO_NEXT_PAGE',
			'GOTO_LAST_PAGE',
			'TOTAL',
			'SHOW_HIDE_PANEL',
			'HIDE_PANEL',
			'START_SEARCH',
			'VIEW',
			'INFO'
		));
	?>
	<title><?=WC.' | '.OBJECTS?></title>
	<script>
	<![CDATA[
		var
			wchome=<?='"'.WCHOME.'"'?>,
			skin=<?='"'.SKIN.'"'?>,
			imghome=wchome+'/i/'+skin,
			curnode=<?='"'.$curnode.'"'?>,
			nodetype=<?='"'.$nodetype.'"'?>
			;
		<?php
			set_js_vars(array(
				'REMOVE_SELECTED_OBJECTS_Q'
				));
		?>
	]]>
	</script>
	<script src="<?=WCHOME?>/common.js"></script>
	<script src="<?=WCHOME?>/objects/local.js"></script>
	<session>
		<site host="<?=$_host?>" insight="/openconstructor"/>
		<user name="<?=$auth->userName?>" id="<?=$auth->userLogin?>"/>
	</session>
	<logo language="<?=LANGUAGE?>"/>
	<?php
		menu('objects');
		include('toolbar._wc');
		toolbar(&$toolbar);
		echo '<navigation>';
			print_tree($map, 'homeobj');
		echo '</navigation>';
		include('headline._wc');
	?>
	<settings display="<?=@$_COOKIE['panelstate']?$_COOKIE['panelstate']:'inline'?>">
		<searchbar title="<?=htmlspecialchars(RP_SEARCH_FOR_OBJECTS, ENT_COMPAT, 'UTF-8')?>" text="<?=htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8')?>"/>
	</settings>
	<postscript>
	<![CDATA[
		disableButton(btn_remove,imghome+'/tool/remove_.gif');
		disableButton(btn_editsec,imghome+'/tool/editsec_.gif');
		function rf_view()
		{
			<?php
				if(is_array(@$fieldnames))
					foreach($fieldnames as $k=>$v)
						echo 'if(!frm_v.'.$k.'.checked) setCookie("vf['.$k.']","disabled","'.WCHOME.'/objects/"); else setCookie("vf['.$k.']","enabled","'.WCHOME.'/objects/");';
			?>
			setCookie("pagesize",frm_v.pagesize.value,"<?=WCHOME?>/objects/");
			window.location.reload();
		}
	]]>
	</postscript>
	<copyrights><![CDATA[<div id="copyrights"><?=WC_COPYRIGHTS?></div>]]></copyrights>
</interface>