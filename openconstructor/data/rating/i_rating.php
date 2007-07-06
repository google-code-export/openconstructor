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
 * $Id: i_rating.php,v 1.7 2007/03/02 10:06:40 sanjar Exp $
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
WCS::requireAuthentication();
require_once(LIBDIR.'/wcdatasource._wc');

switch(@$_POST['action'])
{
	case 'edit_rating':
		assert(@$_POST['id'] > 0 && @$_POST['ds_id'] > 0);
		require_once(LIBDIR.'/rating/dsrating._wc');
		$_ds = & new DSRating();
		assert($_ds->load($_POST['ds_id']) == true);
		$result = $_ds->update($_POST['id'], @$_POST['rating'], @$_POST['fakeType']);
		if($result)
			header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'delete_vote':
		assert(@$_POST['id'] > 0 && @$_POST['ds_id'] > 0);
		require_once(LIBDIR.'/rating/dsrating._wc');
		$_ds = & new DSRating();
		assert($_ds->load($_POST['ds_id']) == true);
		$result = $_ds->removeVotes($_POST['id'], @$_POST['ids']);
		if($result)
			header('Location: http://'.WC_SITE_HOST.WCHOME."/data/rating/edit.php?ds_id={$_ds->ds_id}&id={$_POST['id']}");
	break;
	
	case 'activate_vote':
	case 'deactivate_vote':
		assert(@$_POST['id'] > 0 && @$_POST['ds_id'] > 0);
		require_once(LIBDIR.'/rating/dsrating._wc');
		$_ds = & new DSRating();
		assert($_ds->load($_POST['ds_id']) == true);
		$result = $_ds->setVotesState($_POST['id'], @$_POST['ids'], $_POST['action'] == 'activate_vote');
		if($result)
			header('Location: http://'.WC_SITE_HOST.WCHOME."/data/rating/edit.php?ds_id={$_ds->ds_id}&id={$_POST['id']}");
	break;
	
	case 'edit_vote':
		assert(@$_POST['ratingId'] > 0 && @$_POST['ds_id'] > 0 && @$_POST['userId'] > 0);
		require_once(LIBDIR.'/rating/dsrating._wc');
		$_ds = & new DSRating();
		assert($_ds->load($_POST['ds_id']) == true);
		$result = $_ds->updateVote($_POST['ratingId'], $_POST['userId'], @$_POST['active'] == 'true', @$_POST['comment'], @$_POST['rating'], @$_POST['votes'], @$_POST['date']);
		if($result)
			header('Location: '.$_SERVER['HTTP_REFERER']);
	break;
	
	case 'delete_rating':
		if(isset($_POST['ds_id'])) {
			require_once(LIBDIR.'/rating/dsrating._wc');
			$_ds = & new DSRating();
			$_ds->load($_POST['ds_id']);
			$_ds->delete(implode(',', @$_POST['ids']));
		}
//		header('Location: '.$_SERVER['HTTP_REFERER']);
		die('<meta http-equiv="Refresh" content="0; URL='.$_SERVER['HTTP_REFERER'].'">');
	break;
	
	case 'remove_ds':
		if(isset($_POST['ds_id'])) {
			require_once(LIBDIR.'/rating/dsrating._wc');
			$_ds = & new DSRating();
			$_ds->load($_POST['ds_id']);
			$_ds->remove();
		}
		die('<meta http-equiv="Refresh" content="0; URL=http://'.$_host.WCHOME.'/data/">');
	break;
	
	default:
	break;
}
?>
