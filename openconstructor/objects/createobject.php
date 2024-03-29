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
 * $Id: createobject.php,v 1.15 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/objects._wc');
	require_once(LIBDIR.'/objmanager._wc');
	require_once(LIBDIR.'/dsmanager._wc');

	if(@sizeof($_POST)) {
		assert(trim(@$_POST['name']) != '' && trim(@$_POST['description']) != '');

		$dsType = $_POST['ds_type'];
		$class = $_POST['obj_type'];

		assert(strpos($_POST['ds_type'], '/') === false && strpos($_POST['obj_type'], '/') === false);
		if ($dsType == 'hybrid')
			$classFile = LIBDIR.'/'.$dsType.'/view/'.$class.'._wc';
		else
			$classFile = LIBDIR.'/'.$dsType.'/'.$class.'._wc';
		assert(file_exists($classFile));
		require_once($classFile);
		assert(class_exists($class));
		$obj = new $class();
		$result = null;
		switch($class)
		{
			//htmltext
			case 'htmltextbody':
			case 'htmltexthl':
			case 'htmltexthlintro':
			//publication
			case 'publicationhl':
			case 'publicationhlintro':
			case 'publicationbody':
			case 'publicationpager':
			case 'publicationmainintro':
			case 'publicationlist':
			case 'publicationlistintro':
			//event
			case 'eventcalendar':
			case 'eventhl':
			case 'eventhlintro':
			case 'eventbody':
			case 'eventpager':
			//textpool
			case 'textrandom':
			//guestbook
			case 'gbmsgbody':
			case 'gbmsghl':
			case 'gbpager':
			//phpsource
			case 'phpinclude':
			case 'phpcallback':
			//file
			case 'filehl':
			case 'filepager':
			//article
			case 'articlebody':
			case 'articlebodypager':
			case 'articlehl':
			case 'articlehlintro':
			case 'articlepager':
			case 'articlerelated':
			//hybrid
			case 'hybridtree':
			case 'hybridhl':
			case 'hybridbar':
			case 'hybridpager':
			case 'hybridbody':
			case 'hybridbodyedit':
			//search
			case 'searchdss':
			//rating
			case 'ratingrate':
			case 'ratingratelogic':
				$obj->ds_id = (int) @$_POST['ds_id'];
				$obj->name = $_POST['name'];
				$obj->description = $_POST['description'];
				$result = ObjManager::create($obj);
			break;


			//gallery
			case 'galleryhl':
			case 'galleryimage':
			case 'gallerypager':
			case 'galleryimgpager':
				if(@$_POST['ds_id'])
					$obj->ds_id = $_POST['ds_id'];
				else {
					$obj->dynamic_ds=true;
					$obj->ds_id = 'gallery_id';
				}
				$obj->name = $_POST['name'];
				$obj->description = $_POST['description'];
				$result = ObjManager::create($obj);
				if($result)
					die("<script>window.opener.location.reload();window.location.href='$dsType/$class.php?id=$result';</script>Successfully created.");
			break;


			//guestbook
			case 'gballmessages':
			case 'gbaddmsglogic':
			case 'gblist':
				$obj->ds_id = @$_POST['ds_id'];
				$obj->name = $_POST['name'];
				$obj->description = $_POST['description'];
				$obj->defaultGB = @$_POST['ds_id'];
				$result = ObjManager::create($obj);
				if($result)
					die("<script>window.opener.location.reload();window.location.href='$dsType/$class.php?id=$result';</script>Successfully created.");
			break;


			//miscellany
			case 'miscfetchtpl':
			case 'misccrumbs':
			case 'miscinjector':
			case 'miscsendmail':
			//users
			case 'usersauthorize':
			case 'userslogout':
			//search
			case 'searchdsspager':
				$obj->ds_id = 0;
				$obj->name = $_POST['name'];
				$obj->description = $_POST['description'];
				$result = ObjManager::create($obj);
				if($result)
					die("<script>window.opener.location.reload();window.location.href='$dsType/$class.php?id=$result';</script>Successfully created.");
			break;


			default:
			die();
			break;
		}
		if($result) {
			?>
				<script>
					try {
						window.opener.location.reload();
					} catch(e) {}
					var href = "<?=$dsType.'/'.$class.'.php?id='.$result?>";
					document.write("<pre>Opening " + href + " ...</pre>");
					window.location.href = href;
				</script>
			<?php
			die();
		}
	} else
		if(!@$_GET['ds_type']||!@$_GET['obj_type']) die();

	require_once(LIBDIR.'/smarty/ocmsmartybackend._wc');
	$smartybackend = & new OcmSmartyBackend();
	$smartybackend->caching = false;

	$_dsm=new DSManager();
	$ds = $_dsm->getAll($_GET['ds_type']);
	$smartybackend->assign("ds", $ds);
	$dis = System::decide('objects.ds'.$_GET['ds_type']) && is_array($ds) ? 'false' : 'true';
	$smartybackend->assign("dis", $dis);
	$smartybackend->display('objects/createobject.tpl');
?>