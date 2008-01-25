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

		$_ds = &$dsm->load($_POST['ds_id']);
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

	case 'create_dspublication':
		require_once(LIBDIR.'/publication/dspublication._wc');
		$_ds=new DSPublication();

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
		if(@$_POST['isindexable']) $_ds->setIndexable(true);
		if(@$_POST['attach_gallery'] == 'true') {
			$_ds->set_images(@$_POST['g_xmin'],@$_POST['g_ymin'],@$_POST['g_xmax'],@$_POST['g_ymax'],isset($_POST['g_img_main']),isset($_POST['g_img_intro'])?@$_POST['g_intro']:false);
			$_ds->attachGallery=$_ds->images;
			$_ds->images=NULL;
		}
		$_ds->set_images(@$_POST['xmin'],@$_POST['ymin'],@$_POST['xmax'],@$_POST['ymax'],isset($_POST['img_main']),isset($_POST['img_intro'])?@$_POST['intro']:false);

		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_publication.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dspublication':
		require_once(LIBDIR.'/publication/dspublication._wc');
		$_ds=new DSPublication();

		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}

		$_ds = &$dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
		if(intval(@$_POST['introsize'])>0)
			$_ds->introSize=$_POST['introsize'];
		$_ds->name=$_POST['ds_name'];
		$_ds->description=@$_POST['description'];
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
			$_ds->autoPublish=!$_ds->autoPublish;
		if($_ds->attachGallery) {
			$tmp=$_ds->images;
			$_ds->set_images(@$_POST['g_xmin'],@$_POST['g_ymin'],@$_POST['g_xmax'],@$_POST['g_ymax'],@$_ds->attachGallery['main'],@$_ds->attachGallery['intro']?(intval($_POST['g_intro'])?$_POST['g_intro']:$_ds->attachGallery['intro']):NULL);
			$_ds->attachGallery=$_ds->images;
			$_ds->images=$tmp;
		}
		if($_ds->images)
			$_ds->set_images(@$_POST['xmin'],@$_POST['ymin'],@$_POST['xmax'],@$_POST['ymax'],@$_ds->images['main'],@$_ds->images['intro']?(intval($_POST['intro'])?$_POST['intro']:$_ds->images['intro']):NULL);
		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dstextpool':
		require_once(LIBDIR.'/textpool/dstextpool._wc');
		$_ds=new DSTextPool();

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		if(@$_POST['isindexable'] == false) $_ds->setIndexable(false);
		if(is_numeric(@$_POST['dssize'])) $_ds->setSize($_POST['dssize']);
		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_textpool.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dstextpool':
		require_once(LIBDIR.'/textpool/dstextpool._wc');
		$_ds=new DSTextPool();

		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds = &$dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
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
			$_ds->autoPublish=!$_ds->autoPublish;
		$_ds->name=$_POST['ds_name'];
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
		$_ds = &$dsm->load($_POST['ds_id']);
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
		$_ds = &$dsm->load($_POST['ds_id']);
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

	case 'create_dsevent':
		require_once(LIBDIR.'/event/dsevent._wc');
		$_ds=new DSEvent();


		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}

		if(@$_POST['isindexable'] == false)  $_ds->setIndexable(false);

		if(is_numeric(@$_POST['dssize'])) $_ds->setSize($_POST['dssize']);
		if(intval(@$_POST['introsize'])>0) $_ds->introSize=$_POST['introsize'];
		$_ds->set_images(@$_POST['xmin'],@$_POST['ymin'],@$_POST['xmax'],@$_POST['ymax'],isset($_POST['img_main']),isset($_POST['img_intro'])?@$_POST['intro']:false);
		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_event.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dsevent':
		require_once(LIBDIR.'/event/dsevent._wc');
		$_ds=new DSEvent();
		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds = &$dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
		if(intval(@$_POST['introsize'])>0)
			$_ds->introSize=$_POST['introsize'];
		$_ds->name=$_POST['ds_name'];
		$_ds->description=@$_POST['description'];
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
			$_ds->autoPublish=!$_ds->autoPublish;
		if($_ds->images)
			$_ds->set_images(@$_POST['xmin'],@$_POST['ymin'],@$_POST['xmax'],@$_POST['ymax'],@$_ds->images['main'],@$_ds->images['intro']?(intval($_POST['intro'])?$_POST['intro']:$_ds->images['intro']):NULL);

		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dsgallery':
		require_once(LIBDIR.'/gallery/dsgallery._wc');
		$_ds=new DSGallery();

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}

		if(@$_POST['isindexable'] == false) $_ds->setIndexable(false);

		if(is_numeric(@$_POST['dssize'])) $_ds->setSize($_POST['dssize']);
		$_ds->set_images(@$_POST['xmin'],@$_POST['ymin'],@$_POST['xmax'],@$_POST['ymax'],isset($_POST['img_main']),isset($_POST['img_intro'])?@$_POST['intro']:false);

		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_gallery.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dsgallery':
		require_once(LIBDIR.'/gallery/dsgallery._wc');
		$_ds=new DSGallery();
		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds = &$dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
		$_ds->name=$_POST['ds_name'];
		$_ds->description=@$_POST['description'];
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
			$_ds->autoPublish=!$_ds->autoPublish;
		if($_ds->images)
			$_ds->set_images(@$_POST['xmin'],@$_POST['ymin'],@$_POST['xmax'],@$_POST['ymax'],@$_ds->images['main'],@$_ds->images['intro']?(intval($_POST['intro'])?$_POST['intro']:$_ds->images['intro']):NULL);

		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dsguestbook':
		require_once(LIBDIR.'/guestbook/dsguestbook._wc');
		$_ds=new DSGuestBook();

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		if(@$_POST['isindexable'] == false) $_ds->setIndexable(false);
		if(is_numeric(@$_POST['dssize'])) $_ds->setSize($_POST['dssize']);
		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_guestbook.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dsguestbook':
		require_once(LIBDIR.'/guestbook/dsguestbook._wc');
		$_ds=new DSGuestBook();
		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds = &$dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
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
			$_ds->autoPublish=!$_ds->autoPublish;
		$_ds->name=$_POST['ds_name'];
		$_ds->description=@$_POST['description'];
		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dsarticle':
		require_once(LIBDIR.'/article/dsarticle._wc');
		$_ds=new DSArticle();

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		if(@$_POST['isindexable'] == false) $_ds->setIndexable(false);
		if(is_numeric(@$_POST['dssize'])) $_ds->setSize($_POST['dssize']);
		if(intval(@$_POST['introsize'])>0) $_ds->introSize=$_POST['introsize'];
		$_ds->set_images(@$_POST['xmin'],@$_POST['ymin'],@$_POST['xmax'],@$_POST['ymax'],isset($_POST['img_intro']));
		$result=$_ds->create($_POST['ds_name'],$_POST['description']);
		if($result)
			echo '<script>window.opener.location.href="'.WCHOME.'/data/?node='.$result.'";window.location.href="edit_article.php?ok=1&ds_id='.$result.'";</script>Succesfully created!';
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'edit_dsarticle':
		require_once(LIBDIR.'/article/dsarticle._wc');
		$_ds=new DSArticle();
		assert(@$_POST['ds_id'] > 0);

		$fail=array();
		$message=array();
		if(!@$_POST['ds_name'])
			$fail[]='ds_name';
		if($ref=make_fail_header($message,$fail,$_POST)){
			header('Location: '.$ref);
			die();
		}
		$_ds = &$dsm->load($_POST['ds_id']);
		if(is_numeric(@$_POST['dssize']))
			$_ds->setSize($_POST['dssize']);
		if(intval(@$_POST['introsize'])>0)
			$_ds->introSize=$_POST['introsize'];
		$_ds->name=$_POST['ds_name'];
		$_ds->description=@$_POST['description'];
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
			$_ds->autoPublish=!$_ds->autoPublish;

		if($_ds->images)
			$_ds->set_images(@$_POST['xmin'],@$_POST['ymin'],@$_POST['xmax'],@$_POST['ymax'],@$_ds->images['intro']);
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
		$_ds = &$dsm->load($_POST['ds_id']);
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
		if($_ds->save())
			header('Location: '.$_SERVER['HTTP_REFERER'].'&ok=1');
		else
			header('Location: '.make_fail_header(array(),$fail,$_POST));
	break;

	case 'create_dsrating':
		require_once(LIBDIR.'/rating/dsrating._wc');
		$_ds = & new DSRating();
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
		$_ds = & new DSRating();

		$fail = $message = array();
		if(!@$_POST['ds_name'])
			$fail[] = 'ds_name';
		if($ref = make_fail_header($message, $fail, $_POST)) {
			header('Location: '.$ref);
			die();
		}
		$_ds = &$dsm->load($_POST['ds_id']);
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
