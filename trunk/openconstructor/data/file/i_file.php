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
 * $Id: i_file.php,v 1.9 2007/03/02 10:06:41 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
WCS::requireAuthentication();
require_once(LIBDIR.'/wcdatasource._wc');


switch(@$_POST['action'])
{
	case 'create_file':
		if(@$_POST['hybridid'] > 0) {
			$hDoc = &WCDataSource::getHybridDoc($_POST['hybridid']);
			WCS::assert($hDoc, 'editdoc');
			WCS::runAs(WCS_ROOT_ID);
		}
		require_once(LIBDIR.'/file/dsfile._wc');
		$fail=array();
		$message=array();
		
		if(!@$_POST['name'])
			$fail[]='name';
//		if(!@$_POST['fname'])
//			$fail[]='fname';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds=new DSFile();
		$_ds->load(@$_POST['ds_id']);
		$result=$_ds->add($_POST['name'], @$_POST['description'], @$_POST['fname'],@$_POST['type'], @$_FILES['file']['tmp_name']);
		if(is_array($result)&&is_int($result[0]))
		{
			if(@$_POST['hybridid'] > 0 && @$_POST['fieldid'] > 0){
				if($_ds->setHybridField((int)@$_POST['hybridid'],(int)@$_POST['fieldid'], $result[0])){
					echo "<script>try{window.opener['".@$_POST['callback']."']({$result[0]},'{$_POST['name']}',{$_POST['fieldid']});}catch(e){}";
				}else {
					$_ds->delete($result);
					die('<script>try{window.close();}catch(e){}</script>');
				}
			} else
				echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}';
			if(sizeof($result)>1)
				echo 'window.location.href="edit.php?result='.addslashes(urlencode($result[1])).'&ok=1&ds_id='.$_POST['ds_id'].'&id='.$result[0].'";</script>';
			else
				echo 'window.location.href="edit.php?ds_id='.$_POST['ds_id'].'&id='.$result[0].'";</script>';
		} else
			header('Location: '.make_fail_header(is_array($result)?$result[1]:array($result),$fail,$_POST));
	break;
	
	case 'edit_file':
		require_once(LIBDIR.'/file/dsfile._wc');
		assert(@$_POST['id'] > 0);
		
		$fail=array();
		$message=array();
		
		if(!@$_POST['name'])
			$fail[]='name';
		if(!@$_POST['fname'])
			$fail[]='fname';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds=new DSFile();
		$_ds->load(@$_POST['ds_id']);
		$result=$_ds->update($_POST['id'],$_POST['name'], @$_POST['description'],@$_POST['fname'],@$_POST['type'], @$_FILES['file']['tmp_name']);
		if($result===true)
			header('Location: edit.php?ds_id='.$_POST['ds_id'].'&id='.$_POST['id'].'&ok=1');
		else {
			$p=$_POST;
			unset($p['fname']);
			header('Location: '.make_fail_header(array($result),$fail,$p).'&ok=1');
		}
	break;
	
	case 'delete_file':
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/file/dsfile._wc');
			$_ds=new DSFile();
			$_ds->load($_POST['ds_id']);
			$_ds->delete(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'remove_ds':
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/file/dsfile._wc');
			$_ds=new DSFile();
			$_ds->load($_POST['ds_id']);
			$_ds->remove();
		}
//		header('Location: http://'.$_host.WCHOME.'/data/');
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/data/">');
	break;
	
	case 'move_documents':
		assert(@$_POST['ds_id'] > 0 && @$_POST['dest_ds_id'] > 0);
		if(isset($_POST['ids'])) {
			require_once(LIBDIR.'/file/dsfile._wc');
			$_ds = new DSFile();
			$_ds->load($_POST['ds_id']);
			$_dest_ds = new DSFile();
			$_dest_ds->load($_POST['dest_ds_id']);
			assert($_ds->ds_id > 0 && $_dest_ds->ds_id > 0);
			$_dest_ds->moveFiles($_ds,$_POST['ids']);
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	default:
	break;
}
?>
