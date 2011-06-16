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
 * $Id: i_objects.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/objmanager._wc');
	
switch(@$_POST['action']) {
	case 'edit_uses':
		$obj = ObjManager::load(@$_POST['obj_id']);
		assert($obj != null);
		require_once(LIBDIR.'/site/pagefactory._wc');
		$uses = array();
		foreach((array) @$_POST['page'] as $id)
			$uses[$id] = (string) @$_POST['block'][$id];
		$pf = PageFactory::getInstance();
		$pf->updateObjectUses($obj->obj_id, $uses);
		header('Location: '.$_SERVER['HTTP_REFERER']);
		die();
	break;

	case 'delete_obj':
		$_obj = new ObjManager();
		$_obj->remove(implode(',',@$_POST['ids']));
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	default:
	break;
}
?>
