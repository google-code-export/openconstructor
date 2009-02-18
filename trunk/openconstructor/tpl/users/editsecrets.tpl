<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_USER_SECRETS} | {$user->login}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body style="border-style:groove;padding:0 20 20">
		<script language="JavaScript" type="text/JavaScript">
			var	dis = {if $user->id eq $Authentication->getOriginalUserId()}false{else}true{/if};
			{literal}
				var re=new RegExp('[^\\s]','gi');

				$(document).ready(function(){
					$("#close").click(function(){
						window.close();
					});

					$(".dsb").keyup(function(){
						if(dis)
							$("#f_create").attr('disabled',true);
						else
							$("#f_create").attr('disabled',false);
					});
				});
			{/literal}
		</script>
		<br />
		<h3>{$smarty.const.EDIT_USER_SECRETS}</h3>
		<form name="f" method="POST" action="i_users.php">
			<input type="hidden" name="id" value="{$user->id}">
			<input type="hidden" name="action" value="edit_secrets">
			<fieldset style="padding:10"><legend>{$smarty.const.USER}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.USR_LOGIN}:</td>
						<td><b>{$user->login}</b></td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_NAME}:</td>
						<td>{$user->name|escape}</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.USR_SECRETS}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td valign="top" nowrap>{$smarty.const.USR_SECRET_QUESTION}:</td>
						{if $secretQ}
							<td><b>{$secretQ}</b></td>
						{else}
							<td><span style="color:red">{$smarty.const.USR_SECRET_Q_NOTSET}</span></td>
						{/if}
					</tr>
					<tr>
						<td>{$smarty.const.USR_PASSWORD}:</td>
						<td><input type="password" name="pwd" size="32" maxlength="32"></td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_NEW_SECRET_QUESTION}:</td>
						<td><input type="text" name="secretQ" size="64" maxlength="64"></td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_SECRET_ANSWER}:</td>
						<td><input type="text" name="secretA" size="40" maxlength="40"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" id="f_save" name="save" />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>