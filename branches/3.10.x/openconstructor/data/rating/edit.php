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
 * $Id: edit.php,v 1.9 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	assert(@$_GET['ds_id'] > 0 && @$_GET['id'] > 0);
	require_once($_SERVER['DOCUMENT_ROOT'].WCHOME.'/include/toolbar._wc');
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = &$dsm->load($_GET['ds_id']); 
	$_doc = $_ds->get_record($_GET['id']);
	assert($_doc !== null);
	$sDoc = $_ds->wrapDocument($_doc);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?=$_ds->name?> | <?=htmlspecialchars($_doc['header'], ENT_COMPAT, 'UTF-8').' | '.EDIT_RATING?></title>
	<link href="<?=WCHOME.'/'.SKIN?>.css" type=text/css rel=stylesheet>
	<link href="../../lib/js/api.css" type=text/css rel=stylesheet>
	<link href="editor.css" type=text/css rel=stylesheet>
	<script src="../../common.js"></script>
	<script src="../../lib/js/base.js"></script>
	<script src="../../lib/js/widgets.js"></script>
	<script>
		var checkedDocs = 0,
			wchome = "<?=WCHOME?>",
			skin = "<?=SKIN?>",
			REMOVE_SELECTED_VOTES_Q = "<?=REMOVE_SELECTED_VOTES_Q?>",
			ACTIVATE_SELECTED_VOTES_Q = "<?=ACTIVATE_SELECTED_VOTES_Q?>",
			DEACTIVATE_SELECTED_VOTES_Q = "<?=DEACTIVATE_SELECTED_VOTES_Q?>",
			minRating = <?=(int) $_ds->minRating?>,
			maxRating = <?=(int) $_ds->maxRating?>,
			RATING_MUST_BE_VALID_W = "<?=addslashes(sprintf(RATING_MUST_BE_VALID_W_PATTERN, $_ds->minRating, $_ds->maxRating))?>"
		;
		window.onload = function() {
			disableToolbarButtons();
			docsHref = document.getElementById('iframe.votes').src;
			new CalendarWidget(document.getElementById("txt.from"), document.getElementById("btn.from"), 2000, 2010, "calendar", "<?=LANGUAGE?>");
			new CalendarWidget(document.getElementById("txt.to"), document.getElementById("btn.to"), 2000, 2010, "calendar", "<?=LANGUAGE?>");
		}
	</script>
	<script src="editor.js"></script>
