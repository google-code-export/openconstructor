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
 * $Id: i_hybrid.php,v 1.16 2007/03/02 10:06:41 sanjar Exp $
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
			case 'edit_hybridtree':
				$obj->header=@$_POST['header'];
				$obj->ds_id = (int) @$_POST['ds_id'];
				$obj->deepness = (int) @$_POST['deepness'] > 0 ? (int) $_POST['deepness'] : $obj->deepness;
				$obj->countDocs = @$_POST['countDocs'] == 'true';
				$obj->dsIdKey = @$_POST['dsIdKey'];
				$obj->onlySub = @$_POST['onlySub'] == 'true';
				$obj->srvUri = @$_POST['srvUri'] ? $_POST['srvUri'] : $obj->srvUri;
				$obj->nodeId = @$_POST['nodeId'] ? $_POST['nodeId'] : $obj->nodeId;
				$obj->docFields = (array) @$_POST['field'];
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_hybridhl':
				require_once(LIBDIR.'/hybrid/fields/fieldfactory._wc');
				$obj->header=@$_POST['header'];
				$obj->ds_id = (int) $_POST['ds_id'];
				$obj->docFields = array_values((array) @$_POST['field']);
				$obj->docOrder = array();
				foreach(array_values(explode(',', @$_POST['docOrder'])) as $i => $id) {
					$obj->docOrder[$i] = array('id' => $id);
					if(is_numeric($id) && isset($_POST['rRange'][abs($id)]))
						$obj->docOrder[$i]['range'] = (string) $_POST['rRange'][abs($id)];
				}
				$obj->listOffset = (int) @$_POST['listOffset'];
				$obj->listSize = (int) @$_POST['listSize'];
				$obj->dsIdKey = @$_POST['dsIdKey'];
				$obj->onlySub = @$_POST['onlySub'] == 'true';
				$obj->srvUri = @$_POST['srvUri'] ? $_POST['srvUri'] : $obj->srvUri;
				$obj->docId = @$_POST['docId'] ? str_replace("\r\n", "\n", $_POST['docId']) : $obj->docId;
				$obj->nodeId = @$_POST['nodeId'];
				$obj->nodeType = (int) @$_POST['nodeType'];
				$f = $obj->ds_id ? FieldFactory::getRelatedFields($obj->ds_id) : array();
				for($fields = array(), $i = 0, $l = sizeof($f); $i < $l; $i++)
					$fields[substr($f[$i]->key, 2)] = &$f[$i];
				$obj->docFilter = array();
				for($i = 1, $l = sizeof(@$_POST['value']); $i < $l; $i++) {
					$obj->docFilter[$i - 1] = array(
						($_POST['src'][$i] + $_POST['type'][$i]) * (@$_POST['invert'][$i] == 'true' ? -1 : 1),
						$_POST['value'][$i],
						isset($fields[$_POST['filter'][$i]]) ? $fields[$_POST['filter'][$i]]->id : $_POST['filter'][$i]
					);
				}
				$obj->keywordKey = @$_POST['keywordKey'];
				$obj->sortByRank = @$_POST['sortByRank'] == 'true';
				$obj->noResTpl = (int) @$_POST['noResTpl'];
				$obj->no404=@$_POST['no404']=='true';
				$obj->_pma = @$_POST['_pma'] == 'true';
				if(isset($_POST['doc_ids']))
					$obj->ids = implode(',', (array) @$_POST['doc_ids']);
				else
					unset($obj->ids);
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_hybridpager':
				$obj->header=@$_POST['header'];
				$obj->pageNumberKey = @$_POST['pageNumberKey'];
				$obj->pagerSize = @$_POST['pagersize'] > 0 ? $_POST['pagersize'] : 10;
				$obj->listSizeKey = @$_POST['listSizeKey'];
				$obj->slave = @$_POST['master_obj'];
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_hybridbody':
				$obj->header=@$_POST['header'];
				$obj->ds_id = (int) @$_POST['ds_id'];
				$obj->docFields = array_values((array) @$_POST['field']);
				$obj->dsIdKey = @$_POST['dsIdKey'];
				$obj->onlySub = @$_POST['onlySub'] == 'true';
				$obj->idField = (int) @$_POST['idField'];
				$obj->docId = @$_POST['docId'] ? $_POST['docId'] : $obj->docId;
				$obj->browseUri = @$_POST['browseUri'];
				$obj->nodeId = @$_POST['nodeId'];
				$obj->no404=@$_POST['no404']=='true';
				$obj->_pma = @$_POST['_pma'] == 'true';
				$obj->docFilter = array();
				for($i = 1, $l = sizeof(@$_POST['value']); $i < $l; $i++) {
					$obj->docFilter[$i - 1] = array(
						($_POST['src'][$i] + $_POST['type'][$i]) * (@$_POST['invert'][$i] == 'true' ? -1 : 1),
						$_POST['value'][$i],
						isset($fields[$_POST['filter'][$i]]) ? $fields[$_POST['filter'][$i]]->id : $_POST['filter'][$i]
					);
				}
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_hybridbodyedit':
				$obj->header=@$_POST['header'];
				$obj->ds_id = (int) @$_POST['ds_id'];
				$obj->docFields = array_values((array) @$_POST['field']);
				$obj->dsIdKey = @$_POST['dsIdKey'];
				$obj->docId = @$_POST['docId'] ? $_POST['docId'] : $obj->docId;
				$obj->no404=@$_POST['no404']=='true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			case 'edit_hybridbar':
				$obj->header=@$_POST['header'];
				$obj->ds_id = (int) $_POST['ds_id'];
				$obj->docFields = array_values((array) @$_POST['field']);
				$obj->docOrder = array();
				foreach(array_values(explode(',', @$_POST['docOrder'])) as $i => $id) {
					$obj->docOrder[$i] = array('id' => $id);
					if(isset($_POST['rRange'][abs($id)]))
						$obj->docOrder[$i]['range'] = (string) $_POST['rRange'][abs($id)];
				}
 				$obj->fetchedDocs = implode(',', (array) @$_POST['fetchedDocs']);
				$obj->rotate = @$_POST['rotate']?true:false;
				$obj->dsIdKey = @$_POST['dsIdKey'];
				$obj->onlySub = @$_POST['onlySub'] == 'true';
				$obj->srvUri = @$_POST['srvUri'] ? $_POST['srvUri'] : $obj->srvUri;
				$obj->docId = @$_POST['docId'] ? str_replace("\r\n", "\n", $_POST['docId']) : $obj->docId;
				$obj->_pma = @$_POST['_pma'] == 'true';
				include('../apply_tpl._wc');
				ObjManager::save($obj);
				header('Location: '.$_SERVER['HTTP_REFERER']);
			break;
			
			default:
			break;
		}
	}
?>
