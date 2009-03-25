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
 * $Id: i_catalog.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
switch(@$_POST['action'])
{
	case 'create_node':
		require_once(LIBDIR.'/tree/sqltree._wc');
		$sqltree = new SqlTree();
		$result = $sqltree->addNode(@$_POST['parent'], @$_POST['key'], @$_POST['header']);
		if($result)
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.location.href="editnode.php?id='.$result.'";//"addnode.php?in='.$_POST['parent'].'";</script>Succesfully created!';
	break;
	
	case 'edit_node':
		require_once(LIBDIR.'/tree/sqltree._wc');
		$sqltree = new SqlTree();
		$node = $sqltree->getNode(@$_POST['id']);
		assert($node != null);
		$result = $sqltree->updateNode($node['id'], $node['name'], @$_POST['header']);
		if($result)
			die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');

	break;
	
	case 'remove_node':
		require_once(LIBDIR.'/tree/sqltree._wc');
		$sqltree = new SqlTree();
		$result = $sqltree->removeNode(@$_POST['node_id']);
		if($result)
			die('<meta http-equiv="Refresh" content="0; URL=/openconstructor/catalog/index.php/trees/"/>');
	break;
	
	case 'move_up':
		require_once(LIBDIR.'/tree/sqltree._wc');
		$sqltree = new SqlTree();
		$result = $sqltree->moveNodeUp(@$_POST['node_id']);
		die('<meta http-equiv="Refresh" content="0; URL=/openconstructor/catalog/index.php/trees/"/>');
	break;
	
	case 'move_down':
		require_once(LIBDIR.'/tree/sqltree._wc');
		$sqltree = new SqlTree();
		$result = $sqltree->moveNodeDown(@$_POST['node_id']);
		die('<meta http-equiv="Refresh" content="0; URL=/openconstructor/catalog/index.php/trees/"/>');
	break;
	
	case 'view_detail':
		foreach((array) @$_COOKIE['vd'] as $key => $val){
			if(!array_key_exists($key, (array) @$_POST['vdetail']))
				setcookie('vd['.$key.']', '', time() - 3600, WCHOME.'/catalog/');
		}
		foreach($_POST['vdetail'] as $key => $val)
			setcookie('vd['.$key.']', $key, 0, WCHOME.'/catalog/');
		setcookie('pagesize', $_POST['pagesize'], 0, WCHOME.'/catalog/');
		setcookie('vd[_touched]', '_touched', 0, WCHOME.'/catalog/');
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	default:
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
}
?>
