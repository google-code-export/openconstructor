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
 * $Id: internal_ds.php,v 1.9 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	require_once('../include/sections._wc');
	
	$_dsm=new DSManager();
	if(!($ds = $_dsm->load(@$_GET['node'])))
		assert(true == false);
	assert(!$ds->isInternal == false);
	$curnode = $ds->ds_id;
	$nodetype = $ds->ds_type;
	header("Content-type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo '<?xml-stylesheet type="text/xsl" href="'.WCHOME.'/skins/'.SKIN.'/main.xsl"?>';
?>
<interface uri="<?=htmlspecialchars($_SERVER['REQUEST_URI'])?>" node="<?=$curnode?>">
	<?php
		set_xslt_vars(array(
			'EDITING_SITE',
			'CURRENT_USER',
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
	<title><?=WC.' | '.constant('DS_'.strtoupper($nodetype)).' | '.htmlspecialchars($ds->name, ENT_COMPAT, 'UTF-8')?></title>
	<script>
	<![CDATA[
		var
			wchome=<?='"'.WCHOME.'"'?>,
			skin=<?='"'.SKIN.'"'?>,
			imghome=wchome+'/i/'+skin,
			curnode=<?=intval($curnode)>0?$curnode:'false'?>,
			isInternal=true,
			nodetype=<?='"'.strtolower($nodetype).'"'?>
			;
		<?php
			set_js_vars(array(
				'REMOVE_DS_Q',
				'SURE_REMOVE_DS_Q',
				'REMOVE_SELECTED_DOCUMENTS_Q',
				'PUBLISH_SELECTED_DOCUMENTS_Q',
				'UNPUBLISH_SELECTED_DOCUMENTS_Q'
				));
		?>
	]]>	
	</script>
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
	<header><?=htmlspecialchars($ds->name, ENT_COMPAT, 'UTF-8')?></header>
<?php
		include('internal_toolbar._wc');
		toolbar($toolbar);
		if(intval($curnode)>0) {
			require_once($nodetype.'/headline._wc');
		} else echo '&#160;';
?>
	<settings display="<?=@$_COOKIE['panelstate']?$_COOKIE['panelstate']:'inline'?>">
		<searchbar title="<?=htmlspecialchars(RP_SEARCH_FOR_DOCUMENTS, ENT_COMPAT, 'UTF-8')?>" text="<?=htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8')?>"/>
	</settings>
	<postscript>
	<![CDATA[
		disableButton(btn_moverecord,imghome+'/tool/moverecord_.gif');
		disableButton(btn_publish,imghome+'/tool/publish_.gif');
		disableButton(btn_unpublish,imghome+'/tool/unpublish_.gif');
		disableButton(btn_remove,imghome+'/tool/remove_.gif');
		function rf_view()
		{
			<?php
			if(is_array(@$fieldnames)) foreach($fieldnames as $k=>$v) echo 'if(!frm_v.'.$k.'.checked) setCookie("vf['.$k.']","disabled","'.WCHOME.'/data/"); else setCookie("vf['.$k.']","enabled","'.WCHOME.'/data/");';
			?>	
			setCookie("pagesize",frm_v.pagesize.value,"<?=WCHOME?>/data/");
			window.location.reload();
		}
	]]>
	</postscript>
	<copyrights><![CDATA[<div id="copyrights"><?=WC_COPYRIGHTS?></div>]]></copyrights>
</interface>