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
 * $Id: i_rating.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');

	if (isset($_POST['action'])) {
		$obj = &ObjManager::load(@$_POST['obj_id']);
		assert($obj != null);
		$obj->name = (string) @$_POST['name'];
		$obj->description = (string) @$_POST['description'];
		switch(@$_POST['action'])
		{
			case 'edit_ratingrate':
				$obj->header = (string) @$_POST['header'];
				$obj->ds_id = (int) @$_POST['ds_id'];
				if(@$_POST['idKey'])
					$obj->idKey = $_POST['idKey'];
				$obj->no404 = @$_POST['no404'] == 'true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;

			case 'edit_ratingratelogic':
				$obj->header = (string) @$_POST['header'];
				$obj->ds_id = (int) @$_POST['ds_id'];
				if(@$_POST['idKey'])
					$obj->idKey = $_POST['idKey'];
				if(@$_POST['ratingKey'])
					$obj->ratingKey = $_POST['ratingKey'];
				if(@$_POST['commentKey'])
					$obj->commentKey = $_POST['commentKey'];
				$obj->ignoreAuths = @$_POST['ignoreAuths'] == 'true';

				$obj->cId = @$_POST['cId'];
				$obj->cVal = $obj->cId ? @$_POST['cVal'] : $obj->cVal;
				$obj->closeSess = @$_POST['closeSess'] == 'true';

				$obj->notifyEmail = @$_POST['notifyEmail'] ? $_POST['notifyEmail'] : null;
				if($obj->notifyEmail)
					$obj->mailSubject = (string) @$_POST['mailSubject'];

				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;

			default:
				assert(true == false);
			break;
		}
	}
?>
