<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Pragma" content="no-cache">
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.H_SELECT_USERS}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/common.js"></script>
	</head>
	<body style="background:#f2f2f2;border-style:groove;padding:0;border-width:2px;">
		<script language="JavaScript" type="text/JavaScript">
			var type = "{$type}";
			returnValue = new Array();
			{literal}
				$(document).ready(function(){
					$("#close").click(function(){
						window.close();
					});
				});
				$(document).keyup(function(event){
				    if (event.keyCode == 27) {
				        window.close();
				    }
				});

				function startsearch() {
					enableSelect(false);
					$("#f_result").attr('src','users.php?type=' + type + '&keyword=' + $("#f_query").val());
				}
				function enableSelect(enable) {					$("#btn_select").attr('disabled',!enable);
				}
				function selectUsers() {					var select = null;
					try {
						select = document.getElementById("f_result").contentWindow.document.getElementById("s.users");
					} catch(e) {return false;}
					if(select.multiple) {
						var rValue = [];
						for(var i = 0; i<select.options.length; i++)
							if(select.options[i].selected){
								rValue[rValue.length] = [select.options[i].value, select.options[i].getAttribute("login"), select.options[i].innerHTML];
							}
						returnValue = rValue;
					} else
						if(select.selectedIndex >= 0)
							returnValue = [[select.options[select.selectedIndex].value, select.options[select.selectedIndex].getAttribute("login"), select.options[select.selectedIndex].innerHTML]];
					if(returnValue.length > 0)
						window.close();
				}
			{/literal}
		</script>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" height="100%">
			<tr height="35">
				<td style="padding:0 5px;border-bottom:solid 1px #999;background:#ccc;">
					<form onsubmit="startsearch();return false;" style="padding:0px;margin:0px;">
						<input type="text" id="f_query" size="40"> <input type="submit" title="{$smarty.const.BTN_SEARCH}" value="{$smarty.const.BTN_SEARCH}">
					</form>
				</td>
			</tr>
			<tr>
				<td>
					<iframe src="users.php" height="300" width="100%" id="f_result" frameborder="0"></iframe>
				</td>
			</tr>
			<tr height="40">
				<td align="right" style="padding:0 5px;border-top:solid 1px #999;">
					<input type="button" value="{$smarty.const.BTN_SELECT}" title="{$smarty.const.BTN_SELECT}" id="btn_select" disabled onclick="selectUsers();"> <input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" title="{$smarty.const.BTN_CANCEL}">
				</td>
			</tr>
		</table>
	</body>
</html>