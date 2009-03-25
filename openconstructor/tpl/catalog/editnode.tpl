<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_NODE}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script language="JavaScript" type="text/JavaScript">
			var re = new RegExp('[^\\s]','gi');
			var rek=/^[a-z][a-z0-9_\-]{literal}{0,31}$/gi{/literal};
			var dis = {$dis};
			{literal}
			$(document).ready(function(){
				$("#close").click(function(){
					window.close();
				});
				$("#f_name").focus();
				
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
<h3>{$smarty.const.EDIT_NODE}</h3>
<form name="f" method="POST" action="i_catalog.php">
	<input type="hidden" name="action" value="edit_node">
	<input type="hidden" name="id" value="{$node->id}">
	<input type="hidden" name="key" value="{$node->key}">
	<fieldset style="padding:10"><legend>{$smarty.const.NODE_PROPS}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td>{$smarty.const.NODE_KEY}:</td>
			<td><input type="text" size="32" maxlength="32" id="f_key"  value="{$node->key}" DISABLED></td>
		</tr>
		<tr>
			<td>{$smarty.const.NODE_HEADER}:</td>
			<td><input type="text" name="header" id="f_name" size="32" class="dsb" maxlength="64" value="{$node->header}"></td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right">
		<input type="submit" value="{$smarty.const.BTN_SAVE}" id="f_save" name="save" />
		<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
	</div>
</form>
</body>
</html>