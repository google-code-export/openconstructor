<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.CREATE_NODE}</title>
		<link href="../{$smarty.const.SKIN}.css" type=text/css rel=stylesheet>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script language="JavaScript" type="text/JavaScript">
			var re=new RegExp('[^\\s]','gi');
			var rek=/^[a-z][a-z0-9_\-]{literal}{0,31}$/gi{/literal};
			var dis = {$dis};
			{literal}
			$(document).ready(function(){
				$("#f_key").focus();
				$("#close").click(function(){
					window.close();
				});

				$(".dsb").keyup(function(){
					if(!$("#f_name").val().match(re) || !$("#f_key").val().match(rek) || dis)
						$("#f_save").attr('disabled',true);
					else
						$("#f_save").attr('disabled',false);
				});
			});
			{/literal}
		</script>
	</head>
<body>
<br>
<h3>{$smarty.const.CREATE_NODE}</h3>
<form name="f" method="POST" action="i_catalog.php">
	<input type="hidden" name="action" value="create_node">
	<input type="hidden" name="parent" value="{$in->id}">
	<fieldset style="padding:10"><legend>{$smarty.const.NEW_NODE}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td>{$smarty.const.NODE_KEY}:</td>
			<td><input type="text" name="key" id="f_key" class="dsb" size="32" maxlength="32" /></td>
		</tr>
		<tr>
			<td>{$smarty.const.NODE_HEADER}:</td>
			<td><input type="text" name="header" id="f_name" class="dsb" size="32" maxlength="64" /></td>
		</tr>
	</table>
	</fieldset><br>
	<fieldset style="padding:10"><legend>{$smarty.const.PARENT_NODE}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td>{$smarty.const.CREATE_NODE_IN}:</td>
			<td><b>{$in->getFullKey('/')}</b></td>
		</tr>
		<tr>
			<td>{$smarty.const.PARENT_NODE}:</td><td>{$in->header}</td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right">
		<input type="submit" value="{$smarty.const.BTN_CREATE}" id="f_save" name="create" DISABLED>
		<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close">
	</div>
</form>
</body>
</html>