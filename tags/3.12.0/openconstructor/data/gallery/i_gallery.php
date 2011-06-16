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
 * $Id: i_gallery.php,v 1.11 2007/03/02 10:06:41 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
WCS::requireAuthentication();
require_once(LIBDIR.'/dsmanager._wc');
require_once(LIBDIR.'/wcdatasource._wc');
$dsm = new DSManager();

switch(@$_POST['action'])
{
	case 'create_image':
		assert(trim(@$_POST['header']) != '');
		if(@$_POST['hybridid'] > 0) {
			$ownerDs = WCDataSource::loadByDoc($_POST['hybridid']);
			$ownerDoc = $ownerDs->getDocument($_POST['hybridid']);
			WCS::assertValue(WCS::decide($ownerDoc, 'editdoc') || WCS::decide($ownerDs, 'editdoc'), $ownerDoc, 'editdoc');
			WCS::runAs(WCS_ROOT_ID);
		}
		$_ds = $dsm->load(@$_POST['ds_id']);
		$date = strtotime(@$_POST['date']);

		$img = @$_FILES['image']['size'] && $_FILES['image']['error'] == UPLOAD_ERR_OK
			? $_FILES['image']['tmp_name']
			: null;
		if(@$_POST['manual_img'] == 'true') {
			$imgIntro = @$_FILES['image_intro']['size'] && $_FILES['image_intro']['error'] == UPLOAD_ERR_OK
				? $_FILES['image_intro']['tmp_name']
				: null;
		} else
			$imgIntro = true;
		
		$result=$_ds->add(
			$_POST['header'],
			@$_POST['html'],
			$date,
			$img,
			$imgIntro
		);
		if($result)
		{
			if(@$_POST['hybridid'] > 0 && @$_POST['fieldid'] > 0){
				if($_ds->setHybridField((int)@$_POST['hybridid'],(int)@$_POST['fieldid'], $result)){
					echo "<script>try{window.opener['".@$_POST['callback']."']($result,'{$_POST['header']}',{$_POST['fieldid']});}catch(e){}";
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
	
	case 'edit_image':
		assert(@$_POST['id'] > 0);
		assert(trim(@$_POST['header']) != '');
		$_ds = $dsm->load(@$_POST['ds_id']);
		$date = strtotime(@$_POST['date']);
		
		$img = @$_FILES['image']['size'] && $_FILES['image']['error'] == UPLOAD_ERR_OK
			? $_FILES['image']['tmp_name']
			: null;
		if(@$_POST['manual_img'] == 'true') {
			$imgIntro = @$_FILES['image_intro']['size'] && $_FILES['image_intro']['error'] == UPLOAD_ERR_OK
				? $_FILES['image_intro']['tmp_name']
				: null;
		} else
			$imgIntro = true;
		
		$result=$_ds->update(
			$_POST['id'],
			$_POST['header'],
			@$_POST['html'],
			$date,
			$img,
			$imgIntro
		);
		if($result)
			header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'delete_image':
		if(isset($_POST['ds_id']))
		{
			$_ds = $dsm->load($_POST['ds_id']);
			$_ds->delete(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'remove_ds':
		if(isset($_POST['ds_id']))
		{
			$_ds = $dsm->load($_POST['ds_id']);
			$_ds->remove();
		}
//		header('Location: http://'.$_host.WCHOME.'/data/');
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/data/">');
	break;
	
	case 'move_documents':
		assert(@$_POST['ds_id'] > 0 && @$_POST['dest_ds_id'] > 0);
		$failed = false;
		if(isset($_POST['ids'])) {
			$_ds = $dsm->load($_POST['ds_id']);
			$dest_ds = $dsm->load($_POST['dest_ds_id']);
			assert($_ds->ds_id > 0 && $dest_ds->ds_id > 0 && $_ds->ds_id != $dest_ds->ds_id);
			$real_ids=$dest_ds->get_real_ids();
			$ids=$_POST['ids'];
			foreach($ids as $k=>$id)
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
				$image=NULL;
				$image_intro=false;
				if(@file_exists($_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.$_doc['id'].'.'.$_doc['img_type']))
					$image=$_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.$_doc['id'].'.'.$_doc['img_type'];
				if(@file_exists($_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.$_doc['id'].'_.'.$_doc['img_type']))
					$image_intro=$_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.$_doc['id'].'_.'.$_doc['img_type'];
				$result=$dest_ds->add($_doc['header'],$_doc['content'],$image,$image_intro);
				if(!$result)
					unset($ids[$k]);
			}
			$_ds->delete(implode(',',$ids));
		}
		if(!$failed){
//			header('Location: '.$_SERVER['HTTP_REFERER']);
			die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
		}
		else
			echo '<p><a href="'.$_SERVER['HTTP_REFERER'].'">Back</a>';
	break;
	
	case 'publish_documents':
		if(isset($_POST['ds_id']))
		{
			$_ds = $dsm->load($_POST['ds_id']);
			$_ds->publish(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'unpublish_documents':
		if(isset($_POST['ds_id']))
		{
			$_ds = $dsm->load($_POST['ds_id']); 
			$_ds->unpublish(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
//Case blocks for aliases	
	case 'create_alias':
		assert(@sizeof($_POST['ids']) > 0);
		assert($_ds = $dsm->load(@$_POST['ds_id']));
		$_ids = $_POST['ids'];
		foreach($_ids as $id){
			$_ds->create_alias($id);//$result=
		}
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	default:
	break;
}
?>