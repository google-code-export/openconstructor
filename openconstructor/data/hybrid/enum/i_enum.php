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
 * $Id: i_enum.php,v 1.5 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/enum/wcenumfactory._wc');
	
	$ef = WCEnumFactory::getInstance();
switch(@$_POST['action'])
{
	case 'create_enum':
		$result = $ef->create(@$_POST['header']);
		if($result)
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.location.href="editenum.php?id='.$result.'";</script>Succesfully created!';
	break;
	
	case 'edit_enum':
		$id = (int) @$_POST['id'];
		$enum = $ef->load($id);
		assert($enum != null);
		$enum->header = @$_POST['header'];
		$ef->update($enum);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'create_enumvalue':
		$enum = $ef->load((int) @$_POST['enum']);
		assert($enum != null);
		$result = $enum->addValue(@$_POST['key'], @$_POST['header']);
		if($result)
			echo '<script>try{window.opener.location.href=window.opener.location.href;}catch(RuntimeException){}window.location.href="editvalue.php?enum='.$enum->id.'&id='.$result.'";</script>Succesfully created!';
	break;
	
	case 'edit_enumvalue':
		$enum = $ef->load((int) @$_POST['enum']);
		assert($enum != null);
		$id = (int) @$_POST['id'];
		$enum->updateValue($id, @$_POST['key'], @$_POST['header']);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'remove_enum':
		$enum = $ef->load((int) @$_POST['enum']);
		assert($enum != null);
		$ef->remove($enum);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'remove_enumvalue':
		$enum = $ef->load((int) @$_POST['enum']);
		assert($enum != null && is_array(@$_POST['ids']));
		$enum->removeValue($_POST['ids']);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
}
?>
