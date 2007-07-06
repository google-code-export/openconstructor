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
 * $Id: index.php,v 1.14 2007/03/10 11:04:01 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	require_once('../include/sections._wc');
	
	$_dsm=new DSManager();
	$siteroot='dataRoot';
	$map[$siteroot]=array(DATASOURCES=>$_dsm->getTree());
	$tmp = @$_GET['node']?$_GET['node']:@$_COOKIE['curnode'];
	$curnode = $_dsm->exists($tmp) ? $tmp : $_dsm->first;
	if(!($ds = &$_dsm->load($curnode))) {
		$curnode = 0;
	} elseif($ds->isInternal) {
		sendRedirect('http://'.$_SERVER['HTTP_HOST'].WCHOME.'/data/internal_ds.php?node='.$ds->ds_id);
		die();
	}
	$nodetype = @$ds->ds_type;
	setcookie('curnode',$curnode,0,WCHOME.'/data/');
	if(!isset($_COOKIE['vf']['img_intro'])) {
		setcookie('vf[img_intro]','disabled',0,WCHOME.'/data/');
		$img_intro_just_disabled = true;
	}
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
			'RP_DONT_USE_INDEX',
			'VIEW',
			'INFO'
		));
	?>
	<title><?=WC.' | '.DATASOURCES.($ds ? htmlspecialchars((@$ds->isInternal ? '(internal)':'').' | '.@$ds->name, ENT_COMPAT, 'UTF-8') : '')?></title>
	<script>
	<![CDATA[
		var
			wchome=<?='"'.WCHOME.'"'?>,
			skin=<?='"'.SKIN.'"'?>,
			imghome=wchome+'/i/'+skin,
			curnode=<?=intval($curnode)>0?intval($curnode):'false'?>,
			isInternal=<?=@$ds->isInternal?'true':'false'?>,
			nodetype=<?='"'.strtolower($nodetype).'"'?>,
			isLocked=<?=@$ds->lock?'true':'false'?>
			;
		<?php
			define("SURE_REMOVE_LOCKED_DS_Q", sprintf(TPL_SURE_REMOVE_LOCKED_DS_Q, $ds->lock));
			set_js_vars(array(
				'REMOVE_DS_Q',
				'SURE_REMOVE_DS_Q',
				'SURE_REMOVE_LOCKED_DS_Q',
				'REMOVE_SELECTED_DOCUMENTS_Q',
				'PUBLISH_SELECTED_DOCUMENTS_Q',
				'UNPUBLISH_SELECTED_DOCUMENTS_Q'
				));
		?>
	]]>	
	</script>
	<script src="<?=WCHOME?>/lib/js/base.js"></script>
	<script src="<?=WCHOME?>/common.js"></script>
	<script src="<?=WCHOME?>/data/local.js"></script>
	<form name="f_remove" method="POST" action="<?=WCHOME.'/data/'.$nodetype?>/i_<?=$nodetype?>.php" style="margin:0">
		<input type="hidden" name="action" value="remove_ds"/>
		<input type="hidden" name="ds_id" value="<?=$curnode?>"/>
	</form>
	<session>
		<site host="<?=$_host?>" insight="/openconstructor"/>
		<user name="<?=$auth->userName?>" id="<?=$auth->userLogin?>"/>
	</session>
	<logo language="<?=LANGUAGE?>"/>
	<?php
		menu('data');
		include('toolbar._wc');
		toolbar(&$toolbar);
		$opened=@array_flip(explode(',', $ds->path));
		$opened[$siteroot]=true;
		$opened[$curnode]=true;
		$opened[$nodetype]=true;
		echo '<navigation>';
			print_tree($map, 'homedata');
		echo '</navigation>';
		if(intval($curnode)>0) {
			require_once($nodetype.'/headline._wc');
		} else echo '&#160;';
	?>
	<settings display="<?=@$_COOKIE['panelstate']?$_COOKIE['panelstate']:'inline'?>">
		<searchbar title="<?=htmlspecialchars(RP_SEARCH_FOR_DOCUMENTS)?>" text="<?=htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8')?>" showNoIndex="<?=$ds->isIndexable ? 'yes' : 'no'?>" useIndex="<?=@$_GET['noindex'] == 'on' ? 'no' : 'yes'?>"/>
	</settings>
	<postscript>
	<![CDATA[
		disableButton(btn_moverecord,imghome+'/tool/moverecord_.gif');
		disableButton(btn_publish,imghome+'/tool/publish_.gif');
		disableButton(btn_unpublish,imghome+'/tool/unpublish_.gif');
//		dump(location);
		function rf_view()
		{
			<?php
			if(is_array(@$fieldnames)) foreach($fieldnames as $k=>$v) echo 'if(!frm_v.'.$k.'.checked) setCookie("vf['.$k.']","disabled","'.WCHOME.'/data/"); else setCookie("vf['.$k.']","enabled","'.WCHOME.'/data/");';
			?>	
			setCookie("pagesize",frm_v.pagesize.value,"<?=WCHOME?>/data/");
			var page = Math.ceil((((<?=intval(@$_GET['page'])+!intval(@$_GET['page'])?> - 1) * <?=(int) @$pagesize?>) + 1) / frm_v.pagesize.value);
			window.location.href = window.location.pathname + setVar(window.location.search, 'page', page);
		}
	]]>
	</postscript>
	<copyrights><![CDATA[<div id="copyrights"><?=WC_COPYRIGHTS?></div>]]></copyrights>
</interface>
