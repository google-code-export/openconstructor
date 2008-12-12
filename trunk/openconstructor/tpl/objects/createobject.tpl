<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.CREATE_OBJECT}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body>
		<script language="JavaScript" type="text/JavaScript">
			var	dis = {$dis};
			{literal}
				var re= /\S+/gi;

				$(document).ready(function(){
					$("#close").click(function(){
						window.close();
					});

					$(".dsb").keyup(function(){						if(!$("#f_name").val().match(re) || !$("#f_description").val().match(re) || dis)
							$("#f_save").attr('disabled',true);
						else
							$("#f_save").attr('disabled',false);
					});
				});
			{/literal}
		</script>
		<body style="border-style:groove;padding:0 20 20">
		<br />
		<h3>{$smarty.const.EDIT_OBJECT}</h3>
		<form name="f" method="POST" action="createobject.php">
			<input type="hidden" name="ds_type" value="{$smarty.get.ds_type}" />
			<input type="hidden" name="obj_type" value="{$smarty.get.obj_type}" />
			<fieldset style="padding:10"><legend>{$smarty.const.OBJECT}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.PR_OBJ_NAME}:</td>
						<td><input type="text" id="f_name" name="name" size="64" maxlength="64" class="dsb" /></td>
					</tr>
					<tr>
						<td>{$smarty.const.PR_OBJ_DESCRIPTION}:</td>
						<td><textarea cols="51" rows="5" id="f_description" name="description" class="dsb"></textarea></td>
					</tr>
				</table>
			</fieldset>
			<br />
			{if $smarty.get.ds_type eq 'miscellany' or $smarty.get.obj_type eq 'htmltexthl'}
				{set noDs="true"|bool}
			{/if}
			<fieldset style="padding:10" {if $noDs}disabled{/if}><legend>{$smarty.const.OBJ_DATA}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td nowrap>{$smarty.const.PR_DATASOURCE}:</td>
						<td>
							<select size="1" name="ds_id" {if $noDs}disabled{/if}>
								{foreach from=$ds item=val}
									<option value="{$val.id}">{$val.name}</option>
								{/foreach}
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" id="f_save" name="save" disabled />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>