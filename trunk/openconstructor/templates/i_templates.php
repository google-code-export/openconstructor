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
 * $Id: i_templates.php,v 1.11 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	$dstype = @$_POST['dstype'];
	if(!$dstype)
		$dstype = @$_GET['dstype'];
	require_once(LIBDIR.'/wcdatasource._wc');
	
switch(@$_POST['action'])
{
	case 'create_tpl':
		require_once(LIBDIR.'/templates/wctemplate._wc');
		require_once(LIBDIR.'/templates/wctemplates._wc');
		assert(trim(@$_POST['tpl_name']) != '');
		$tpls = new WCTemplates();
		$tpl = new WCTemplate(@$_POST['type'],$_POST['tpl_name']);
		$tpl->tpl = @$_POST['html'];
		$tpl->setMockup(@$_POST['mockup']);
		$result = $tpls->add(@$_POST['dstype'], $tpl);
		if($result) {
			echo '<script> try {';
			if(@$_POST['select']) {
				echo sprintf('window.opener.addAndSelectTpl(%d, "%s");', $tpl->id, escapeTags($tpl->name));
			} else {
				echo 'window.opener.location.href = window.opener.location.href;';
			}
			echo '} catch (e){}';
			echo 'window.location.href="edit'.(@$_POST['type']=='page'?'page':'').'.php?dstype='.$_POST['dstype'].'&id='.$tpl->id.'&caret='.@$_POST['caret'].'";';
			echo '</script>';
		}
	break;
	
	case 'edit_tpl':
		require_once(LIBDIR.'/templates/wctemplate._wc');
		require_once(LIBDIR.'/templates/wctemplates._wc');
		assert(@$_POST['id'] > 0);
		assert(trim(@$_POST['tpl_name']) != '');
		$tpls = new WCTemplates();
		$tpl = &$tpls->load($_POST['id']);
		$tpl->name = $_POST['tpl_name'];
		$tpl->tpl = @$_POST['html'];
		$tpl->setMockup(@$_POST['mockup']);
		$result = $tpls->update($tpl);
		if($result) {
			$ref = $_SERVER['HTTP_REFERER'];
			if(@$_POST['caret']) {
				if(strpos($ref, '&caret=') !== false || strpos($ref, '?caret=') !== false)
					$ref = preg_replace('/(\\?|&)caret=\\d+/', '$1caret='.$_POST['caret'], $ref);
				else
					$ref = $ref.(utf8_strpos($ref, '?') === false ? '?' : '&').'caret='.$_POST['caret'];
			}
			header('Location: '.$ref);
		}
	break;
	
	case 'delete_tpl':
		require_once(LIBDIR.'/templates/wctemplates._wc');
		$tpls=new WCTemplates();
		$tpls->remove(implode(',',@$_POST['ids']));
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'copy_tpl':
		die();
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/phpsource/dsphpsource._wc');
			$_ds=new DSPHPSource();
			$_ds->load($_POST['ds_id']);
			$_ds->remove();
		}
//		header('Location: http://'.$_host.WCHOME.'/data/');
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/data/">');
	break;
	
	default:
	break;
}
?>
