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
 * $Id: edit.php,v 1.20 2007/03/05 07:15:35 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/templates._wc');

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	require_once(LIBDIR.'/templates/wctemplates._wc');
	$tpls = new WCTemplates();
	if($_GET['id'] == 'new'){
		$type = @$_GET['type'];
		$tpl = new WCTemplate($type,'');
		assert($tpls->objectSupported($type));
		if(isset($_GET['header']))
			$tpl->name = $_GET['header'];
		if(file_exists(LIBDIR.'/tpl/'.$type.'.tpl'))
			$defTpl = implode('', file(LIBDIR.'/tpl/'.$type.'.tpl'));
	} else {
		$tpl = &$tpls->load(@$_GET['id']);
		assert($tpl != null);
		$tpls->parse($tpl);
	}
	require_once(LIBDIR.'/syntax/syntaxhighlighter._wc');
	$save = $tpl->id ? WCS::decide($tpl, 'edittpl') : System::decide('tpls.ds'.@$_GET['dstype']);

	$smartybackend->assign_by_ref("tpl", $tpl);
	$WCS = new WCS();
	$smartybackend->assign_by_ref("WCS", $WCS);
	$SyntaxHighlighter = new SyntaxHighlighter();
	$smartybackend->assign_by_ref("SyntaxHighlighter", $SyntaxHighlighter);

    $tplVars = array('id' => $_GET['id'], 'dstype' => $_GET['dstype'], 'select' => @$_GET['select'], 'caret' => intval(@$_GET['caret']));
    $smartybackend->assign("tplVars", $tplVars);

	$smartybackend->assign("defTpl", @$defTpl);
	$smartybackend->assign("save", $save);

    $errorText = array(
		E_USER_NOTICE => 'Notice',
		E_USER_WARNING => 'Warning',
		E_USER_ERROR => 'Fatal error',
	);
	$errorText[E_NOTICE] = $errorText[E_USER_NOTICE];
	$smartybackend->assign("errorText", $errorText);

	$smartybackend->display('templates/edit.tpl');
?>