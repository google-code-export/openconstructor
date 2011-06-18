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
 * $Id: move_doc.php,v 1.8 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	System::request('data');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/data._wc');
	assert(@$_GET['ds_id'] > 0 && trim(@$_GET['ds_type']) != '');
	require_once(LIBDIR.'/wcobject._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	
	$_dsm = new DSManager();
	$from = $_dsm->load((int) $_GET['ds_id']);
	assert($from != null);
	$ds = $_dsm->getAll($from->ds_type);
?>
<html>
<head>
<title><?=MOVE_DOCUMENTS?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	window.returnValue=false;
	function sbmt(){
		window.returnValue=f.dest_ds.options(f.dest_ds.selectedIndex).value;
		window.close();
	}
</script>
</head>
<body style="border-style:groove;border-width:2px;padding:5 10;">
<br>
<h3><?=MOVE_DOCUMENTS?></h3>
<form name="f" onsubmit="return false">
<fieldset style="padding:10"><legend><?=PROPS_DATASOURCES?></legend>
	<table style="margin:5 0" cellspacing="5">
		<tr>
			<td nowrap><?=CURRENT_DS?>:</td>
			<td><b><?=$from->name?></b></td>
		</tr>
		<tr>
			<td nowrap><?=DESTINATION_DS?>:</td>
			<td><select size="1" name="dest_ds">
			<?php
				foreach($ds as $v)
					if($v['id'] != $from->ds_id)
						echo '<OPTION VALUE="'.$v['id'].'">'.
							$v['name'];
			?>	
			</select></td>
		</tr>
	</table>
</fieldset><br>
<div align="right"><input type="button"<?=(WCS::decide($from, 'removedoc') || WCS::ownerAllowed($from, 'removedoc')) && sizeof($ds) > 1 ? '' : ' disabled'?> name="move" value="<?=BTN_MOVE_DOCUMENTS?>" onclick="sbmt()"> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
</body>
</html>