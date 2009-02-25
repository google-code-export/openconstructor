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
 * $Id: i_htmltext.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	
switch(@$_POST['action'])
{
	case 'create_html':
		$_ds = &$dsm->load(@$_POST['ds_id']); 
		if(@$_POST['autointro'] != 'true') {
			$intro = @$_POST['intro'];
			if(substr($intro, 0, 2) == '<P')
				$intro = utf8_substr($intro, utf8_strpos($intro, '>', 1) + 1);
			$intro = str_replace('<P></P>', '', $intro);
		} else
			$intro = null;
		$result=$_ds->add(@$_POST['uri_id'], @$_POST['html'], @$_POST['noIndex'] == 'true', $intro);
		if($result) {
			$result = $_POST['uri_id'];
//				$_ds->update($result,@$_POST['html'],$intro?$intro:' ');
			if(0 && @$_POST['hybridid'] > 0 && @$_POST['fieldid'] > 0){
				if($_ds->setHybridField((int)@$_POST['hybridid'],(int)@$_POST['fieldid'], $result)){
					echo "<script>try{window.opener['".@$_POST['callback']."']($result,'{$_POST['uri_id']}',{$_POST['fieldid']});}catch(e){}";
					die('location.href = "edit.php?id='.$result.'&ds_id='.$_ds->ds_id.'";</script>');
				}else {
					$_ds->delete($result);
					die('<script>try{window.close();}catch(e){}</script>');
				}
			}
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}';
			echo 'window.parent.location.href="edit.php?ds_id='.$_POST['ds_id'].'&id='.$_POST['uri_id'].'";</script>';
		}
	break;
	
	case 'edit_html':
		$_ds = &$dsm->load(@$_POST['ds_id']); 
		$intro=@$_POST['intro'];
		if(substr($intro, 0, 2) == '<P')
			$intro = utf8_substr($intro, utf8_strpos($intro, '>', 1) + 1);
		$intro=str_replace('<P></P>','',$intro);
		$result=$_ds->update(@$_POST['uri_id'],@$_POST['html'], @$_POST['noIndex'] == 'true', @$_POST['autointro'] == 'true' ? null : $intro);
		if($result)
			header('Location: '.$_SERVER['HTTP_REFERER']);
		//	echo '<script>window.opener.location.reload();</script>Succesfully created!';
	break;
	
	case 'delete_htmltext':
		$_ds = &$dsm->load(@$_POST['ds_id']);
		$_ds->delete(implode(',',@$_POST['ids']));
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'remove_ds':
		if(isset($_POST['ds_id']))
		{
			$_ds = &$dsm->load($_POST['ds_id']); 
			$_ds->remove();
		}
//		header('Location: http://'.$_host.WCHOME.'/data/');
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/data/">');
	break;
	
	case 'move_documents':
		assert(@$_POST['ds_id'] > 0 && @$_POST['dest_ds_id'] > 0);
		if(isset($_POST['ids'])) {
			assert($_ds = &$dsm->load($_POST['ds_id']));
			assert($dest_ds = &$dsm->load($_POST['dest_ds_id']));
			assert($_ds->ds_id != $dest_ds->ds_id);
			$dest_ds->delete(implode(',',$_POST['ids']));
			foreach($_POST['ids'] as $id)
			{
				$_doc=$_ds->get_record($id);
				$dest_ds->add($_doc['id'],$_doc['html']);
			}
			$_ds->delete(implode(',',$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
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
