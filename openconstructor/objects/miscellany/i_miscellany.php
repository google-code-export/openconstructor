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
 * $Id: i_miscellany.php,v 1.14 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	
	if (isset($_POST['action'])) {
		$obj = &ObjManager::load(@$_POST['obj_id']);
		assert($obj != null);
		$obj->name=@$_POST['name'];
		$obj->description=@$_POST['description'];
		switch(@$_POST['action'])
		{
			case 'edit_misccrumbs':
				unset($obj->urifilter, $obj->current, $obj->separator);
				$obj->header=@$_POST['header'];
				$obj->exclude = (array) @$_POST['exclude'];
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_miscinjector':
				$obj->jobs = array();
				for($i = 1, $l = sizeof($_POST['type']); $i < $l; $i++)
					$obj->jobs[$i - 1] = array(
						$_POST['src'][$i] + $_POST['type'][$i],
						$_POST['srcId'][$i],
						$_POST['field'][$i],
						$_POST['param'][$i]
					);
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_miscsendmail':
				$obj->subject = @$_POST['subject'];
				$obj->from = @$_POST['from'];
				$obj->to = @$_POST['to'];
				$obj->cc = @$_POST['cc'];
				$obj->bcc = @$_POST['bcc'];
				$obj->isHtml = @$_POST['isHtml'] == 'true';
				if($obj->isHtml)
					$obj->allowedTags = @$_POST['allowedTags'];
				$fields = array();
				for($i = 1, $l = sizeof($_POST['src']); $i < $l; $i++)
					$fields[$i - 1] = array(
						$_POST['src'][$i] + $_POST['type'][$i],
						$_POST['srcId'][$i],
						$_POST['validator'][$i],
						$_POST['error'][$i]
					);
				$obj->fields = $fields;
				$obj->cId = @$_POST['cId'];
				$obj->captcha = $obj->cId ? @$_POST['captcha'] : null;
				$obj->closeSess = @$_POST['closeSess'] == 'true';
				$obj->files = array();
				foreach($_POST['attachSrc'] as $i => $j)
					if($i > 0)
						$obj->files[] = array(
							$_POST['attachSrc'][$i] + (isset($_POST['attachType'][$i]) ? $_POST['attachType'][$i] : FTYPE_FILE),
							$_POST['attachSrcId'][$i],
							@$_POST['ext'][$i],
							@$_POST['size'][$i],
							@$_POST['attachIsReq'][$i] == 'true',
							@$_POST['attachError'][$i]
						);
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_miscfetchtpl':
				$obj->header = (string) @$_POST['header'];
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			default:
			break;
		}
	}
?>
