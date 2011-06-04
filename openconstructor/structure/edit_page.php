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
 * $Id: edit_page.php,v 1.22 2007/04/23 09:50:05 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/structure._wc');
	require_once(LIBDIR.'/site/pagereader._wc');

	$pr = PageReader::getInstance();
	$page = $pr->getPage(@$_GET['node']);
	assert($page != null);
	$super = $pr->superDecide($page->id, 'managesub');
	require_once(LIBDIR.'/security/groupfactory._wc');
	$gf = GroupFactory::getInstance();
	require_once(LIBDIR.'/templates/wctemplates._wc');
	$tpls = new WCTemplates();
	$tpl = null;
	if($page->tpl)
		$tpl = $tpls->load($page->tpl);
?>
<html>
<head>
<title><?=WC.' | '.H_EDIT_PAGE.' '.$page->uri?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
var re=new RegExp('[^\\s]','gi');
function dsb(){
	if(!f.uri_name.value.match(re)||!f.header.value.match(re)||<?=$super || WCS::decide($page, 'managesub') ?'false' : 'true'?>)
		f.save.disabled=true; else f.save.disabled=false;
}
function wxyopen(uri,x,y) {
	window.open(uri,'',"resizable=yes, scrollbars=yes, status=yes" + (y > 0 ? ", height=" + y : "") + (x > 0 ? ", width=" + x : ""));
}
function suggestCacheVary() {
	var res = openModal('cachevary.php?id=<?=$page->id?>', 460, 350);
	if(res.length) {
		f.cacheVary.value = '';
		for(var i = 0; i < res.length; i++)
			f.cacheVary.value += res[i] + "\n";
	}
}
</script>
<script language="Javascript" src="../lib/js/base.js"></script>
</head>
<body style="border-style:groove;padding:0 20 20">
<br>
<h3><?=H_EDIT_PAGE.' '.$page->uri?></h3>
<form name="f" method="POST" action="i_structure.php">
	<input type="hidden" name="uri_id" value="<?=$page->id?>">
	<input type="hidden" name="action" value="edit_page">
	<fieldset style="padding:10" <?=$super || WCS::decide($page, 'editpage.uri') ? '' : 'disabled'?>><legend>URI</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td>URI:</td>
			<td><b><?=$page->uri?></b></td>
		</tr>
<?php if($page->uri != '/') { ?>
		<tr>
			<td><?=PAGE_FOLDER?>:</td>
			<td><input type="text" name="uri_name" value="<?=$page->name?>" size="64" maxlength="128" onpropertychange="dsb()"></td>
		</tr>
<?php } else
	echo '<tr><td colspan=2><input type="hidden" name="uri_name" value="'.$page->name.'"></td></tr>';
?>
		<tr>
			<td><?=$page->linkTo ? "<a href='?node={$page->linkTo}'>".PAGE_REDIRECT_URI.'</a>' : PAGE_REDIRECT_URI?>:</td>
			<td><input type="checkbox"<?=$page->location?' CHECKED':''?> onclick="f.location.disabled=!this.checked"><input type="text" name="location" size="60" value="<?=$page->location?>"<?=$page->location?'':' DISABLED'?>></td>
		</tr>
		<tr>
			<td colspan="2"><input <?=$page->uri == '/' ? 'disabled' : ''?> type="checkbox" name="router" id="f.router" value="true" <?=$page->router?' CHECKED':''?>> <label for="f.router"><?=PAGE_ROUTE?></label></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=PAGE_GENERAL?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=PAGE_NAME?>:</td>
			<td><input type="text" name="header" value="<?=htmlspecialchars($page->header, ENT_COMPAT, 'UTF-8')?>" size="64" maxlength="128" onpropertychange="dsb()"></td>
		</tr>
		<tr>
			<td><?=PAGE_STATUS?>:</td>
			<td><select size="1" name="published" <?=($super || WCS::decide($page, 'editpage.publish')) && (!$page->parent || $pr->isPublished($page->parent)) ? '' : 'disabled'?> onchange="if(this.selectedIndex==0){f.recursive.ov=f.recursive.checked;f.recursive.checked=true;f.recursive.disabled=true}else{f.recursive.checked=f.recursive.ov;f.recursive.disabled=false}"><option value="false"><?=PAGE_IS_UNPUBLISHED?><option value="true"<?=$page->published ? ' SELECTED':''?>><?=PAGE_IS_PUBLISHED?></select>
			<input type="checkbox" name="recursive" id="f.recursive" value="true"<?=!$page->published?' CHECKED DISABLED':''?> <?=$super || WCS::decide($page, 'editpage.publish') ? '' : 'disabled'?>> <label for="f.recursive"><?=PAGE_STATUS_AFFECTS_CHILDREN?></label>
			</td>
		</tr>
		<tr>
			<td><a href="javascript: if(f.template.options[f.template.selectedIndex].value > 0) wxyopen('../templates/editpage.php?dstype=site&id=' + f.template.options[f.template.selectedIndex].value, 660);"><?=PAGE_TEMPLATE?></a>:</td>
			<td><select size="1" name="template">
				<option value="0" style="background:#eee;color: gray;"><?=H_NO_TPL_SELECTED?>
