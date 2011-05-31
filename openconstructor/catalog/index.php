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
 * $Id: index.php,v 1.13 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('catalog');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/tree/sqltreereader._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	require_once('../include/sections._wc');

	$dsm = new DSManager();
	$dsh = $dsm->getAll('hybrid');
	$reader = new SqlTreeReader();
	$currentDs = @$dsh[@$_COOKIE['dsh']] ? @$_COOKIE['dsh'] : key($dsh);
	@list(,$curtab) = explode('/',$_SERVER['PATH_INFO']);
	if(!$curtab) $curtab = $currentDs ? 'browse' : 'trees';
	$ds = $dsm->load($currentDs);
	$tmp = @$_GET['node'] ? $_GET['node'] : @$_COOKIE['curnode'];
	$treeFields = getTreesFor($currentDs);
	if($curtab == 'browse') {
		$rootNode = null;
		$tree = $reader->getPartialTree($treeFields);
		require_once(LIBDIR.'/tree/export/xmlviewmultiple._wc');
		$view = new TreeXmlViewMultiple();
		if((int) $tmp == -1) { // Special node to select unassigned documents
			$selected = array();
			$curnode = -1;
		} else {
			$selected = explode(',', $tmp);
			for($i = 0; $i < sizeof($selected); $i++)
				if(!$tree->exists($selected[$i]))
					unset($selected[$i]);
			$selected = sizeof($selected) ? array_values($selected) : array(-1);
			$curnode = implode(',', $selected);
		}
		$view->setSelected($selected);
		foreach($treeFields as $fieldName=>$nodeId) {
			$presetValue[$fieldName] = array();
			for($i = 0; $i < sizeof($selected); $i++) {
				if($tree->contains($nodeId, $selected[$i]))
					$presetValue[$fieldName][] = $selected[$i];
			}
			$presetValue[$fieldName] = $fieldName.'='.implode(',', $presetValue[$fieldName]);
		}
		$presetValue = implode('&', $presetValue);
		setcookie('dsh', $currentDs, 0, WCHOME);
	} else {
		$tree = $reader->getTree(1);
		if(!$tmp || !$tree->exists($tmp))
			@list($tmp) = each($tree->root->node);
		$curnode = $tmp && $tree->exists($tmp) ? $tmp : $tree->root->id;
		$rootNode = $tree->node[$curnode];
		while($rootNode->parent && $rootNode->parent->id != 1)
			$rootNode = &$rootNode->parent;
		if($rootNode->id != 1)
			$reader->loadAuths($rootNode);
		require_once(LIBDIR.'/tree/export/xmlview._wc');
		$view = new TreeXmlView();
		$view->setSelected($curnode);
		foreach($treeFields as $fieldName=>$nodeId)
			if($tree->contains($nodeId, $curnode)) {
				$presetValue = $fieldName.'='.$curnode;
				break;
			}
	}
	$tree->root->header = H_TREES;
	setcookie('curnode', $curnode, 0, WCHOME.'/catalog/');
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
	<title><?=WC.' | '.CATALOG.($currentDs > 0 ? ' | '.htmlspecialchars($dsh[$currentDs]['name'], ENT_COMPAT, 'UTF-8') : '')?></title>
	<script>
	<![CDATA[
		var
			wchome=<?='"'.WCHOME.'"'?>,
			skin=<?='"'.SKIN.'"'?>,
			imghome=wchome+'/i/'+skin,
			curTab = '<?=$curtab?>',
			curDS = '<?=$currentDs?>',
			curnode = '<?=$curnode?>',
			preset = '<?=@$presetValue?>',
			rootNode = <?=@$curnode > 1 && $curnode == @$rootNode->id ? $curnode : 0?>
			;
		<?php
			set_js_vars(array(
				'REMOVE_NODE_Q',
				'SURE_REMOVE_NODE_Q',
				'REMOVE_SELECTED_DOCUMENTS_Q',
				'PUBLISH_SELECTED_DOCUMENTS_Q',
				'UNPUBLISH_SELECTED_DOCUMENTS_Q'
				));
		?>
	]]>
	</script>
	<script src="<?=WCHOME?>/lib/js/base.js"></script>
	<script src="<?=WCHOME?>/common.js"></script>
	<script src="<?=WCHOME?>/catalog/local.js"></script>
	<form name="f_remove" method="POST" action="<?=WCHOME?>/catalog/i_catalog.php" style="margin:0">
		<input type="hidden" name="action" value="remove_node"/>
		<input type="hidden" name="node_id" value="<?=$curnode?>"/>
	</form>
	<session>
		<site host="<?=$_host?>" insight="/openconstructor"/>
		<user name="<?=$auth->userName?>" id="<?=$auth->userLogin?>"/>
	</session>
	<logo language="<?=LANGUAGE?>"/>
	<?php
		menu('catalog');
		include('toolbar._wc');

		echo '<pretoolbar><![CDATA[<select size="1" onchange="setCookie(\'dsh\', this.options[this.selectedIndex].value, wchome + \'/\'); location.href = location.href;" style="margin-right:10px;">';
		foreach($dsh as $v)
			echo "<option value='{$v['id']}'".($v['id'] == $currentDs ? ' selected>' : '>').str_repeat('&#160;', (substr_count($v['path'],',') - 1) * 3)."{$v['name']}</option>";
		echo '</select>]]></pretoolbar>';
		toolbar(&$toolbar);
	?>
		<navigation>
			<tabs>
				<item href="<?=WCHOME?>/catalog/index.php/browse/" current="<?=$curtab=='browse'?'yes':'no'?>"><?=H_BROWSE_TAB?></item>
				<item href="<?=WCHOME?>/catalog/index.php/trees/" current="<?=$curtab=='trees'?'yes':'no'?>"><?=H_TREES_TAB?></item>
			</tabs>
	<?php
		if($curtab == 'browse') {
			foreach($treeFields as $fieldName => $nodeId)
				if($tree->exists($nodeId)) {
					$sub = $tree->getSubTree($nodeId);
					$sub->export($view);
				}
			echo '<postscript>tree.root = 0;';
			foreach($selected as $id)
				if(@$tree->node[$id])
					echo "setNodeState({$tree->node[$id]->index},1);";

			echo '</postscript><apply/>';
		} else
			$tree->export($view);
		echo '</navigation>';
		if($curnode) {
			require_once('headline._wc');
		} else echo '&#160;';
	?>
	<settings display="<?=@$_COOKIE['panelstate']?$_COOKIE['panelstate']:'inline'?>">
		<searchbar title="<?=htmlspecialchars(RP_SEARCH_FOR_DOCUMENTS, ENT_COMPAT, 'UTF-8')?>" text="<?=htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8')?>" showNoIndex="<?=@$ds->isIndexable ? 'yes' : 'no'?>" useIndex="<?=@$_GET['noindex'] == 'on' ? 'no' : 'yes'?>"/>
	</settings>
	<postscript>
	<![CDATA[
//		disableButton(btn_addrecord, imghome+'/tool/addrecord_.gif');
		disableButton(btn_publish,imghome+'/tool/publish_.gif');
		disableButton(btn_unpublish,imghome+'/tool/unpublish_.gif');
		if(!rootNode)
			disableButton(btn_editsec,imghome+'/tool/editsec_.gif');
		if(<?=$curtab == 'browse' ? 1 : 0?>)
			disableButton(btn_remove,imghome+'/tool/remove_.gif');
		function rf_view()
		{
			<?php
			if(is_array(@$fieldnames)) foreach($fieldnames as $k=>$v) echo 'if(!frm_v.'.$k.'.checked) setCookie("vf['.$k.']","disabled","'.WCHOME.'/catalog/"); else setCookie("vf['.$k.']","enabled","'.WCHOME.'/catalog/");';
			?>
			setCookie("pagesize",frm_v.pagesize.value,"<?=WCHOME?>/data/");
			window.location.reload();
		}
	]]>
	</postscript>
	<copyrights><![CDATA[<div id="copyrights"><?=WC_COPYRIGHTS?></div>]]></copyrights>
</interface>
