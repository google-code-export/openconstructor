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
 * $Id: editvote.php,v 1.7 2007/03/02 10:06:40 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	
	assert(@$_GET['ds_id'] > 0 && @$_GET['rid'] > 0 && @$_GET['id'] > 0);
	require_once(LIBDIR.'/dsmanager._wc');
	$dsm = new DSManager();
	$_ds = &$dsm->load($_GET['ds_id']); 
	$_doc = $_ds->get_record($_GET['rid']);
	assert($_doc !== null);
	$vote = $_ds->getVote($_doc['id'], $_GET['id']);
	assert($vote != null);
	require_once(LIBDIR.'/syntax/syntaxhighlighter._wc');
	require_once($_SERVER['DOCUMENT_ROOT'].WCHOME.'/include/toolbar._wc');
	$m = array();
	preg_match_all('~<([A-Z0-9]+)~', strtoupper($_ds->allowedTags), $m, PREG_PATTERN_ORDER);
	$allowed = (array) @$m[1];
	$sDoc = $_ds->wrapDocument($_doc);
	$save = WCS::decide($_ds, 'editdoc') || WCS::decide($sDoc, 'editdoc');
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?=htmlspecialchars($_doc['header'], ENT_COMPAT, 'UTF-8').' | '.EDIT_RATING_VOTE?></title>
		<script src="../../lib/js/base.js"></script>
		<script src="../../lib/js/wysiwyg.js"></script>
		<script src="../../lib/js/widgets.js"></script>
		<script>
			var
				host='<?=$_host?>',
				skin='<?=SKIN?>'
				;
			function saveDocument() {
				f.onsubmit();
				f.submit();
			}
			window.onload = function() {
				keys = new KeyBinder(document);
				keys.addShortcut("ctrl+s", function() {if(<?=$save ? 'true' : 'false'?>) saveDocument()});
				keys.addShortcut("ctrl+h", function() {control.editSource();});
				control = new WYSIWYGController();
				new WYSIWYGWidget(
					document.getElementById('comment'),
					document.getElementById('iframe.comment'),
					control,
					'@import url("http://<?=$_SERVER['HTTP_HOST']?>/css/content.css");',
					document.getElementById('src.comment')
				);
				keys.addTarget(document.getElementById('iframe.comment').contentWindow.document, document.getElementById('iframe.comment').contentWindow);
				if(document.getElementById("txt.date"))
					new CalendarWidget(document.getElementById("txt.date"), document.getElementById("btn.date"), 2000, 2010, "calendar", "<?=LANGUAGE?>");
				updateSaveState();
			}
			var saveable = <?=(int) $save?>,
				fake = <?=(int) $vote['fake']?>,
				minRating = <?=(int) $_ds->minRating?>,
				maxRating = <?=(int) $_ds->maxRating?>
				;
			function updateSaveState() {
				if(saveable) {
					if(fake) {
						var rating = parseInt(f.rating.value),
							votes = parseInt(f.votes.value);
						f.save.disabled = !(rating >= minRating && rating <=maxRating && votes > 0);
					} else
						f.save.disabled = false;
				}
			}
		</script>
		<style>
			@import url(<?=WCHOME.'/'.SKIN?>.css);
			@import url(<?=WCHOME?>/lib/js/api.css);
			A IMG {
				border: none;
			}
			TD.param {
				padding-left: 50px;
			}
			#warning {
				color: #c30;
				font-style: italic;
			}
		</style>
	</head>
<body style="border:groove; border-width:2; margin:10;" ondrag="return false;">
<form method="POST" style="margin: 0; padding: 0;" name="f" action="i_rating.php">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
	<tr>
		<td style="padding-bottom:5px">
			<input type=hidden name="action" value="edit_vote">
			<input type="hidden" name="ds_id" value="<?=$_ds->ds_id?>">
			<input type="hidden" name="ratingId" value="<?=$vote['id']?>">
			<input type="hidden" name="userId" value="<?=$vote['user_id']?>">
			<table border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td><?=F_VOTE_AUTHOR?>:</td>
					<td>
						<? if($vote['login']): ?>
							<b><?=$vote['name']?></b> [<a href="javascript: openWindow('../../users/edituser.php?id=<?=$vote['login']?>', 600);"><?=$vote['login']?></a>]
						<? else: 
							echo '<span id="warning">'.H_VOTE_AUTHOR_WAS_REMOVED.'</span>';
						endif; ?>
					</td>
					
					<td class="param"><?=F_VOTE_RATING?>:</td>
					<td>
						<? if($vote['fake']): ?>
							<input type="text" name="rating" value="<?=$vote['rating']/$vote['votes']?>" size="5" onpropertychange="updateSaveState();">
						<? else: ?>
							<b><?=$vote['rating']?></b>
						<? endif; ?>
					</td>
				</tr>
				<tr>
					<td><?=F_VOTE_DATE?>:</td>
					<td>
						<? if($vote['fake']): ?>
							<input type="text" name="date" id="txt.date" value="<?=date('d/m/Y H:i:s', $vote['date'])?>"/>
							<img align="absmiddle" src="../../i/default/e/h/calendar.gif" class="btn" id="btn.date" alt="<?=BTN_SELECT?>">
						<?php
							else:
								echo wcfFormatTime((LANGUAGE == 'rus' ? '$ru' : '').'j F Y H:i:s', $vote['date']);
							endif;
						?>
					</td>
					
					<? if($vote['fake']): ?>
						<td class="param"><?=F_VOTE_COUNT?>:</td>
						<td><input type="text" name="votes" value="<?=$vote['votes']?>" size="5" onpropertychange="updateSaveState();"></td>
					<? else: ?>
						<td colspan="2" style="font-size: 140%;">&nbsp;</td>
					<? endif; ?>
				</tr>
			</table>
			<hr size="1">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" height="24">
				<tr><td align="left">
					<nobr class="tool"><?php
						wysiwygtoolbar('control');
					?></nobr>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr height="100%" valign="top">
		<td style="border-width:2px; border-style: groove;">
			<textarea name="comment" style="display:none; width: 100%; height: 100%;"><?=htmlspecialchars($vote['comment'], ENT_COMPAT, 'UTF-8')?></textarea>
			<iframe name="iframe.comment" style="border:none; margin:0px;" width="100%" height="100%"></iframe>
			<?php if(WC_USE_SYNTAX):?>
				<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" style="width: 100%; height: 100%;" id="src.comment"><?=SyntaxHighlighter::getHtmlEditor()?></object>
			<?php endif;?>
		</td>
	</tr>
	<tr>
		<td style="padding-top: 5px">
			<div style="float: right;">
				<input type="submit" value="<?=BTN_SAVE?>" name="save">
				<input type="button" value="<?=BTN_CANCEL?>" onclick="window.close();">
			</div>
			<input type="checkbox" name="active" value="true" id="chk.active" <?=$vote['active'] ? 'checked' : ''?>> <label for="chk.active"><?=F_VOTE_IS_ACTIVE?></label>
		</td>
	</tr>
</table>
</form>
</body>
</html>