<?php
	foreach($tpls->get_all_tpls('page') as $tpl_id=>$name)
		echo '<OPTION VALUE="'.$tpl_id.'"'.($tpl_id == $page->tpl ? ' SELECTED' : '').'>'.$name;
?>
		</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
<?php
	if($tpl != null && $tpl->mockup > '') {
		echo '<div class="mockGroup">'.$tpl->mockup.'</div>';
	} elseif($tpl == null && $page->tpl > 0)
		echo '<span style="color:red;">'.TPL_NOT_FOUND_W.'</span>';
?>
			</td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=PAGE_WEB?></legend>
	<table style="margin:5 0" cellspacing="3" width="100%">
		<tr>
			<td><?=PAGE_TITLE?>:</td>
			<td><input type="text" name="title" value="<?=htmlspecialchars($page->title, ENT_COMPAT, 'UTF-8')?>" maxlength="255" style="width: 100%;font-family: monospace; font-size: 100%;"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="appendparent" id="f.appendparent" value="true"<?=$page->addTitle ? ' CHECKED' : ''?> <?=$page->uri == '/' ? ' DISABLED' : ''?>> <label for="f.appendparent"><?=PAGE_ADD_PARENTS_TITLE?></label><p></td>
		</tr>
		<tr>
			<td valign="top">
				<?=PAGE_CSS?>:
				<div style="font-size:90%;"><?=sprintf(TT_PAGE_CSS, FILES.'/css');?></div>
			</td>
			<td><select size="5" name="css[]" multiple><?php
				$cssDir = $_SERVER['DOCUMENT_ROOT'].FILES.'/css';
				if(@is_dir($cssDir)) {
					$d = dir($cssDir);
					while (false !== ($entry = $d->read()))
					    if(substr($entry, 0, 1) != '_' && substr($entry, strrpos($entry, '.')) == '.css' && is_file($cssDir.'/'.$entry))
							echo '<option value="'.$entry.'"'.(array_search($entry, $page->css) !== false ? ' SELECTED' : '').'>'.$entry;
					$d->close();
				}
				?></select></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend><?=PAGE_META_INFO?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><?=PAGE_CONTENT_TYPE?>:</td>
			<td><select size="1" name="contentType" style="font-family: monospace;">
				<?php
				if($page->uri != '/')
					echo '<option value="" style="background:#eee;color:#888;">'.PAGE_INHERIT_CONTENT_TYPE;
				foreach(
					array(
						'text/html', 'text/plain', 'text/css', 'text/xml', 'text/calendar'
						, 'application/octet-stream', 'application/pdf', 'application/x-gzip', 'application/x-javascript', 'application/xml', 'application/zip'
						, 'image/gif', 'image/jpeg', 'image/png'
					) as $v)
					echo '<option value="'.$v.'"'.($v == $page->contentType ? ' SELECTED' : '').'>'.$v;
				?></select>
			</td>
		</tr>
		<tr>
			<td valign="top"><?=PAGE_META_KEYWORDS?>:</td>
			<td><textarea cols="51" rows="5" name="m_keywords"><?=htmlspecialchars($page->meta['keywords'], ENT_COMPAT, 'UTF-8')?></textarea></td>
		</tr>
		<tr>
			<td valign="top"><?=PAGE_META_DESCRIPTION?>:</td>
			<td><textarea cols="51" rows="5" name="m_description"><?=htmlspecialchars($page->meta['description'], ENT_COMPAT, 'UTF-8')?></textarea></td>
		</tr>
		<tr>
			<td><?=PAGE_ROBOTS?>:</td>
			<td><select size="1" name="robots">
				<option value="<?=ROBOTS_I_F?>" <?=$page->robots == ROBOTS_I_F ? 'selected' : ''?>>INDEX, FOLLOW
				<option value="<?=ROBOTS_I_NOF?>" <?=$page->robots == ROBOTS_I_NOF ? 'selected' : ''?>>INDEX, NOFOLLOW
				<option value="<?=ROBOTS_NOI_F?>" <?=$page->robots == ROBOTS_NOI_F ? 'selected' : ''?>>NOINDEX, FOLLOW
				<option value="<?=ROBOTS_NOI_NOF?>" <?=$page->robots == ROBOTS_NOI_NOF ? 'selected' : ''?>>NOINDEX, NOFOLLOW
			</selects></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10" <?=$super || WCS::decide($page, 'editpage.security') ? '' : 'disabled'?> ><legend><?=PAGE_CACHING?></legend>
		<input type="checkbox" id="ch.caching" name="caching" value="true" style="margin-top: 10px;" onclick="document.getElementById('fs.caching').disabled = !this.checked" <?=$page->caching ? 'checked' : ''?>>
			<label for="ch.caching"><?=PAGE_ENABLE_CACHING?></label>
		<fieldset id="fs.caching" style="padding:10" <?=$super || WCS::decide($page, 'editpage.security') ? '' : 'disabled'?> ><legend><?=PAGE_CACHE_PROPS?></legend>
		<table style="margin:5 0" cellspacing="3">
			<tr>
				<td><?=PAGE_CACHE_LIFETIME?>:</td>
				<td><input type="text" name="cacheLife" value="<?=intval($page->cacheLife)?>"></td>
			</tr>
			<tr>
				<td><?=PAGE_CACHE_VARY?>:</td>
				<td rowspan="2"><textarea name="cacheVary" cols="20" rows="5"><?=htmlspecialchars($page->cacheVary)?></textarea></td>
			</tr>
			<tr>
				<td height="100%"><input type="button" value="<?=PAGE_CACHE_VARY_SUGGEST?>" style="margin: 10px;" onclick="suggestCacheVary();"></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="checkbox" name="cacheGz" id="ch.cacheGz" value="true" <?=$page->cacheGz ? 'checked' : ''?>>
					<label for="ch.cacheGz"><?=PAGE_TRY_TO_GZIP_CACHE?></label>
				</td>
			</tr>
		</table>
		</fieldset>
		<script>f.caching.onclick();</script>
	</fieldset><br>
	<fieldset style="padding:10" <?=$super || WCS::decide($page, 'editpage.security') ? '' : 'disabled'?> ><legend><?=PAGE_USERS?></legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td><select size="10" name="users[]" multiple>
