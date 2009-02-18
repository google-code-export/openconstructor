<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.CREATE_USER}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body style="border-style:groove;padding:0 20 20">
		<script language="JavaScript" type="text/JavaScript">
			var	dis = {if $WCS->decide($group, 'createuser')}false{else}true{/if};
			var	alert_txt = "{$smarty.const.H_PASSWORDS_DOESNT_MATCH}";
			{literal}
				var re=new RegExp('[^\\s]','gi');

				$(document).ready(function(){
					$("#close").click(function(){
						window.close();
					});

					$(".dsb").keyup(function(){
						if(!$("#f_name").val().match(re) || !$("#f_login").val().match(re) || dis)
							$("#f_create").attr('disabled',true);
						else
							$("#f_create").attr('disabled',false);
					});

					$("#f_form").submit(function(){						if($("#f_pswd1").val() != $("#f_pswd2").val()){							alert(alert_txt);
							return false;
						}
					});
				});
			{/literal}
		</script>
		<br />
		<h3>{$smarty.const.CREATE_USER}</h3>
		<form name="f" method="POST" action="i_users.php" id="f_form">
			<input type="hidden" name="group_id" value="{$group->id}">
			<input type="hidden" name="action" value="add_user">
			<fieldset style="padding:10"><legend>{$smarty.const.USER}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.USR_LOGIN}:</td>
						<td><input type="text" name="login" id="f_login" size="32" maxlength="32" class="dsb"></td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_NAME}:</td>
						<td><input type="text" name="name" id="f_name" size="32" maxlength="128" class="dsb"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.USR_ADDITIONAL_INFO}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.USR_EMAIL}:</td>
						<td><input type="text" name="email" size="40" maxlength="255"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.USR_SET_PASSWORD}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.USR_PASSWORD}:</td>
						<td><input type="password" name="password1" id="f_pswd1" size="32" maxlength="32"></td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_CONFIRM_PASSWORD}:</td>
						<td><input type="password" name="password2" id="f_pswd2" size="32" maxlength="32"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_CREATE}" id="f_create" name="create" disabled />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
		<br /><br />
	</body>
</html>