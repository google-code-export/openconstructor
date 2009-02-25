<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_DS_RATING} | {$ds_name}</title>
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
			});
			{/literal}
		</script>
	</head>
	<body>
		<h3>{$smarty.const.EDIT_DS_RATING}</h3>
		{$reportResult}
		<form name="f" method="POST" action="i_data.php">
			<input type="hidden" name="action" value="edit_dsrating">
			<input type="hidden" name="ds_id" value="{$ds_id}">
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
						<td>{$smarty.const.DS_MIN_RATING}:</td>
						<td><input type="text" name="minRating" size="5" maxlength="4" value="{$ds->minRating}"></td>
					</tr>
					<tr>
						<td>{$smarty.const.DS_MAX_RATING}:</td>
						<td><input type="text" name="maxRating" size="5" maxlength="4" value="{$ds->maxRating}"></td>
					</tr>
					<tr>
						<td>{$smarty.const.DS_FAKE_RATERS_GROUP}:</td>
						<td>
							<select size="1" name="fakeRaters">
								<option value="0" style="background: #eee;">-</option>
							{foreach from=$groups item=title key=id}
								{if $id != $smarty.const.WCS_ADMINS_ID}
								<option value="{$id}"{if $id == $ds->fakeRaters} selected{/if}>{$title}</option>
								{/if}
							{/foreach}
							</select>
						</td>
					</tr>
				</table>
				<fieldset style="padding:10"><legend>{$smarty.const.DS_CLEAN_HTML}</legend>
					<table style="margin:5 0" cellspacing="3">
						<tr>
							<td colspan="2"><input type="checkbox" name="stripHTML" value="true"{if $ds->stripHTML} checked{/if} onclick="f.allowedTags.disabled = !this.checked"> {$smarty.const.DS_ENABLE_CLEAN_HTML}</td>
						</tr>
						<tr>
							<td colspan=2>{$smarty.const.DS_ALLOWED_TAGS}:</td>
						</tr>
						<tr>
							<td colspan=2><textarea cols="52" rows="4" name="allowedTags"{if !$ds->stripHTML} disabled{/if}>{$ds->allowedTags}</textarea></td>
						</tr>
					</table>
				</fieldset><br>
			</fieldset>
			<div align="right">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" name="create" id="f_save" /> 
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>