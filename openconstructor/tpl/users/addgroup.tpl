<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.CREATE_USERGROUP}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body id="inner_page">
		<script language="JavaScript" type="text/JavaScript">
			var	dis = {$dis};
			{literal}
				$(document).ready(function(){					var re=new RegExp('[^\\s]','gi');
					var rek = /^[a-z][a-z\-_0-9]{0,31}$/i;

					$("#close").click(function(){
						window.close();
					});

					$(".dsb").keyup(function(){
						if(!$("#f_name").val().match(re) || !$("#f_key").val().match(rek) || dis)
							$("#f_create").attr('disabled',true);
						else
							$("#f_create").attr('disabled',false);
					});
				});
			{/literal}
		</script>
		<h3 class="hTitle">{$smarty.const.CREATE_USERGROUP}</h3>
		<form name="f" method="POST" action="i_users.php">
			<input type="hidden" name="action" value="add_group">
			<fieldset><legend>{$smarty.const.USR_NEW_USERGROUP}</legend>
				<table>
					<tr>
						<td>{$smarty.const.USR_USERGROUP_KEY}:</td>
						<td><input type="text" name="key" id="f_key" size="32" maxlength="32" class="dsb"></td>
					</tr>
					<tr>
						<td>{$smarty.const.USR_USERGROUP_NAME}:</td>
						<td><input type="text" name="name" id="f_name" size="64" maxlength="128" class="dsb"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_CREATE}" id="f_create" name="create" disabled />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>
<html>