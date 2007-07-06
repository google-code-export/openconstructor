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
 * $Id: i_guestbook.php,v 1.9 2007/03/02 10:06:41 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
WCS::requireAuthentication();
require_once(LIBDIR.'/wcdatasource._wc');

switch(@$_POST['action'])
{
	case 'create_message':
		require_once(LIBDIR.'/guestbook/dsguestbook._wc');
		assert(trim(@$_POST['subject']) != '');
		if(@$_POST['hybridid'] > 0) {
			$hDoc = &WCDataSource::getHybridDoc($_POST['hybridid']);
			WCS::assert($hDoc, 'editdoc');
			WCS::runAs(WCS_ROOT_ID);
		}
		$_ds=new DSGuestBook();
		$_ds->load(@$_POST['ds_id']);
		$date = strtotime(@$_POST['date']);
		$result=$_ds->add($_POST['subject'], @$_POST['html'], $date, @$_POST['author'], @$_POST['email']);
		if($result)
		{
			if(@$_POST['hybridid'] > 0 && @$_POST['fieldid'] > 0){
				if($_ds->setHybridField((int)@$_POST['hybridid'],(int)@$_POST['fieldid'], $result)){
					echo "<script>try{window.opener['".@$_POST['callback']."']($result,'{$_POST['subject']}',{$_POST['fieldid']});}catch(e){}";
					die('location.href = "edit.php?id='.$result.'&ds_id='.$_ds->ds_id.'";</script>');
				}else {
					$_ds->delete($result);
					die('<script>try{window.close();}catch(e){}</script>');
				}
			}
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}';
			echo 'window.location.href="edit.php?ds_id='.$_POST['ds_id'].'&id='.$result.'";</script>';
		}
	break;
	
	case 'edit_message':
		require_once(LIBDIR.'/guestbook/dsguestbook._wc');
		assert(@$_POST['id'] > 0);
		assert(trim(@$_POST['subject']) != '');
		$_ds=new DSGuestBook();
		$_ds->load(@$_POST['ds_id']);
		$date = strtotime(@$_POST['date']);
		if(@$_POST['published']=='true')
			$_ds->publish($_POST['id']);
		else
			$_ds->unpublish($_POST['id']);
		$result=$_ds->update($_POST['id'], $_POST['subject'], @$_POST['html'], $date, @$_POST['author'], @$_POST['email']);
		if($result)
			header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'delete_message':
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/guestbook/dsguestbook._wc');
			$_ds=new DSGuestBook();
			$_ds->load($_POST['ds_id']);
			$_ds->delete(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'remove_ds':
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/guestbook/dsguestbook._wc');
			$_ds=new DSGuestBook();
			$_ds->load($_POST['ds_id']);
			$_ds->remove();
		}
//		header('Location: http://'.$_host.WCHOME.'/data/');
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/data/">');
	break;
	
	case 'move_documents':
		assert(@$_POST['ds_id'] > 0 && @$_POST['dest_ds_id'] > 0);
		if(isset($_POST['ids'])) {
			require_once(LIBDIR.'/guestbook/dsguestbook._wc');
			$_ds = new DSGuestBook();
			assert($_ds->load($_POST['ds_id']));
			$dest_ds = new DSGuestBook();
			assert($dest_ds->load($_POST['dest_ds_id']));
			assert($_ds->ds_id != $dest_ds->ds_id);
			$real_ids=$dest_ds->get_real_ids();
			$ids=$_POST['ids'];
			foreach($_POST['ids'] as $id)
			{
				$_doc=$_ds->get_record($id);
				if(in_array($_doc['real_id'], $real_ids)){
					unset($ids[$k]);
					continue;
				}
				if($_doc['id']!=$_doc['real_id']){
					$dest_ds->create_alias($_doc['real_id']);
					continue;
				}
				$dest_ds->add($_doc['subject'],$_doc['html'],$_doc['author'],$_doc['email']);
			}
			$_ds->delete(implode(',',$ids));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'publish_documents':
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/guestbook/dsguestbook._wc');
			$_ds=new DSGuestBook();
			$_ds->load($_POST['ds_id']);
			$_ds->publish(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'unpublish_documents':
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/guestbook/dsguestbook._wc');
			$_ds=new DSGuestBook();
			$_ds->load($_POST['ds_id']);
			$_ds->unpublish(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'create_alias':
		require_once(LIBDIR.'/guestbook/dsguestbook._wc');
		assert(@sizeof($_POST['ids']) > 0);
		$_ds=new DSGuestBook();
		assert($_ds->load(@$_POST['ds_id']));
		$_ids = $_POST['ids'];
		foreach($_ids as $id)
			$_ds->create_alias($id);//$result=
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	default:
	break;
}
?>
