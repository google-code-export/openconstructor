<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_DS_GALLERY} | {$ds_name}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script language="JavaScript" type="text/JavaScript">
			var re = new RegExp('[^\\s]','gi');
			var dis = {$dis};
			{literal}
			$(document).ready(function(){
				$("#close").click(function(){
					window.close();
				});

				$(".dsb").keyup(function(){
					if(!$("#f_name").val().match(re) || dis)
						$("#f_save").attr('disabled',true);
					else
						$("#f_save").attr('disabled',false);
				});
				$("#f_intro").keyup(function(){
					updateintro();
				});
			});
			function updateintro(){
				$("#f_ixmin").val(parseInt(($("#f_xmin").val()*$("#f_intro").val())/100));
				$("#f_ixmax").val(parseInt(($("#f_xmax").val()*$("#f_intro").val())/100));
				$("#f_iymin").val(parseInt(($("#f_ymin").val()*$("#f_intro").val())/100));
				$("#f_iymax").val(parseInt(($("#f_ymax").val()*$("#f_intro").val())/100));
			}
			function stripHTMLToggle(value) {
				$("#f_allowedTags").attr('disabled', !value);
				$("#f_encodeemail").attr('disabled', !value);
			}
			{/literal}
		</script>
	</head>
	<body>
		<h3>{$smarty.const.EDIT_DS_GALLERY}</h3>
		{$reportResult}
		<form name="f" method="POST" action="i_data.php">
			<input type="hidden" name="action" value="edit_dsgallery" />
			<input type="hidden" name="ds_id" value="{$ds->ds_id}" />
			<fieldset style="padding:10"><legend>{$smarty.const.DS_GENERAL_PROPS}</legend>
				<div class="property"{$isValid.ds_name}>
					<span>{$uf.ds_name}:</span>
					<input type="text" name="ds_name" id="f_name" class="dsb" value="{$ds_name}" size="64" maxlength="64" />
				</div>
				<div class="property"{$isValid.description}>
					<span>{$uf.description}:</span>
					<textarea cols="51" rows="5" name="description">{$description}</textarea>
				</div>
			</fieldset><br>
			<fieldset style="padding:10"><legend>{$smarty.const.DS_PROPS}</legend>
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td>{$smarty.const.DS_SIZE}:</td>
					<td><input type="text" name="dssize" size="5" maxlength="4" value="{$ds->size}"> {$smarty.const.DS_RECORDS}</td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="autoPublish" value="true"{if $ds->autoPublish} checked{/if}{if !$disPublish} disabled{/if}> {$smarty.const.DS_ALLOW_AUTOPUBLISHING}</td>
				</tr>
			</table>
				<fieldset style="padding:10"><legend>{$smarty.const.DS_CLEAN_HTML}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td colspan="2"><input type="checkbox" name="stripHTML" value="true"{if $ds->stripHTML} checked{/if} onclick="stripHTMLToggle(this.checked)"> {$smarty.const.DS_ENABLE_CLEAN_HTML}</td>
					</tr>
					<tr>
						<td colspan=2>{$smarty.const.DS_ALLOWED_TAGS}:</td>
					</tr>
					<tr>
						<td colspan=2><textarea cols="52" rows="4" id="f_allowedTags" name="allowedTags"{if !$ds->stripHTML} disabled{/if}>{$ds->allowedTags}</textarea></td>
					</tr>
					<tr>
						<td colspan="2"><input type="checkbox" id="f_encodeemail" name="encodeemail" value="true"{if $ds->encodeemail} checked{/if}{if !$ds->stripHTML} disabled{/if}> {$smarty.const.DS_ENABLE_EMAIL_ENCODING}</td>
					</tr>
				</table>
				</fieldset><br>
				<fieldset style="padding:10"><legend>{$smarty.const.DS_SEARCH}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td nowrap><input type="checkbox" name="isindexable"{if $ds->isIndexable} checked{/if}> {$smarty.const.IS_INDEXABLE}</td>
					</tr>
				</table>
				</fieldset><br>
			</fieldset><br>
			<fieldset style="padding:10"><legend>{$smarty.const.DS_GRAPH_PROPS}</legend>
				<fieldset style="padding:10"><legend>{$smarty.const.DS_GRAPH_BOUNDS}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.DS_GRAPH_MIN_RECT}:</td>
						<td nowrap valign="top">{$smarty.const.DS_GRAPH_WIDTH} <input type="text" id="f_xmin" name="xmin" size="4" maxlength="4" value="{$ds->images.xmin}">&nbsp;&nbsp;{$smarty.const.DS_GRAPH_HEIGHT} <input type="text" id="f_ymin" name="ymin" size="4" maxlength="4" value="{$ds->images.ymin}"></td>
					</tr>
					<tr>
						<td>{$smarty.const.DS_GRAPH_MAX_RECT}:</td>
						<td nowrap>{$smarty.const.DS_GRAPH_WIDTH} <input type="text" id="f_xmax" name="xmax" size="4" maxlength="4" value="{$ds->images.xmax}">&nbsp;&nbsp;{$smarty.const.DS_GRAPH_HEIGHT} <input type="text" id="f_ymax" name="ymax" size="4" maxlength="4" value="{$ds->images.ymax}"></td>
					</tr>
				</table>
				</fieldset><br>
				<fieldset style="padding:10"><legend>{$smarty.const.DS_GRAPH_IMAGES}</legend>
				<table style="margin:5 0" cellspacing="3" width="100%">
					<tr>
						<td nowrap><input type="checkbox" name="img_main"{if $ds->images.main} checked{/if} disabled> {$smarty.const.DS_GRAPH_IMAGEMAIN}</td><td></td>
					</tr>
					<tr>
						<td colspan="2"><hr size="1"></td>
					</tr>
					<tr>
						<td nowrap valign="top"><input type="checkbox" name="img_intro"{if $ds->images.intro} checked{/if} disabled> {$smarty.const.DS_GRAPH_IMAGEINTRO}</td>
						<td></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;{$smarty.const.DS_GRAPH_IMAGEINTRO_SIZE}:</td>
						<td width="100%"><input type="text" name="intro" id="f_intro" size="4" maxlength="3" value="{$ds->images.intro}"{if !$ds->images.intro} disabled{/if}>{$smarty.const.DS_GRAPH_PERCENT_10_100}</td>
					</tr>
					<tr>
						<td NOWRAP>&nbsp;&nbsp;&nbsp;&nbsp;{$smarty.const.DS_GRAPH_MIN_RECT}:</td>
						<td nowrap valign="top">{$smarty.const.DS_GRAPH_WIDTH} <input type="text" id="f_ixmin" name="ixmin" size="4" disabled>&nbsp;&nbsp;{$smarty.const.DS_GRAPH_HEIGHT} <input type="text" id="f_iymin" name="iymin" size="4" disabled></td>
					</tr>
					<tr>
						<td NOWRAP>&nbsp;&nbsp;&nbsp;&nbsp;{$smarty.const.DS_GRAPH_MAX_RECT}:</td>
						<td nowrap>{$smarty.const.DS_GRAPH_WIDTH} <input type="text" id="f_ixmax" name="ixmax" size="4" disabled>&nbsp;&nbsp;{$smarty.const.DS_GRAPH_HEIGHT} <input type="text" id="f_iymax" name="iymax" size="4" disabled></td>
					</tr>
				</table>
				</fieldset><br>
			</fieldset><br>
			<div align="right">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" name="create" id="f_save" /> 
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		<script>updateintro()</script>
		</form>
	</body>
</html>