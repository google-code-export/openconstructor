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
 * $Id: i_phpsource.php,v 1.9 2007/03/02 10:06:41 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
WCS::requireAuthentication();
require_once(LIBDIR.'/wcdatasource._wc');

switch(@$_POST['action'])
{
	case 'create_source':
		require_once(LIBDIR.'/phpsource/dsphpsource._wc');
		assert(trim(@$_POST['header']) != '');
		$_ds=new DSPHPSource();
		$_ds->load(@$_POST['ds_id']);
		$result=$_ds->add($_POST['header'], @$_POST['html']);
		if($result) {
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}';
			echo 'window.location.href="edit.php?ds_id='.$_POST['ds_id'].'&id='.$result.'&caret='.@$_POST['caret'].'";</script>';
		}
	break;
	
	case 'edit_source':
		require_once(LIBDIR.'/phpsource/dsphpsource._wc');
		assert(@$_POST['id'] > 0);
		assert(trim(@$_POST['header']) != '');
		$_ds=new DSPHPSource();
		$_ds->load(@$_POST['ds_id']);
		$result=$_ds->update($_POST['id'], $_POST['header'], @$_POST['html']);
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
	
	case 'delete_source':
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/phpsource/dsphpsource._wc');
			$_ds=new DSPHPSource();
			$_ds->load($_POST['ds_id']);
			$_ds->delete(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'remove_ds':
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
	
	case 'move_documents':
		assert(@$_POST['ds_id'] > 0 && @$_POST['dest_ds_id'] > 0);
		if(isset($_POST['ids'])) {
			require_once(LIBDIR.'/phpsource/dsphpsource._wc');
			$_ds = new DSPHPSource();
			assert($_ds->load($_POST['ds_id']));
			$_dest_ds = new DSPHPSource();
			assert($_dest_ds->load($_POST['dest_ds_id']));
			assert($_ds->ds_id != $_dest_ds->ds_id);
			$_dest_ds->moveDocuments($_ds, $_POST['ids']);
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	default:
	break;
}
?>