</head>
<body style="border:groove;border-width:2;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
	<tr>
		<td colspan="4">
		<?php if($_doc['id']):?>
			<div class="head"><?=htmlspecialchars($_doc['header'], ENT_COMPAT, 'UTF-8')?></div>
		<?php endif;?>
		</td>
	</tr>
	<tr id="ratings">
		<td id="avgRating" nowrap="yes">
			<div>
				<span style="font-size: 150%;"><?=H_AVERAGE_RATING?>: <b><?=$_doc['rating']?></b></span>
				<span style="font-size: 90%; display: inline-block; margin-bottom: 2px; color:#999;">[<?="{$_ds->minRating}&mdash;{$_ds->maxRating}"?>]</span>
				<a id="a.setRating" href="javascript: // Set Rating" onclick="toggleSetRatingCtrls(true)" style="font-size: 90%; display: inline-block; margin: 0 0 2px 5px;"><?=H_SET_RATING_MANUALLY?></a>
			</div>
			<div><?=H_TOTAL_VOTES?>: <b><?=$_doc['raters']?></b></div>
		</td>
		<td class="detRating">
			<div><?=H_REAL_AVERAGE_RATING?>: <b><?=round($_doc['realRating'], 2)?></b></div>
			<div><?=H_REAL_VOTES?>: <b><?=$_doc['realVotes']?></b></div>
		</td>
		<td class="detRating">
			<div><?=H_FAKE_AVERAGE_RATING?>: <b><?=round($_doc['fakeRating'], 2)?></b></div>
			<div><?=H_FAKE_VOTES?>: <b><?=$_doc['fakeVotes']?></b> <span style="color:#999;">[<?=$_doc['fakeRaters']?>]</span></div>
		</td>
	</tr>
	<tr id="tr.setRating" style="display: none;" onkeypress="if(event.keyCode == 27) toggleSetRatingCtrls(false);">
		<td colspan="3" style="background: #fff; padding: 5px; border-top: solid 1px #999;">
			<form method="POST" style="margin:0;padding:0" enctype="multipart/form-data" name="f" action="i_rating.php" onsubmit="return setRating()">
				<input type="hidden" name="action" value="edit_rating">
				<input type="hidden" name="id" value="<?=$_doc['id']?>">
				<input type="hidden" name="ds_id" value="<?=$_ds->ds_id?>">
				<table width="100%" height="100%" id="setRating">
					<tr>
						<td nowrap="yes" style="padding-left: 10px;"><?=H_SET_RATING?>:</td>
						<td><input type="text" size="5" id="txt.rating" name="rating"></td>
						<td nowrap="yes" style="padding-left: 10px;"><?=H_SET_RATING_STRATEGY?>:</td>
						<td width="100%" style="font-size: 90%;">
							<div>
								<input type="radio" name="fakeType" style="vertical-align:middle" checked id="radio.addUser" value="<?=DSR_FAKING_ADDUSER?>">
								<label for="radio.addUser"><?=H_SRS_ADDUSER?></label>
							</div>
							<div>
								<input type="radio" name="fakeType" style="vertical-align:middle" id="radio.distribute" value="<?=DSR_FAKING_DISTRIBUTE?>" <?=0&&$_doc['fakeVotes'] > 0 ? '' : 'disabled'?>>
								<label for="radio.distribute"><?=H_SRS_DISTRIBUTE?></label>
							</div>
						</td>
						<td style="padding-right: 10px;"><input type="submit" value="<?=BTN_SET_RATING?>" <?=WCS::decide($_ds, 'editdoc') || WCS::decide($_doc, 'editdoc') ? '' : 'disabled'?>></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td id="tdToolbar" colspan="3">
		<?php
			toolbar(array(
				BTN_ACTIVATE_VOTE=>array('pic' => 'activate', 'action' => 'setVotesState(true)'),
				BTN_DEACTIVATE_VOTE=>array('pic' => 'deactivate', 'action' => 'setVotesState(false)'),
				'separator_',
				BTN_REMOVE_VOTE=>array('pic' => 'remove', 'action' => 'removeVotes()')
			));
		?>
		</td>
	</tr>
	<tr height="100%" valign="top">
		<td colspan="3">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
				<tr>
					<td><iframe style="width: 100%; height: 100%;" id="iframe.votes" src="./votes.php?ds_id=<?=$_ds->ds_id?>&amp;id=<?=$_doc['id']?>"></iframe></td>
					<td width="5px" style="background: #ebebeb;" onkeypress="if(event.keyCode == 27) toggleRightPanel(false);">
						<a href="javascript: toggleRightPanel();" class="pnlleft" id="a.rightPanel"><img src="../../i/1x1.gif" width="8" height="50" border="0"></a>
					</td>
					<td width="240px" valign="top" id="rightPanel" style="display: none; padding: 10px;" onkeypress="if(event.keyCode == 27) toggleRightPanel(false);">
						<fieldset id="fs.search">
							<legend><?=H_SEARCH_VOTE?></legend>
							<div style="padding: 10px; text-align: right;">
								<input type="text" id="txt.search" style="width: 100%;" onkeypress="if(event.keyCode == 13) search();"/>
								<input type="button" value="<?=BTN_SEARCH?>" onclick="search()"/>
							</div>
						</fieldset>
						<br/>
						<fieldset id="fs.date">
							<legend><?=H_FILTER_BY_DATE?></legend>
							<div style="padding: 10px;">
							<div style="width: 100%;">
								<table width="100%" border="0" cellpadding="0" cellspacing="3">
									<tr>
										<td><?=H_FILTER_DATE_FROM?>:</td>
										<td width="100%">
											<input type="text" id="txt.from" value="<?=date('d/m/Y')?> 00:00:00" style="width: 100%;" onkeypress="if(event.keyCode == 13) filterByDate();"/>
										</td>
										<td><img align="absmiddle" src="../../i/default/e/h/calendar.gif" class="btn" alt="<?=BTN_SELECT?>" id="btn.from"></td>
									</tr>
									<tr>
										<td><?=H_FILTER_DATE_TO?>:</td>
										<td>
											<input type="text" id="txt.to" value="<?=date('d/m/Y')?> 00:00:00" style="width: 100%;" onkeypress="if(event.keyCode == 13) filterByDate();"/>
										</td>
										<td><img align="absmiddle" src="../../i/default/e/h/calendar.gif" class="btn" alt="<?=BTN_SELECT?>" id="btn.to"></td>
									</tr>
									<tr>
										<td colspan="3" align="right">
											<input type="button" value="<?=BTN_FILTER_BY_DATE?>" onclick="filterByDate()"/>
										</td>
									</tr>
								</table>
							</div>
							</div>
						</fieldset>
						<br/>
						<fieldset id="fs.fake">
							<legend><?=H_FILTER_VOTES?></legend>
							<div style="padding: 10px;">
							<div style="font-size: 90%;">
								<div id="div.fakeVotes" style="margin-bottom: 10px;"><a href="javascript: showOnlyFakeVotes(1);"><?=H_SHOW_ONLY_FAKE_VOTES?></a></div>
								<div style="display: none;" id="div.allVotes"><a href="javascript: showOnlyFakeVotes(0);"><?=H_SHOW_ALL_VOTES?></a></div>
							</div>
							</div>
						</fieldset>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>