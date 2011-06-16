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
 * $Id: i_setup.php,v 1.28 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	WCS::_assert(Authentication::getUserId() == WCS_ROOT_ID);
	
	if(!@$_POST['posted']) {
		assert(true === false);
		die();
	}
		
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/classes._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/setup._wc');
	
	$db = WCDB::bo();
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?=WC.' '.WC_VERSION_FULL?> | <?=H_SYSTEM_SETUP?> (<?=$_SERVER['HTTP_HOST']?>)</title>
	<link href="setup.css" type="text/css" rel="stylesheet">
</head>
<body style="padding:3%">
	<h1><?=H_SETUP_RESULTS?></h1>
	<h3><?=R_SETUP_REMOVE_PAGES?></h3>
	<?php
		if(@$_POST['removepages']){
			require_once(LIBDIR.'/site/pagefactory._wc');
			$pf = PageFactory::getInstance();
			$pf->_dropAll();
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_REMOVE_DS?></h3>
	<?php
		if(@$_POST['removedatasources']){
			require_once(LIBDIR.'/wcdatasource._wc');
			$res=$db->query('SELECT ds_id FROM hybriddatasources WHERE parent=0');
			if(mysql_num_rows($res)>0) {
				require_once(LIBDIR.'/hybrid/hybriddocument._wc');
				require_once(LIBDIR.'/hybrid/dshybrid._wc');
				require_once(LIBDIR.'/hybrid/dshybridfactory._wc');
				$dsf = new DSHybridFactory();
				
				while($row = mysql_fetch_assoc($res))
					$dsf->remove($row['ds_id']);
			}
			mysql_free_result($res);
			
			$res = $db->query('SELECT ds_id FROM datasources ORDER BY internal, locks, ds_type');
			if(mysql_num_rows($res) > 0) {
				require_once(LIBDIR.'/dsmanager._wc');
				while($r = mysql_fetch_assoc($res)) {
					$ds = DSManager::load($r['ds_id']);
					if($ds != null)
						$ds->remove();
				}
			}
			mysql_free_result($res);
			
			$db->query('TRUNCATE TABLE datasources');
			$db->query('TRUNCATE TABLE dshybrid');
			$db->query('TRUNCATE TABLE dspublication');
			$db->query('TRUNCATE TABLE dsarticle');
			$db->query('TRUNCATE TABLE dsarticlepages');
			$db->query('TRUNCATE TABLE dsevent');
			$db->query('TRUNCATE TABLE dsgallery');
			$db->query('TRUNCATE TABLE dsfile');
			$db->query('TRUNCATE TABLE dshtmltext');
			$db->query('TRUNCATE TABLE dsphpsource');
			$db->query('TRUNCATE TABLE dstextpool');
			$db->query('TRUNCATE TABLE dsguestbook');
			$db->query('TRUNCATE TABLE dshfields');
			$db->query('TRUNCATE TABLE dsrating');
			$db->query('TRUNCATE TABLE dsratinglog');
			$db->query('TRUNCATE TABLE `index`');
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_REMOVE_ENUMS?></h3>
	<?php
		if(@$_POST['removedatasources'] && @$_POST['removeenums']){
			$db->query('TRUNCATE TABLE enums');
			$db->query('TRUNCATE TABLE enumvalues');
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_REMOVE_NODES?></h3>
	<?php
		if(@$_POST['removedatasources'] && @$_POST['removenodes']){
			require_once(LIBDIR.'/tree/sqltree._wc');
			$sqltree = new SqlTree();
			$sqltree->_dropAll();
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_REMOVE_OBJECTS?></h3>
	<?php
		if(@$_POST['removeobjects']){
			$db->query('TRUNCATE TABLE objects');
			$db->query('TRUNCATE TABLE siteobjects');
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_REMOVE_TPLS?></h3>
	<?php
		if(@$_POST['removetpls']){
			$res = $db->query('SELECT GROUP_CONCAT(id SEPARATOR ",") FROM wctemplates');
			list($ids) = mysql_fetch_row($res);
			if($ids) {
				require_once(LIBDIR.'/templates/wctemplates._wc');
				$wct = new WCTemplates();
				$wct->remove($ids);
			}
			mysql_free_result($res);
			$db->query('TRUNCATE TABLE wctemplates');
			$db->query('TRUNCATE TABLE wctemplate_blocks');
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_REMOVE_USERS_GROUPS?></h3>
	<?php
		if(@$_POST['removegroups']){
			$auth = Authentication::getInstance();
			$db->query('UPDATE wcsusers SET builtin = 0');
			$res = $db->query('SELECT GROUP_CONCAT(id SEPARATOR ",") FROM wcsusers');
			list($ids) = mysql_fetch_row($res);
			require_once(LIBDIR.'/security/userfactory._wc');
			if($ids) {
				$ids = explode(',', $ids);
				UserFactory::removeUser($ids);
			}
			mysql_free_result($res);
			$db->query('TRUNCATE TABLE wcsusers');
			$db->query('TRUNCATE TABLE wcsgroups');
			
			$db->query('INSERT INTO wcsgroups (id, name, auths, title, umask, builtin, wcsowner, wcsgroup) VALUES ('.WCS_ADMINS_ID.', "administrators", "", "Administrators", "'.WCS_DEFAULT_USER_MASK.'", 1, '.WCS_ROOT_ID.', '.WCS_ADMINS_ID.')');
			$db->query('INSERT INTO wcsgroups (id, name, auths, title, umask, builtin, wcsowner, wcsgroup) VALUES ('.WCS_EVERYONE_ID.', "everyone", "", "Everyone", "'.WCS_DEFAULT_USER_MASK.'", 1, '.WCS_ROOT_ID.', '.WCS_ADMINS_ID.')');
			$db->query('INSERT INTO wcsgroups (name, auths, title, umask, builtin, wcsowner, wcsgroup) VALUES ("system", "", "Open Constructor Users", "'.WCS_DEFAULT_USER_MASK.'", 1, '.WCS_ROOT_ID.', '.WCS_ADMINS_ID.')');
			
			$db->query(
				'INSERT INTO wcsusers (id, login, pwd, group_id, autologin, name, active, builtin, lastlogin)'.
				'VALUES ('.WCS_ROOT_ID.', "root", "", '.WCS_ADMINS_ID.', "'.UserFactory::getAutologinId('root', '').'", "'.addslashes($auth->userName).'", 1, 1, '.time().')'
			);
			$db->query('INSERT INTO wcsmembership (group_id, user_id) VALUES ('.WCS_ADMINS_ID.', '.WCS_ROOT_ID.')');
			$db->query('INSERT INTO wcsmembership (group_id, user_id) VALUES ('.WCS_EVERYONE_ID.', '.WCS_ROOT_ID.')');
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h2><?=H_SETUP_SMARTY_PREFS?></h2>
	<h3><?=R_SETUP_SMARTY_CLEAR_CACHE?></h3>
	<?php
		require_once(LIBDIR.'/smarty/wcsmarty._wc');
		$smarty=new WCSmarty();
		if(@$_POST['clearcache']){
			$smarty->clear_all_cache();
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_SMARTY_CLEAR_COMPILED?></h3>
	<?php
		if(@$_POST['clearcompiled']){
			$smarty->clear_compiled_tpl();
			echo '<h4 class="ok">'.R_SETUP_OK.'</h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h2><?=H_SETUP_MISC_PREFS?></h2>
	<h3><?=R_SETUP_RESAVE_DS?></h3>
	<?php
		if(!@$_POST['removedatasources'] && @$_POST['resavedss']){
			require_once(LIBDIR.'/dsmanager._wc');
			$res = $db->query('SELECT ds_id FROM datasources');
			$l = (int) mysql_num_rows($res);
			set_time_limit(($l + 1) * 2);
			_marktime();
			for($i = 0; $i < $l; $i++) {
				list($id) = mysql_fetch_row($res);
				$ds = DSManager::load($id);
				$ds->save();
			}
			$spentTime = _getperiod();
			mysql_free_result($res);
			echo '<h4 class="ok">'.R_SETUP_OK.'. <i>'.R_SETUP_TIME_SPENT.gmdate(' H:i:s', ceil($spentTime)).'</i></h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_RESAVE_PAGES?></h3>
	<?php
		if(!@$_POST['removepages'] && @$_POST['resavepages']){
			require_once(LIBDIR.'/site/pagefactory._wc');
			$pf = PageFactory::getInstance();
			$ids = array_keys($pf->reader->getAllPages());
			$ftp = $pf->getFtp();
			$ftp->open();
			set_time_limit(10 + (sizeof($ids) + 1) * 2);
			_marktime();
			foreach($ids as $id) {
				$page  = $pf->reader->getPage($id);
				$pf->updatePage($page);
			}
			set_time_limit(30);
			$spentTime = _getperiod();
			$ftp->close();
			echo '<h4 class="ok">'.R_SETUP_OK.'. <i>'.R_SETUP_TIME_SPENT.gmdate(' H:i:s', ceil($spentTime)).'</i></h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_UPDATE_INDEX?></h3>
	<?php
		if(@$_POST['reindex']){
			require_once(LIBDIR.'/dsmanager._wc');
			$db->query('TRUNCATE TABLE `index`');
			$res = $db->query('SELECT ds_id FROM datasources WHERE indexed');
			_marktime();
			for($i = 0, $l = mysql_num_rows($res); $i < $l; $i++) {
				list($id) = mysql_fetch_row($res);
				$ds = DSManager::load($id);
				if($ds != null && $ds->isIndexable) {
					$ds->setIndexable(false);
					$ds->setIndexable(true);
				}
			}
			$spentTime = _getperiod();
			mysql_free_result($res);
			echo '<h4 class="ok">'.R_SETUP_OK.'. <i>'.R_SETUP_TIME_SPENT.gmdate(' H:i:s', ceil($spentTime)).'</i></h4>';
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_EVICT_CAPTCHA?></h3>
	<?php
		if(@$_POST['clearCaptchaCache'] && WC_CAPTCHA_CACHE) {
			$files = (array) glob(WC_CAPTCHA_CACHE.'/*.png');
			$inSite = strpos($_SERVER['DOCUMENT_ROOT'], WC_CAPTCHA_CACHE) === 0;
			if($inSite) {
				$l = strlen($_SERVER['DOCUMENT_ROOT']);
				require_once(LIBDIR.'/wcftp._wc');
				$ftp = WCFTP::getNew();
				$ftp->open();
				foreach($files as $f)
					if($f)
						$ftp->unlink(substr($f, $l));
				$ftp->close();
				if(!$ftp->success())
					echo $ftp->get_message(true);
				else
					'<h4 class="ok">'.R_SETUP_OK.'</h4>';
			} else
				foreach($files as $f)
					if($f)
						unlink($f);
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<h3><?=R_SETUP_CHMOD?></h3>
	<?php
		$mode = (int) @$_POST['mode'];
		if(@$_POST['chmod'] && array_search($mode, array(755, 775, 777)) !== false) {
			$mode = "0$mode";
			$ex = array(
				FILES.'/map._wc',
				FILES.'/config._wc',
			);
			$exfile = array(
				'.htaccess'
			);
			$exdir = array(
				FILES.'/captcha/',
				FILES.'/smarty/compiled/',
				FILES.'/smarty/cache/'
			);
			require_once(LIBDIR.'/wcftp._wc');
			$ftp = WCFTP::getNew();
			$files = array_merge(
				glob($_SERVER['DOCUMENT_ROOT'].FILES.'/*'),
				glob($_SERVER['DOCUMENT_ROOT'].FILES.'/*/*'),
				glob($_SERVER['DOCUMENT_ROOT'].FILES.'/*/*/*')
			);
			$l = strlen($_SERVER['DOCUMENT_ROOT']);
			$ftp->open();
			foreach($files as $f) {
				$file = substr($f, $l);
				if(array_search($file, $ex) !== false || array_search(basename($file), $exfile))
					continue;
				$skip = false;
				foreach($exdir as $dir)
					if(strpos($file, $dir) === 0) {
						$skip = true;
						break;
					}
				if(!$skip)
					$ftp->chmod($file, $mode);
			}
			$ftp->close();
			echo $ftp->success() ? '<h4 class="ok">'.R_SETUP_OK.'</h4>' : $ftp->get_message(true);
		} else
			echo '<h4 class="cancel">'.R_SETUP_SKIPPED.'</h4>';
	?>
	<p><a href="index.php"><?=H_SETUP_BACK?></a>
	<p><a href="<?=WCHOME?>"><?=WC?></a>
</body>
</html>