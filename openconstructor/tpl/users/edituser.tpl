<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_USER} | {$user->login}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$ocm_home}/lib/js/base.js"></script>
	</head>
	<body style="border-style:groove;padding:0 20 20">
		<script language="JavaScript" type="text/JavaScript">
			var	dis = {if $WCS->decide($group, 'edituser') or $WCS->decide($user, 'edit')}false{else}true{/if};
			var disableGroups = {if $WCS->decide($group, 'edit.group')}true{else}false{/if};
			var	alert_txt = "{$smarty.const.H_PASSWORDS_DOESNT_MATCH}";
			var user_membership = {$user->membership|@sizeof};
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

					$("#f_newpwd").click(function(){
						$("#f_pwd1").attr('disabled', !$(this).attr('checked'));
						$("#f_pwd2").attr('disabled', !$(this).attr('checked'))
					});

					$("#membership").change(function(){						var old = $("option.oldGroup:selected").get().length;
						$("#f_groupId").attr('disabled',!(old == user_membership));
					});

					if(disableGroups){
						$("#fMembership").attr('disabled',true);
						$("#membership").attr('disabled',true);
						$("#f_groupId").attr('disabled',true);
					}

					$("#f_form").submit(function(){						if($("#f_newpwd").attr('checked'))
							if($("#f_pwd1").val() != $("#f_pwd2").val()){
								alert(alert_txt);
								return false;
							}
						return true;
					});
				});
			{/literal}
		</script>
		<br />
		<h3>{$smarty.const.EDIT_USER}</h3>
		<form name="f" method="POST" action="i_users.php" id="f_form">
			<input type="hidden" name="login" value="{$user->login}">
			<input type="hidden" name="action" value="edit_user">
			<fieldset style="padding:10"><legend>{$smarty.const.USER}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.USR_LOGIN}:</td>
						<td><b>{$user->login}</b></td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_NAME}:</td>
						<td><input type="text" name="name" id="f_name" value="{$user->name|escape}" size="40" maxlength="128" class="dsb"></td>
					</tr>
					<tr>
						<td colspan="2"><input type="checkbox" name="isDisabled" {if !$user->active}checked{/if} {if !($WCS->decide($group, 'edituser') or $WCS->decide($user, 'edit.status'))}disabled{/if}> {$smarty.const.USR_IS_FREEZED}</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.USR_ADDITIONAL_INFO}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.USR_EMAIL}:</td>
						<td><input type="text" name="email" value="{$user->email}" size="40" maxlength="255"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.USR_PROPS}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.USR_EXPIRES}:</td>
						<td><input type="text" name="expiry" value="{if $user->expiry}{$user->expiry|date_format:'%e %B %Y'}{/if}" size="40" maxlength="255" {if !$WCS->decide($user, 'edit.expiry')}disabled{/if}></td>
					</tr>
				</table>
			</fieldset>
			<br />
			{if $WCS->decide($group, 'edituser') or $WCS->decide($user, 'edit.pwd')}
				<fieldset style="padding:10"><legend>{$smarty.const.USR_SET_NEW_PASSWORD}</legend>
					<table style="margin:5 0" cellspacing="3">
						<tr>
							<td colspan="2"><input type="checkbox" name="newpassword" id="f_newpwd">{$smarty.const.USR_CHANGE_PASSWORD}</td>
						</tr>
						<tr>
							<td>{$smarty.const.USR_NEW_PASSWORD}:</td>
							<td><input type="password" name="password1" id="f_pwd1" disabled size="32" maxlength="32"></td>
						</tr>
						<tr>
							<td>{$smarty.const.USR_CONFIRM_NEW_PASSWORD}:</td>
							<td><input type="password" name="password2" id="f_pwd2" disabled size="32" maxlength="32"></td>
						</tr>
					</table>
				</fieldset>
				<br />
			{/if}
			{if $secret}
				<fieldset style="padding:10"><legend>{$smarty.const.USR_SECRETS}</legend>
					<table style="margin:5 0" cellspacing="3">
						<tr>
							{if $secretQ}
								<td valign="top" nowrap>{$smarty.const.USR_SECRET_QUESTION}:</td>
								<td><i>{$secretQ}</i></td>
							</tr>
							<tr>
								<td colspan="2"><input type="button" value="{$smarty.const.BTN_USR_CHANGE_SECRETS}" onclick="openWindow('editsecrets.php?id={$user->id}', 600, 400)"/></td>
							{else}
								<td colspan="2">{$smarty.const.USR_SECRET_QUESTION}: <span style="color:red">{$smarty.const.USR_SECRET_Q_NOTSET}</span> <input type="button" value="{$smarty.const.BTN_USR_SET_SECRETS}" onclick="openWindow('editsecrets.php?id={$user->id}', 600, 400)" style="vertical-align:middle"/></td>
							{/if}
						</tr>
					</table>
				</fieldset>
				<br />
			{/if}
			<fieldset style="padding:10" id=fMembership><legend>{$smarty.const.USR_MEMBERSHIP}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td valign="top">{$smarty.const.USR_MEMBERSHIP}:</td>
						<td>
							<select size="10" id="membership" name="membership[]" multiple>
								{foreach from=$groups key=id item=title}
									<option value="{$id}" {if $id|array_search:$user->membership !== "false"|bool}selected class="oldGroup"{/if}>{$title}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_MAIN_GROUP}:</td>
						<td>
							<select size="1" name="groupId" id="f_groupId">
								{foreach from=$user->membership item=id}
									<option value="{$id}" {if $id eq $user->groupId}selected{/if}>{$groups.$id}</option>
								{/foreach}
							</select>
						</td>
					</tr>
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