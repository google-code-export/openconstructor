<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Pragma" content="no-cache">
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>Users</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/common.js"></script>
		<script type="text/javascript" src="{$ocm_home}/lib/js/base.js"></script>
		<script type="text/javascript" src="{$ocm_home}/lib/js/widgets.js"></script>
	</head>
	<body>
		{literal}
			<style>
				BODY {border:none;padding:0;margin:0;font:normal 13px arial;}
				TABLE {font-size:100%;}
				A {color:#06c;}
				#users {height:100%;}
				.users {width:100%;}
				.users TR.r1 {background:#f2f2f2;}
				.users TR.r0 {background:#fff;}
				.users TD {padding:4px 6px 5px;border-bottom:solid 1px #f8f8f8;}
				.users TD.input {width:20px;}
				A.gray {color:#666;}
			</style>
		{/literal}
		{if $tpls_vars.keyword}
			{if $users|@sizeof gt 0}
				<select size="10" {if $tpls_vars.type eq 'multiple'}multiple{/if} id="s.users" style="display:none">
					{foreach from=$users item=val}
						<option value="{$val.id}" active="{$val.active}" login="{$val.login}" email="{$val.email|escape}">{$val.name}</option>
					{/foreach}
				</select>
				<div id="users"></div>
				<script language="JavaScript" type="text/JavaScript">
					var wchome = "{$ocm_home}", usr_login = "{$smarty.const.USR_LOGIN}", usr_email = "{$smarty.const.USR_EMAIL}";
					{literal}
						window.holder = (window.dialogArguments && window.dialogArguments.length > 0) ? window.dialogArguments[0].parent : window.parent;
						try{
							window.parent.enableSelect(true);
						} catch(e){}
						var select = document.getElementById("s.users");
						function doclink(index) {
							var url = wchome + "/users/edituser.php?id=" + select.options[index].value;
							return "<a href='" + url + "' onclick='window.holder.wxyopen(this.href, 600, 500);return false;' "
								+ (select.options[index].getAttribute('active') == 1 ? "" : "class='gray'")
								+ " title='" +usr_login + " " + select.options[index].getAttribute("login") + "; " + usr_email + ": "
								+ select.options[index].getAttribute("email") + "'"
								+ ">" + select.options[index].innerHTML
								+ "</a>";
						}
						var table = widgetUtils.createTableFromSelect(select, doclink);
						table.className = "users";
						table.cellPadding = 0;
						table.cellSpacing = 0;
						document.getElementById("users").appendChild(table);
                    {/literal}
				</script>
			{else}
				<table width="100%" height="100%" style="font-size:110%;">
					<tr><td align="center">{$smarty.const.H_NO_USERS_FOR_KEYWORD} "{$tpls_vars.keyword}"</td></tr>
				</table>
			{/if}
		{else}
			<table width="100%" height="100%" style="font-size:110%;">
				<tr><td align="center">{$smarty.const.H_ENTER_USR_KEYWORD}</td></tr>
			</table>
		{/if}
	</body>
</html>