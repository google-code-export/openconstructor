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
 * $Id: i_data.php,v 1.12 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/wcdatasource._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();

switch(@$_POST['action'])
{
	case 'create_dshtmltext':
		require_once(LIBDIR.'/htmltext/dshtmltext._wc');
		$_ds=new DSHTMLText();

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}

		if(is_numeric(@$_POST['dssize'])) $_ds->setSize($_POST['dssize']);
		if(intval(@$_POST['introsize'])>0) $_ds->introSize=$_POST['introsize'];
		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_htmltext.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dshtmltext':
		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}

		$_ds = $dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
		if(intval(@$_POST['introsize'])>0)
			$_ds->introSize=$_POST['introsize'];
		if(@$_POST['stripHTML']=='true')
		{
			$_ds->stripHTML=true;
			$_ds->setAllowedTags(@$_POST['allowedTags']);
			$_ds->encodeemail = @$_POST['encodeemail'] == 'true';
		}
		else
			$_ds->stripHTML=false;

		if(@$_POST['isindexable'] == true) {
			$_ds->setIndexable(true);
		} else {
			$_ds->setIndexable(false);
		}
		if((@$_POST['autoPublish']=='true') != $_ds->autoPublish && WCS::decide($_ds, 'publishdoc'))
			$_ds->autoPublish = !$_ds->autoPublish;
		$_ds->name = $_POST['ds_name'];
		$_ds->description=@$_POST['description'];
		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dsphpsource':
		require_once(LIBDIR.'/phpsource/dsphpsource._wc');
		$_ds=new DSPHPSource();

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		if(is_numeric(@$_POST['dssize'])) $_ds->setSize($_POST['dssize']);
		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_phpsource.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dsphpsource':
		require_once(LIBDIR.'/phpsource/dsphpsource._wc');
		$_ds=new DSPHPSource();
		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds = $dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
		$_ds->name=$_POST['ds_name'];
		$_ds->description=@$_POST['description'];
		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dsfile':
		require_once(LIBDIR.'/file/dsfile._wc');
		$_ds=new DSFile(@$_POST['folder'] ? $_POST['folder'] : true);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if(is_numeric(@$_POST['dssize'])) $_ds->setSize($_POST['dssize']);
		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result && is_numeric($result)) { // папка создана, в result id раздела
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_file.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		} elseif($result) { // папка не создана, в result текст ошибки
			$fail[]='folder';
			$message[]=$result;
			header('Location: '.make_fail_header($message,$fail,$_POST));
		} else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dsfile':
		require_once(LIBDIR.'/file/dsfile._wc');
		$_ds=new DSFile();
		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds = $dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
		$_ds->name=$_POST['ds_name'];
		$_ds->description=@$_POST['description'];
		$_ds->setGroups((array) @$_POST['groups']);
		$_ds->setTypes(explode(',', @$_POST['filetypes']));
		$_ds->setIndexable(isset($_POST['isindexable']));
		$_ds->autoName = @$_POST['autoname'] == 'true';
		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dshybrid':
		require_once(LIBDIR.'/hybrid/dshybrid._wc');
		require_once(LIBDIR.'/hybrid/dshybridfactory._wc');
		$dsf=new DSHybridFactory();

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$result=$dsf->create($_POST['ds_key'],$_POST['ds_name'],$_POST['description'],@$_POST['parent']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result->ds_id.'";window.location.href="edit_hybrid.php?ok=1&ds_id='.$result->ds_id.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dshybrid':
		require_once(LIBDIR.'/hybrid/dshybrid._wc');
		$_ds=new DSHybrid();
		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds = $dsm->load($_POST['ds_id']);
		$_ds->name=$_POST['ds_name'];
		$_ds->description=@$_POST['description'];
		$_ds->editTpl = (int) @$_POST['editTpl'];
		if(@$_POST['isindexable']) {
			$_ds->indexedDoc = @$_POST['indexedDoc'];
			$_ds->docIntro = @$_POST['docIntro'];
		}
		$_ds->setIndexable(isset($_POST['isindexable']));
		if((@$_POST['autoPublish'] == 'true') != $_ds->autoPublish && WCS::decide($_ds, 'publishdoc'))
			$_ds->autoPublish = !$_ds->autoPublish;
		
		//Events
		$_ds->listeners['onDocCreate'] = $_POST['onDocCreate'];
		$_ds->listeners['onDocUpdate'] = $_POST['onDocUpdate'];
		$_ds->listeners['onDocDelete'] = $_POST['onDocDelete'];
		
		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dsrating':
		require_once(LIBDIR.'/rating/dsrating._wc');
		$_ds = new DSRating();
		$fail = $message = array();
		if(!@$_POST['ds_name'])
			$fail[] = 'ds_name';
		if($ref = make_fail_header($message, $fail, $_POST)) {
			header('Location: '.$ref);
			die();
		}
		$result = $_ds->create($_POST['ds_name'], (string) @$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_rating.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(), $fail, $_POST));
	break;

	case 'edit_dsrating':
		assert(@$_POST['ds_id'] > 0);
		require_once(LIBDIR.'/rating/dsrating._wc');
		$_ds = new DSRating();

		$fail = $message = array();
		if(!@$_POST['ds_name'])
			$fail[] = 'ds_name';
		if($ref = make_fail_header($message, $fail, $_POST)) {
			header('Location: '.$ref);
			die();
		}
		$_ds = $dsm->load($_POST['ds_id']);
		$_ds->name = $_POST['ds_name'];
		$_ds->description = (string) @$_POST['description'];

		$_ds->stripHTML = @$_POST['stripHTML'] == 'true';
		if($_ds->stripHTML)
			$_ds->setAllowedTags(@$_POST['allowedTags']);
		$_ds->fakeRaters = (int) @$_POST['fakeRaters'];

		$_ds->_setRatingBounds(@$_POST['minRating'], @$_POST['maxRating']);
		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	default:
	break;
}
?>
