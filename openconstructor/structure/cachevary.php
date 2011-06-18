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
 * $Id: cachevary.php,v 1.6 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/structure._wc');
	require_once(LIBDIR.'/site/pagereader._wc');
	
	$pr = PageReader::getInstance();
	$page = $pr->getPage(@$_GET['id']);
	assert($page != null);
	require_once(LIBDIR.'/site/cachevarysuggest._wc');
	require_once(LIBDIR.'/objmanager._wc');
	$objIds = array_keys($page->getObjects());
	$vary = array();
	foreach($objIds as $id)
		if($page->objects[$id]['block']) {
			$obj = ObjManager::load($id);
			$vary = array_merge($vary, CacheVarySuggest::suggest($obj));
		}
	asort($vary);
	$vary = array_unique($vary);
?>
<html>
<head>
<title><?=WC.' | '.H_CACHE_VARY_SUGGEST?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<link href="../<?=SKIN?>.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
	returnValue = [];
	function returnSelected() {
		var inp = document.getElementById("div.ch").getElementsByTagName("input");
		for(var i = 0, j = 0; i < inp.length; i++)
			if(inp[i].checked)
				returnValue[j++] = inp[i].value;
		window.close();
	}
</script>
</head>
<body style="border-style:groove; border-width: 2px;padding:0 20 20">
<br>
<h3><?=H_CACHE_VARY_SUGGEST?></h3>
<div style="font-size: 90%;"><?=SUGGESTTIONS_MAY_BE_INVALID?></div>
<form name="f">
	<fieldset style="padding:10"><legend><?=H_SUGGESTIONS?></legend>
	<div style="padding: 10px; font-size: 110%;" id="div.ch">
	<?php
		if(sizeof($vary)) {
			echo '<div style=" font-family: monospace;">';
			foreach($vary as $part)
				echo sprintf('<input type="checkbox" value="%1$s" checked> %1$s<br>', $part);
			echo '</div>';
		} else {
			echo H_NO_SUGGESTIONS;
		}
	?>
	</div>
	</fieldset><br>
	<div align="right"><input type="button" onclick="returnSelected()" value="<?=BTN_INSERT?>" name="create"<?=sizeof($vary) ? '':' DISABLED'?>> <input type="button" value="<?=BTN_CANCEL?>" onclick="window.close()"></div>
</form>
</body>
</html>