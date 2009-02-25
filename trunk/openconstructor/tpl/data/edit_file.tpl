<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_DS_FILE} | {$ds_name}</title>
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
		<h3>{$smarty.const.EDIT_DS_FILE}</h3>
		{$reportResult}
		<form name="f" method="POST" action="i_data.php">
			<input type="hidden" name="action" value="edit_dsfile" />
			<input type="hidden" name="ds_id" value="{$ds_id}" />
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
						<td><input type="text" name="dssize" size="5" maxlength="4" value="{$ds->size}" /> {$smarty.const.DS_RECORDS}</td>
					</tr>
				</table>
				<div class="property"{$isValid.folder}>
					<span>{$uf.folder}:</span>
					<div class="tip">{$smarty.const.DS_TT_FOLDER_NAME}</div>
					<input type="text" disabled value="{$folder}" name="folder" size="32" maxlength="32">
				</div>
				<div class="property">
					<span>{$smarty.const.DS_ALLOWED_FILETYPES}:</span>
					<div class="tip">{$smarty.const.DS_TT_ALLOWED_FILETYPES}</div>
					<input type="text" style="font-family:courier new;" value="{$filetypes}" name="filetypes" size="64">
				</div>
				<div class="property">
					<input type="checkbox" style="width:auto;" value="true" name="autoname"{if $ds->autoName} checked{/if}> {$smarty.const.DS_AUTONAME_FILES}
				</div>
			</fieldset><br>
			<fieldset style="padding:10"><legend>{$smarty.const.DS_SEARCH}</legend>
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td nowrap><input type="checkbox" name="isindexable"{if $ds->isIndexable} checked{/if}> {$smarty.const.IS_INDEXABLE}</td>
				</tr>
			</table>
			</fieldset><br>
			<fieldset style="padding:10"><legend>{$smarty.const.DS_SECURITY}</legend>
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td valign="top">{$smarty.const.DS_ALLOWED_GROUPS}:</td>
					<td><select size="10" name="groups[]" multiple>
					{foreach from=$groups item=g}
						<option value="{$g.id}"{if $g.selected == 'true'} selected{/if}>{$g.title}</option>
					{/foreach}
					</select></td>
				</tr>
			</table>
			</fieldset><br>
			<div align="right">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" name="create" id="f_save" /> 
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>