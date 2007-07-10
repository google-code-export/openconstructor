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
 * $Id: index.php,v 1.9 2007/03/05 19:25:34 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	require_once('../../../include/sections._wc');
	require_once(LIBDIR.'/enum/wcenumfactory._wc');
	
	$ef = &WCEnumFactory::getInstance();
	$current = (int) @$_GET['enum'];
	$enum = $ef->load($current);
	if(!$enum) {
		$enum = $ef->loadFirstEnum();
		$current = $enum ? $enum->id : 0;
	}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=H_MANAGE_ENUMS?></title>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
	<style>
		UL.enums {
			list-style-type:none;
			padding:0;
			margin:0;
		}
		.enums LI {
			padding:0px;
			margin:5px 0 5px 20px;
		}
		.enums IMG {
			width:17px;
			height:20px;
			margin:0 5px 0 -17px;
			vertical-align: middle;
		}
	</style>
	<script src="../../../common.js"></script>
	<script>
	var current = <?=intval($current)?>, wchome = '<?=WCHOME?>', skin = '<?=SKIN?>', imghome = wchome + '/i/' + skin;
	var canRemoveEnum = <?=WCS::decide($enum, 'removeenum') ? 1 : 0?>, canRemoveValue = <?=WCS::decide($enum, 'removevalue') ? 1 : 0?>;
	<?php
		set_js_vars(array(
			'REMOVE_ENUM_Q',
			'SURE_REMOVE_ENUM_Q',
			'REMOVE_SELECTED_ENUMVALUES_Q'
			));
	?>
	function remove() {
		if(ch_doc==0) {
			if(!current) return;
			if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_ENUM_Q) + "&skin=" + skin,350,150))
				if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent("<span style='color:red'><b>" + SURE_REMOVE_ENUM_Q + "</b></span>") + "&skin=" + skin,350,170)) {
					f_doc.action.value = "remove_enum";
					f_doc.submit();
				}
		} else {
			if(mopen(wchome + "/confirm.php?q=" + encodeURIComponent(REMOVE_SELECTED_ENUMVALUES_Q) + "&skin="+skin,350,150)) {
				f_doc.action.value = "remove_enumvalue";
				f_doc.submit();
			}
		}
	}
	function chk(obj){
		chk_(obj)
		if(ch_doc < 1){
			disableButton(btn_remove, canRemoveEnum ? false : imghome+'/tool/remove_.gif');
			disableButton(btn_editsec, false);
		} else {
			disableButton(btn_remove, canRemoveValue ? false : imghome+'/tool/remove_.gif');
			disableButton(btn_editsec, imghome + "/tool/editsec_.gif");
		}
	}
	function edit_security() {
		if(!current) return;
		wxyopen(wchome+'/security/editenum.php?id=' + current, 500, 520);
	}
	</script>
</head>
<body style="border-style:groove;border-width:1px;padding:0px;background:white;">
	<div class="head"><?=H_MANAGE_ENUMS?></div>
	<?php
		include('toolbar._wc');
		html_toolbar(&$toolbar);
	?>
	<form name="f_doc" method="POST" action="i_enum.php" style="margin:0px; padding:0px;">
		<input type="hidden" name="enum" value="<?=$current?>">
		<input type="hidden" name="action" value="remove_enum">
		<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40%" valign="top" style="border-right:solid 1px #ccc;padding:20px 10px;">
		<ul class="enums">
		<?php
			$img = WCHOME.'/i/'.SKIN;
			$enums = $ef->getAllEnums();
			foreach($enums as $id => $header)
				if($id != $current)
					echo "<li><img src='$img/f/enum.gif'><a href='?enum=$id'>$header</a>";
				else
					echo "<li><img src='$img/f/enum.gif'><b>$header</b>";
		?>
		</ul>
		&nbsp;
		</td><td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr id="hlhead">
				<td width="100%" colspan="2"><input type="checkbox" onclick="doall(this.checked)" title="<?=SELECT_ALL?>" name="checkall" align="absmiddle"/><?=ENUMVALUE_HEADER?></td>
				<td><img src="<?=$img?>/f/border.gif" align="absmiddle"/><?=ENUMVALUE_KEY?></td>
				<td width="13"><img src="<?=WCHOME?>/i/1x1.gif" width="13" height="1"/></td>
			</tr>
		<?php
			$values = $enum ? $enum->getAllValues() : array(); $i = 0;
			foreach($values as $id => $v) { ?>
				<tr id="r_<?=$id?>" class="hlc<?= $i++ % 2?>">
					<td class="fc"><input type="checkbox" name="ids[]" value="<?=$id?>" onclick="chk(this)"/></td>
					<td width="100%" class="n">
						<a href="editvalue.php?enum=<?=$enum->id?>&id=<?=$id?>" onclick="wxyopen(this.href,590,230);return false;"><?=$v['header']?></a>
					</td>
					<td nowrap=""><?=$v['value']?></td>
					<td>&#160;</td>
				</tr>
		<?php
			}
		?>
		</ul>
		</td></tr></table>
	</form>
	<script>
		if(!(canRemoveEnum || canRemoveValue))
			disableButton(btn_remove, imghome + '/tool/remove_.gif');
	</script>
</body>
</html>