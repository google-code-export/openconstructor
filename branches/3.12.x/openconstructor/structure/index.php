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
 * $Id: index.php,v 1.15 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('sitemap');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/site/pagereader._wc');
	require_once(LIBDIR.'/tree/export/siteview._wc');
	require_once('../include/sections._wc');
	
	$siteroot = @$_COOKIE['siteroot'] ? $_COOKIE['siteroot'] : null;
	$pr = &PageReader::getInstance();
	$tree = $pr->getTree($siteroot);
	if($tree == null) {
		if($siteroot != 1) {
			$siteroot = 1;
			$tree = $pr->getTree($siteroot);
		}
		assert($tree != null);
	}
	$curnode = isset($_GET['node']) ? $_GET['node'] : @$_COOKIE['curnode'];
	$page = $pr->getPage($curnode);
	if($page == null) {
		$curnode = $tree->root->id;
		$page = $pr->getPage($curnode);
	}
	$router = $page->router ? $page->id : $pr->getPageRouter($page->id);
	$super = $pr->superDecide($page->id, 'managesub');
	setcookie('curnode', $curnode, 0, WCHOME.'/structure/');
	header("Content-type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	if(!isset($_GET['rawXml']))
		echo '<?xml-stylesheet type="text/xsl" href="'.WCHOME.'/skins/'.SKIN.'/main.xsl"?>';
?>
<interface uri="<?=htmlspecialchars($_SERVER['REQUEST_URI'])?>" node="<?=$page->id?>">
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
			'REFRESH'
		));
	?>
	<title><?=WC.' | '.SITEMAP.' | '.htmlspecialchars($page->header, ENT_COMPAT, 'UTF-8')?></title>
	<script>
	<![CDATA[
		var
			wchome=<?='"'.WCHOME.'"'?>,
			skin=<?='"'.SKIN.'"'?>,
			imghome=wchome+'/i/'+skin,
			curnode=<?=$page->id?>,
			isVersion=<?=$page->id == $tree->root->id ? 'true' : 'false'?>,
			ob=new Array()
			;
		<?php
			set_js_vars(array(
				'REMOVE_PAGE_Q',
				'SURE_REMOVE_PAGE_Q',
				'EXCLUDE_SELECTED_OBJECTS_Q',
				'YOU_CANNOT_REMOVE_SITEROOT_W'
				));
		?>
		var pri1 = new Array(), ob = new Array();
		pri1[0] = new Image; pri1[0].src = imghome + '/t/fg.gif';
		pri1[1] = new Image; pri1[1].src = imghome + '/t/fg_.gif';
	]]>
	</script>
	<script src="<?=WCHOME?>/common.js"></script>
	<script src="<?=WCHOME?>/structure/local.js"></script>
	<form name="f_remove" method="POST" action="<?=WCHOME.'/structure'?>/i_structure.php" style="margin:0">
		<input type="hidden" name="action" value="remove_page"/>
		<input type="hidden" name="page_id" value="<?=$page->id?>"/>
	</form>
	<session>
		<site host="<?=$_host?>" insight="/openconstructor"/>
		<user name="<?=$auth->userName?>" id="<?=$auth->userLogin?>"/>
	</session>
	<logo language="<?=LANGUAGE?>"/>
	<?php
		menu('structure');
		
		require_once(LIBDIR.'/templates/wctemplates._wc');
		$wct = new WCTemplates();
		$tpls = $wct->get_all_tpls('page');
		echo '<posttoolbar><![CDATA[';
		echo PR_PAGE_TPL.': <select id="tplId" align="absmiddle" '.($super || WCS::decide($page, 'editpage') ? '' : 'disabled').'><option value="0" style="background:#eee;color: gray;">'.H_NO_TPL_SELECTED;
		foreach($tpls as $id => $name)
			echo '<OPTION VALUE="'.$id.'"'.($id == $page->tpl?' SELECTED':'').'>'.htmlspecialchars($name, ENT_COMPAT, 'UTF-8');
		echo '</select>&nbsp;';
		if($page->tpl) {
			$tpl = $wct->load($page->tpl);
			echo '<a href="javascript:editTpl('.$page->tpl.');"><img src="'.WCHOME.'/i/'.SKIN.'/tool/edittpl.gif" style="margin-right:20px; border: none;" align="absmiddle"></a>';
		} else {
			$tpl = null;
			echo '<img src="'.WCHOME.'/i/'.SKIN.'/tool/edittpl_.gif" style="margin-right:20px; border: none;" align="absmiddle">';
		}
		echo ']]></posttoolbar>';
		
		include('toolbar._wc');
		toolbar($toolbar);
//		require_once('common._wc');
	//	$opened=array_flip(explode(',',get_path_id($_site->id_hash[$curnode])));
		//unset($opened['-1']);
		$view = new TreeSiteView();
		$view->setSelected($page->id);
		echo '<navigation>';
			$tree->export($view);
		echo '</navigation>';
		include('objtypes._wc');
		include('headline._wc');
?>
	<postscript>
	<![CDATA[
		<?=$page->id == $tree->root->id ? 'disableButton(btn_remove,imghome+"/tool/remove_.gif");' : ''?>	
	]]>
	</postscript>
	<copyrights><![CDATA[<div id="copyrights"><?=WC_COPYRIGHTS?></div>]]></copyrights>
</interface>
