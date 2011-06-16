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
 * $Id: index.php,v 1.10 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('tpls');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/templates/wctemplates._wc');
	require_once('../include/sections._wc');
	
	$siteroot='templatesRoot';
	$curnode=@$_GET['node']?$_GET['node']:(@$_COOKIE['curnode']?$_COOKIE['curnode']:'htmltextbody');
	setcookie('curnode',$curnode,0,WCHOME.'/templates/');
	$tpls=new WCTemplates();
	$tmap=$tpls->get_map();
	foreach($tmap as $k=>$v)
		foreach($v as $v1)
			if(@$v1[$curnode]) $nodetype=$k;
	$map[$siteroot][TEMPLATES]=&$tmap;
//	foreach($map[$siteroot][TEMPLATES] as $k=>$v)
//		if(!$__ur->can('templates.ds'.$k.'.view'))
//			unset($map[$siteroot][TEMPLATES][$k]);
	$opened[$nodetype]=true;
	$opened[$siteroot]=true;
	header("Content-type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	if(!isset($_GET['rawXml']))
		echo '<?xml-stylesheet type="text/xsl" href="'.WCHOME.'/skins/'.SKIN.'/main.xsl"?>';
?>

<interface uri="<?=htmlspecialchars($_SERVER['REQUEST_URI'])?>" node="<?=$curnode?>">
	<?php
		set_xslt_vars(array(
			'WC',
			'WC_HOMEPAGE_URI',
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
	<title><?=WC.' | '.TEMPLATES?></title>
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
				'REMOVE_SELECTED_TEMPLATES_Q'
				));
		?>
	]]>
	</script>
	<script src="<?=WCHOME?>/common.js"></script>
	<script src="<?=WCHOME?>/templates/local.js"></script>
	<session>
		<site host="<?=$_host?>" insight="/openconstructor"/>
		<user name="<?=$auth->userName?>" id="<?=$auth->userLogin?>"/>
	</session>
	<logo language="<?=LANGUAGE?>"/>
	<?php
		menu('templates');
		include('toolbar._wc');
		toolbar($toolbar);
		echo '<navigation>';
			print_tree($map, 'hometpl');
		echo '</navigation>';
		include('headline._wc');
	?>
	<settings display="<?=@$_COOKIE['panelstate']?$_COOKIE['panelstate']:'inline'?>">
		<searchbar title="<?=htmlspecialchars(RP_SEARCH_FOR_TEMPLATES, ENT_COMPAT, 'UTF-8')?>" text="<?=htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8')?>"/>
	</settings>
	<postscript>
	<![CDATA[
		disableButton(btn_remove,imghome+'/tool/remove_.gif');
		disableButton(btn_editsec, imghome + '/tool/editsec_.gif');
		function rf_view()
		{
			<?php
				if(is_array(@$fieldnames))
					foreach($fieldnames as $k=>$v)
						echo 'if(!frm_v.'.$k.'.checked) setCookie("vf['.$k.']","disabled","'.WCHOME.'/templates/"); else setCookie("vf['.$k.']","enabled","'.WCHOME.'/templates/");';
			?>	
			setCookie("pagesize",frm_v.pagesize.value,"<?=WCHOME?>/templates/");
			window.location.reload();
		}
	]]>
	</postscript>
	<copyrights><![CDATA[<div id="copyrights"><?=WC_COPYRIGHTS?></div>]]></copyrights>
</interface>