<?php
	$groups = $gf->getAllGroups();
	foreach($groups as $id => $title)
		echo '<option value="'.$id.'"'.(array_search($id, $page->users) !== false ? ' SELECTED' : '').'>'.$title;
?></select></td>
			<td valign="top">
				<input type="checkbox" name="usrEnforceChildren" id="f.usrEnforceChildren" value="true"> <label for="f.usrEnforceChildren"><?=H_MAKE_CHILDS_USR_THE_SAME?></label>
				<br><input type="button" value="<?=PAGE_SELECT_ALL_USERS?>" style="width:120;margin:3 5" onclick="for(i=0;i<f.all('users[]').options.length;i++) f.all('users[]').options(i).selected=true;">
				<br><input type="button" value="<?=PAGE_UNSELECT_ALL_USERS?>" style="width:120;margin:3 5" onclick="for(i=0;i<f.all('users[]').options.length;i++) f.all('users[]').options(i).selected=false;">
			</td>
		</tr>
	</table>
		<fieldset style="padding:10">
		<legend><?=H_PAGE_USER_PROFILES?></legend>
		<?php if($page->uri != '/'): ?>
		<div style="padding: 5px;">
			<input type="checkbox" name="profiles_inherit" id="f.profiles_inherit" value="true" onclick="document.getElementById('fs.profiles').disabled = this.checked" <?=$page->profilesInherit ? 'checked' : ''?>> <label for="f.profiles_inherit"><?=PAGE_PROFILES_INHERIT?></label>
		</div>
		<?php endif;?>
		<fieldset id="fs.profiles" style="border: none;">
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td nowrap><?=PAGE_PROFILES_LOADER?>:</td>
					<td style="width: 100%;">
						<select name="profiles_load">
							<option style="background: #eee;">-
						<?php
							require_once(LIBDIR.'/objmanager._wc');
							$objs = (array) ObjManager::get_objects('hybrid', 'hybridbody', 1, '', -1);
							foreach($objs as $id => $obj)
								echo sprintf('<option value="%d" %s>%s', $id, $id == $page->profilesLoad ? 'selected' : '', addslashes($obj['name']));
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="checkbox" name="profiles_fetch_once" id="f.profiles_fetch_dynamic" value="true" <?=$page->profilesDynamic ? '' : 'checked'?>> <label for="f.profiles_fetch_dynamic"><?=PAGE_PROFILES_LOAD_ONCE?></label>
					</td>
				</tr>
			</table>
		</fieldset>
		</fieldset>
		<script>
			try {
				document.getElementById('f.profiles_inherit').onclick();
			} catch(e) {}
		</script>
	</fieldset><br>
	<div align="right"><input type="submit" value="<?=BTN_SAVE?>" name="save"<?=$super || WCS::decide($page, 'managesub') ? '' : ' DISABLED'?>> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
</body>
</html>