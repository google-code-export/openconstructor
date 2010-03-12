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
 * $Id: i_hybrid.php,v 1.11 2007/05/15 23:05:20 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
WCS::requireAuthentication();
require_once(LIBDIR.'/dsmanager._wc');
$dsm = new DSManager();

switch(@$_POST['action'])
{
	case 'add_field':
		$ds = &$dsm->load(@$_POST['ds_id']);
		assert($ds->ds_id > 0 && @$_POST['fieldclass'] != '' && utf8_strpos($_POST['fieldclass'], '/') === false);

		require_once(LIBDIR.'/hybrid/fields/'.$_POST['fieldclass'].'field._wc');
		switch ($_POST['fieldclass']) {
			case 'primitive':
				$f = new PrimitiveField(@$_POST['key'],@$_POST['header'],@$_POST['type'],@$_POST['isRequired']=='true');
			break;

			case 'document':
				$f = new DocumentField(@$_POST['key'],@$_POST['header'],@$_POST['type'],@$_POST['isRequired']=='true',@$_POST['document_isown']=='true',@$_POST['fromDS']);
			break;

			case 'array':
				$f = new ArrayField(@$_POST['key'],@$_POST['header'],@$_POST['type'],@$_POST['isRequired']=='true',@$_POST['array_isown']=='true',@$_POST['fromDS']);
			break;

			case 'datasource':
				$f = new DatasourceField(@$_POST['key'],@$_POST['header'],@$_POST['type'],@$_POST['fromDS']);
			break;

			case 'tree':
				$f = new TreeField(@$_POST['key'],@$_POST['header'],@$_POST['tree_type'], @$_POST['isRequired']=='true', @$_POST['tree_is_array'] == 'true');
			break;

			case 'enum':
				$f = new EnumField(@$_POST['key'],@$_POST['header'],@$_POST['enum_type'], @$_POST['isRequired']=='true', @$_POST['enum_is_array'] == 'true');
			break;

			case 'file':
				$f = new FileField(@$_POST['key'],@$_POST['header'],@$_POST['file_types'], @$_POST['isRequired']=='true');
			break;

			case 'rating':
				$f = new RatingField(@$_POST['key'], @$_POST['header'], @$_POST['rating_type']);
			break;

			default:
				assert(true == false);
		}
		$res = FieldFactory::createField($ds->getRecord(), $f);
		if($res)
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}location.href="field/'.$_POST['fieldclass'].'.php?id='.$res.'";</script>Loading...';
	break;

	case 'edit_field':
		require_once(LIBDIR.'/hybrid/fields/fieldfactory._wc');
		$f = FieldFactory::getField(@$_POST['id']);
		$old = wcfClone($f);

		switch ($f->family) {
			case 'primitive':
				$f->header = @$_POST['header'];
				$f->default = @$_POST['default'];
				$f->min = @$_POST['min'];
				$f->max = @$_POST['max'];
				$f->length = (int) @$_POST['length'];
				$f->allowedTags = @$_POST['allowedtags'];
				$f->regexp = @$_POST['regexp'];
				$f->isRequired = @$_POST['isRequired'] == 'true';
			break;

			case 'file':
				$f->header = @$_POST['header'];
				if((int)@$_POST['maxSize'])
					$f->maxSize = (int)@$_POST['maxSize'];
				$f->setImgBounds(@$_POST['imgBounds']);
				$f->setTypes(@$_POST['file_types']);
				$f->isRequired = @$_POST['isRequired'] == 'true';
			break;

			case 'document':
				$f->header = @$_POST['header'];
				$f->isRequired = @$_POST['isRequired'] == 'true';
			break;

			case 'array':
				$f->header = @$_POST['header'];
				$f->isRequired = @$_POST['isRequired'] == 'true';
			break;

			case 'datasource':
				$f->header = @$_POST['header'];
				$f->isRequired = @$_POST['isRequired'] == 'true';
			break;

			case 'tree':
				$f->header = @$_POST['header'];
				$f->isArray = @$_POST['tree_is_array'] == 'true';
				$f->isRequired = @$_POST['isRequired'] == 'true';
			break;

			case 'enum':
				$f->header = @$_POST['header'];
				$f->isArray = @$_POST['enum_is_array'] == 'true';
				$f->isRequired = @$_POST['isRequired'] == 'true';
			break;

			case 'rating':
				$f->header = @$_POST['header'];
				$f->isRequired = @$_POST['isRequired'] == 'true';
			break;
		}
		FieldFactory::updateField($old, $f);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;

	case 'remove_field':
		$ds = &$dsm->load(@$_POST['ds_id']);
		assert($ds->ds_id > 0);

		if(@sizeof($_POST['field']))
			FieldFactory::removeFields($ds->getRecord(), $_POST['field']);

		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;

	case 'create_hybrid':
		require_once(LIBDIR.'/wcdatasource._wc');
		require_once(LIBDIR.'/hybrid/hybriddocument._wc');
		require_once(LIBDIR.'/hybrid/dshybrid._wc');
		assert(isset($_POST['header']) && trim($_POST['header']) !='' && @$_POST['ds_id'] > 0);
		if(@$_POST['hybridid'] > 0) {
			$hDoc = &WCDataSource::getHybridDoc($_POST['hybridid']);
			WCS::assert($hDoc, 'editdoc');
			WCS::runAs(WCS_ROOT_ID);
		}
		$ds = &$dsm->load($_POST['ds_id']);

		$doc = $ds->getEmptyDocument();
		$doc->readValues($_POST);
		$doc->readFiles($_FILES);
		$ds->createDocument($doc);
		if($doc->id){
			if(@$_POST['hybridid'] > 0 && @$_POST['fieldid'] > 0){
				if($ds->setHybridField((int) @$_POST['hybridid'], (int) @$_POST['fieldid'], $doc->id)){
					echo "<script>try{window.opener['".@$_POST['callback']."']({$doc->id},'{$_POST['header']}',{$_POST['fieldid']});}catch(e){}";
					die('window.location.href="edit.php?ds_id='.$_POST['ds_id'].'&id='.$doc->id.'";</script>');
				} else {
					require_once(LIBDIR.'/hybrid/dshybridfactory._wc');
					$dsf = new DSHybridFactory();
					$dsf->removeDocuments($doc->id);
					die('<script>try{window.close();}catch(e){}</script>');
				}
			}
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}';
			echo 'window.location.href="edit.php?ds_id='.$_POST['ds_id'].'&id='.$doc->id.'";</script>';
		} else
			echo 'Failed to create document';
	break;

	case 'edit_hybrid':
		require_once(LIBDIR.'/hybrid/hybriddocument._wc');
		assert(isset($_POST['header']) && trim($_POST['header']) !='' && @$_POST['ds_id'] > 0);
		$ds = &$dsm->load($_POST['ds_id']);

		$doc = $ds->getEmptyDocument();
		$doc->readValues($_POST);
		$doc->readFiles($_FILES);
		$success = $ds->updateDocument($doc);
		if($success)
			header('Location: '.$_SERVER['HTTP_REFERER']);
	break;

	case 'delete_hybrid':
		if(isset($_POST['ds_id']) && isset($_POST['ids'])) {
			require_once(LIBDIR.'/hybrid/dshybridfactory._wc');
			$ds = new DSHybridFactory();
			$ds->removeDocuments(&$_POST['ids']);
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'remove_ds':
		if(isset($_POST['ds_id']))
		{
			require_once(LIBDIR.'/hybrid/hybriddocument._wc');
			require_once(LIBDIR.'/hybrid/dshybrid._wc');
			require_once(LIBDIR.'/hybrid/dshybridfactory._wc');
			$dsf=new DSHybridFactory();
			$dsf->remove($_POST['ds_id']);
		}
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/data/">');
	break;

	case 'move_documents':
		assert(isset($_POST['ds_id']) && isset($_POST['dest_ds_id']) && isset($_POST['ids']));
		require_once(LIBDIR.'/hybrid/dshybrid._wc');
		require_once(LIBDIR.'/hybrid/dshybridfactory._wc');
		$dsf = & new DSHybridFactory();
		$dsf->castDocuments($_POST['ids'], $_POST['ds_id'], $_POST['dest_ds_id']);
		$failed = headers_sent();
		if(!$failed) {
			die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
		} else
			echo '<p><a href="'.$_SERVER['HTTP_REFERER'].'">Back</a>';
	break;

	case 'publish_documents':
		if(isset($_POST['ds_id']) && isset($_POST['ids']))
		{
			require_once(LIBDIR.'/hybrid/dshybridfactory._wc');
			$dsf=new DSHybridFactory();
			$dsf->publishDocuments($_POST['ids']);
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;

	case 'unpublish_documents':
		if(isset($_POST['ds_id']) && isset($_POST['ids']))
		{
			require_once(LIBDIR.'/hybrid/dshybridfactory._wc');
			$dsf=new DSHybridFactory();
			$dsf->unpublishDocuments($_POST['ids']);
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	default:
	break;
}
?>
