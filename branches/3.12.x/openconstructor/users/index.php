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
 * $Id: index.php,v 1.11 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('users');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/security/groupfactory._wc');
	require_once('../include/sections._wc');
	
	$gf = &GroupFactory::getInstance();
	$groups = $gf->getAllGroups();
	$siteroot=0;
	$map['groupRoot'][USERS] = $groups;
	$tmp = isset($_GET['node']) ? $_GET['node'] : @$_COOKIE['curnode'];
	$curnode = isset($groups[$tmp]) ? $tmp : (sizeof($groups) ? key($groups) : -1);
	$group = $gf->getGroup($curnode);
	setcookie('curnode', $curnode, 0, WCHOME.'/users/');
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
	<title><?=WC.' | '.USERS?></title>
	<script>
	<![CDATA[
		var
			wchome=<?='"'.WCHOME.'"'?>,
			skin=<?='"'.SKIN.'"'?>,
			imghome=wchome+'/i/'+skin,
			curnode=<?=intval($curnode)>0?$curnode:'false'?>,
			login=<?='"'.$auth->userLogin.'"'?>,
			unable=0,
			builtIn = <?=(int) $group->builtIn?>
			;
		<?php
			set_js_vars(array(
				'ENABLE_SELECTED_USERS_Q',
				'DISABLE_SELECTED_USERS_Q',
				'REMOVE_USERGROUP_Q',
				'SURE_REMOVE_USERGROUP_Q',
				'REMOVE_SELECTED_USERS_Q',
				'REMOVE_SELECTED_MEMBERS_Q'
				));
		?>
	]]>
	</script>
	<script src="<?=WCHOME?>/common.js"></script>
	<script src="<?=WCHOME?>/users/local.js"></script>
	<form name="f_remove" method="POST" action="i_users.php" style="margin:0">
		<input type="hidden" name="action" value="remove_group"/>
		<input type="hidden" name="group_id" value="<?=$curnode?>"/>
	</form>
	<session>
		<site host="<?=$_host?>" insight="/openconstructor"/>
		<user name="<?=$auth->userName?>" id="<?=$auth->userLogin?>"/>
	</session>
	<logo language="<?=LANGUAGE?>"/>
	<?php
		menu('users');
		include('toolbar._wc');
		toolbar(&$toolbar);
		$opened[$curnode] = true;
		echo '<navigation>';
			print_tree($map, 'homeusr');
		echo '</navigation>';
		include('headline._wc');
		echo '<settings display="'.(@$_COOKIE['panelstate']?$_COOKIE['panelstate']:'inline').'">';
		echo '<searchbar title="'.htmlspecialchars(RP_SEARCH_FOR_USERS, ENT_COMPAT, 'UTF-8').'" text="'.htmlspecialchars(@$_GET['search'], ENT_COMPAT, 'UTF-8').'"/>';
		echo '</settings>';
	?>
	<postscript>
	<![CDATA[
		disableButton(btn_removemember,imghome+'/tool/removemember_.gif');
		disableButton(btn_enableuser,imghome+'/tool/enableuser_.gif');
		disableButton(btn_disableuser,imghome+'/tool/disableuser_.gif');
		if(builtIn) {
			disableButton(btn_remove,imghome+'/tool/remove_.gif');
		}
		function rf_view()
		{
			<?php
				if(is_array(@$fieldnames))
					foreach($fieldnames as $k=>$v)
						echo 'if(!frm_v.'.$k.'.checked) setCookie("vf['.$k.']","disabled","'.WCHOME.'/users/"); else setCookie("vf['.$k.']","enabled","'.WCHOME.'/users/");';
			?>	
			setCookie("pagesize",frm_v.pagesize.value,"<?=WCHOME?>/users/");
			window.location.reload();
		}
	]]>
	</postscript>
	<copyrights><![CDATA[<div id="copyrights"><?=WC_COPYRIGHTS?></div>]]></copyrights>
</interface>