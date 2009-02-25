<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.CREATE_DS_HYBRID}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script language="JavaScript" type="text/JavaScript">
			var re = new RegExp('[^\\s]','gi');
			var dis = {$dis};
			{literal}
			var rek=/^[a-zA-Z][a-zA-Z0-9]{0,15}$/g;
			$(document).ready(function(){
				$("#close").click(function(){
					window.close();
				});

				$(".dsb").keyup(function(){
					if(!$("#f_key").val().match(rek) || !$("#f_name").val().match(re) || dis)
						$("#f_save").attr('disabled',true);
					else
						$("#f_save").attr('disabled',false);
				});
			});
			{/literal}
		</script>
	</head>
	<body>
		<h3>{$smarty.const.CREATE_DS_HYBRID}</h3>
		{$reportResult}
<form name="f" method="POST" action="i_data.php">
	<input type="hidden" name="action" value="create_dshybrid">
	<fieldset style="padding:10"><legend>{$smarty.const.DS_GENERAL_PROPS}</legend>
		<div class="property"{$isValid.ds_key}>
			<span>{$uf.ds_key}:</span>
			<input type="text" name="ds_key" id="f_key" class="dsb" value="{$ds_key}" size="64" maxlength="64" />
		</div>
		<div class="property"{$isValid.ds_name}>
			<span>{$uf.ds_name}:</span>
			<input type="text" name="ds_name"id="f_name" class="dsb"  value="{$ds_name}" size="64" maxlength="64" />
		</div>
		<div class="property"{$isValid.description}>
			<span>{$uf.description}:</span>
			<textarea cols="51" rows="5" name="description">{$description}</textarea>
		</div>
	</fieldset><br>
	<fieldset style="padding:10"><legend>{$smarty.const.DS_PROPS}</legend>
		{$smarty.const.DS_PARENT}:
		<select name="parent" size="1" align="absmiddle">
			<option value="0">-</option>
		{foreach from=$ds item=v}
			<option value="{$v.id}"{if $v.id eq $getIn} selected{/if}>{$v.pathView}{$v.name}</option>
		{/foreach}
		</select>
	</fieldset><br>
	<div align="right">
		<input type="submit" value="{$smarty.const.BTN_CREATE_DS}" name="create" id="f_save" disabled /> 
		<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
	</div>
</form>
</body>
</html>