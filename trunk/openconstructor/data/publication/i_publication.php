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
 * $Id: i_publication.php,v 1.10 2007/03/02 10:06:44 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/dsmanager._wc');
	require_once(LIBDIR.'/wcdatasource._wc');
	$dsm = new DSManager();
	
switch(@$_POST['action'])
{
	case 'create_publication':
		assert(trim(@$_POST['header']) != '');
		if(@$_POST['hybridid'] > 0) {
			$hDoc = &WCDataSource::getHybridDoc($_POST['hybridid']);
			WCS::assert($hDoc, 'editdoc');
			WCS::runAs(WCS_ROOT_ID);
		}
		$_ds = &$dsm->load(@$_POST['ds_id']); 
		$date = getTimestamp(@$_POST['year'], @$_POST['month'], @$_POST['day'], @$_POST['time']);
		if(@$_POST['autointro'] != 'true') {
			$intro = @$_POST['intro'];
			if(substr($intro, 0, 2) == '<P')
				$intro = utf8_substr($intro, utf8_strpos($intro, '>', 1) + 1);
			$intro = str_replace('<P></P>', '', $intro);
		} else
			$intro = null;
		$result = $_ds->add($_POST['header'], @$_POST['html'], $date, $intro);
		if($result) {
			if(@$_POST['main_publication']=='yes')
				$_ds->set_main($result);
			if(@$_POST['hybridid'] > 0 && @$_POST['fieldid'] > 0){
				if($_ds->setHybridField((int)@$_POST['hybridid'],(int)@$_POST['fieldid'], $result)){
					echo "<script>try{window.opener['".@$_POST['callback']."']($result,'{$_POST['header']}',{$_POST['fieldid']});}catch(e){}";
					die('location.href = "edit.php?id='.$result.'&ds_id='.$_ds->ds_id.'";</script>');
				} else {
					$_ds->delete($result);
					die('<script>try{window.close();}catch(e){}</script>');
				}
			}
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}';
			echo 'window.location.href="edit.php?ds_id='.$_POST['ds_id'].'&id='.$result.'";</script>';
		}
	break;

	case 'edit_publication':
		assert(@$_POST['id'] > 0);
		assert(trim(@$_POST['header']) != '');
		$_ds = &$dsm->load(@$_POST['ds_id']); 
		if(@$_POST['main_publication']=='yes')
			$_ds->set_main($_POST['id']);
		elseif(@$_POST['main_publication']=='was')
			$_ds->set_main('-1');
		if(@$_POST['published']=='true')
			$_ds->publish($_POST['id']);
		else
			$_ds->unpublish($_POST['id']);
		$intro=@$_POST['intro'];
		if(substr($intro, 0, 2) == '<P')
			$intro = utf8_substr($intro, utf8_strpos($intro, '>', 1) + 1);
		$intro=str_replace('<P></P>','',$intro);
		$date = getTimestamp(@$_POST['year'], @$_POST['month'], @$_POST['day'], @$_POST['time']);
		$result = $_ds->update($_POST['id'], $_POST['header'], @$_POST['html'], $date, @$_POST['autointro'] == 'true' ? null : $intro);
		
		$img = @$_FILES['image']['size'] && $_FILES['image']['error'] == UPLOAD_ERR_OK
			? $_FILES['image']['tmp_name']
			: null;
		if(@$_POST['manual_img'] == 'true') {
			$imgIntro = @$_FILES['image_intro']['size'] && $_FILES['image_intro']['error'] == UPLOAD_ERR_OK
				? $_FILES['image_intro']['tmp_name']
				: null;
		} else
			$imgIntro = true;
		if($img || $imgIntro)
			$_ds->update_images($_POST['id'], $img, $imgIntro);
		if($result)
			header('Location: '.$_SERVER['HTTP_REFERER']);
	break;

	case 'delete_publication':
		if(isset($_POST['ds_id'])) {
			$_ds = &$dsm->load($_POST['ds_id']); 
			$_ds->delete(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'remove_ds':
		if(isset($_POST['ds_id'])) {
			$_ds = &$dsm->load($_POST['ds_id']);
			$_ds->remove();
		}
//		header('Location: http://'.$_host.WCHOME.'/data/');
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/data/">');
	break;
	
	case 'move_documents':
		assert(@$_POST['ds_id'] > 0 && @$_POST['dest_ds_id'] > 0);
		$failed = false;
		if(isset($_POST['ids'])) {
			assert($_ds = &$dsm->load($_POST['ds_id'])); 
			assert($dest_ds = &$dsm->load($_POST['dest_ds_id']));
			assert($_ds->ds_id != $dest_ds->ds_id);
			if($dest_ds->attachGallery) {
				$_dsg = new DSGallery();
				$_dsg->images = $dest_ds->attachGallery;
			}
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
				$result = $dest_ds->add($_doc['header'],$_doc['content'],$_doc['date'],$_doc['intro'], $_doc['gallery']);
				if(!$result) {
					unset($ids[$k]);
					continue;
				}
				$image=NULL;
				$image_intro=false;
				if(@file_exists($_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.$_doc['id'].'.'.$_doc['img_type']))
					$image=$_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.$_doc['id'].'.'.$_doc['img_type'];
				if(@file_exists($_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.$_doc['id'].'_.'.$_doc['img_type']))
					$image_intro=$_SERVER['DOCUMENT_ROOT'].FILES.$_ds->imagepath.$_doc['id'].'_.'.$_doc['img_type'];
				if(!$dest_ds->update_images($result,$image,$image_intro))
				{
					$failed = true;
					echo 'Publication header: '.$_doc['header'].'<br>';
				}
			}
			$_ds->delete(implode(',',$ids),@$dest_ds->attachGallery?true:false);
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
			$_ds = &$dsm->load($_POST['ds_id']); 
			$_ds->publish(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'unpublish_documents':
		if(isset($_POST['ds_id']))
		{
			$_ds = &$dsm->load($_POST['ds_id']);
			$_ds->unpublish(implode(',',@$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
//Case blocks for aliases	
	case 'create_alias':
		assert(@sizeof($_POST['ids']) > 0);
		assert($_ds = &$dsm->load(@$_POST['ds_id'])); 
		$_ids = $_POST['ids'];
		foreach($_ids as $id){
			$_ds->create_alias($id);//$result=
		}
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'view_detail':
		foreach((array) @$_COOKIE['vd'] as $key => $val){
			if(!array_key_exists($key, (array) @$_POST['vdetail']))
				setcookie('vd['.$key.']', '', time() - 3600, WCHOME.'/data/');
		}
		foreach($_POST['vdetail'] as $key => $val)
			setcookie('vd['.$key.']', $key, 0, WCHOME.'/data/');
		setcookie('pagesize', $_POST['pagesize'], 0, WCHOME.'/data/');
		setcookie('vd[_touched]', '_touched', 0, WCHOME.'/data/');
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	default:
	break;
}
?>
