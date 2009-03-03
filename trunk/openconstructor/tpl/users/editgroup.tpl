<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_USERGROUP} | {$group->title|escape}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body id="inner_page">
		<script language="JavaScript" type="text/JavaScript">
			var	dis = {if $WCS->decide($group, 'editgroup')}false{else}true{/if};
			{literal}
				var re=new RegExp('[^\\s]','gi');

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
		{literal}
			<style>
				TR.l1 TD {
					padding: 15px 0 5px 10px;
					font-size: 115%;
				}
				TR.l2 TD {
					padding: 2px 0 2px 30px;
				}
				TR.l3 TD {
					padding: 0 0 0 50px;
				}
				TD.c {
					padding-left: 0 !important;
				}
			</style>
		{/literal}
		<h3 class="hTitle">{$smarty.const.EDIT_USERGROUP}</h3>
		<form name="f" method="POST" action="i_users.php">
			<input type="hidden" name="group_id" value="{$group->id}">
			<input type="hidden" name="action" value="edit_group">
			<fieldset><legend>{$smarty.const.USR_USERGROUP}</legend>
				<table>
					<tr>
						<td>{$smarty.const.USR_USERGROUP_KEY}:</td>
						<td><input type="text" name="key" value="{$group->name}" disabled size="32" maxlength="32"></td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_USERGROUP_NAME}:</td>
						<td><input type="text" name="name" id="f_name" value="{$group->title|escape}" class="dsb" size="64" maxlength="128"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset {$dis}><legend>{$smarty.const.USR_PROFILES}</legend>
				<table>
					<tr>
						<td>{$smarty.const.USR_PROFILES_DS}:</td>
						<td>
							<select name="profileType" size="1">
								<option value="0">-</option>
								{foreach from=$ds item=val}
									<option value="{$val.id}" {if $val.id eq $group->profileType}selected{/if}>{$val.path|regex_replace:"~\d*,\d+~":"&nbsp;&nbsp;&nbsp;&nbsp;"|substr:24}{$val.name}</option>{* данная запись высавляет отступы, заменяя пробелами, первый уровень обрезается = 4 пробела *}
								{/foreach}
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset {if !$WCS->decide($group, 'editgroup.umask')}disabled{/if}><legend>{$smarty.const.USR_MASK}</legend>
				<table>
					<tr>
						<td valign="top">{$smarty.const.USR_MASK_PATTERN}:</td>
						<td><textarea name="umask" cols="40" rows="3">{$group->umask|escape}</textarea>
				</table>
			</fieldset>
			<br />
			<fieldset {$dis}><legend>{$smarty.const.USR_AUTHS}</legend>
				<table>
					{foreach from=$powers name=power item=val}
						<tr class="l{$val.level}">
							<td class="c"><input type="checkbox" id="ch{$smarty.foreach.power.iteration}" name="act[{$val.name}]" {if $val.state}checked{/if}></td>
							<td><label for="ch{$smarty.foreach.power.iteration}" title={$val.name}>{$val.title}</label></td>
						</tr>
					{/foreach}
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" id="f_save" name="save" />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
		<br /><br />
	</body>
</html>