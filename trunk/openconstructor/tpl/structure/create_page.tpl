<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.H_CREATE_PAGE}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body id="inner_page">
		<script>
			var	uri = '{$in->uri}', dis = {$disabled};
			{literal}
				var re=new RegExp('[^\\s]','gi');
				var folder = /^[a-z0-9][a-z0-9\-_]{0,31}$/i;

				$(document).ready(function(){
					$("#close").click(function(){
						window.close();
					});

					$("#page_name").keyup(function(){						$("#url").text(uri+$(this).val()+'/');
					});

					$(".dsb").keyup(function(){
						if(!$("#page_name").val().match(folder) || !$("#header").val().match(re) || dis)
							$("#create").attr('disabled',true);
						else
							$("#create").attr('disabled',false);
					});
				});
			{/literal}
		</script>
		<h3 class="hTitle">{$smarty.const.H_CREATE_PAGE}</h3>
		<form name="f" method="POST" action="i_structure.php">
			<input type="hidden" name="action" value="create_page" />
			<input type="hidden" name="parent_id" value="{$in->id}" />
			<fieldset><legend>{$smarty.const.NEW_PAGE}</legend>
				<table cellspacing="3">
					<tr>
						<td>{$smarty.const.PAGE_FOLDER}:</td>
						<td><input type="text" id="page_name" name="page_name" size="32" maxlength="32" class="dsb" /></td>
					</tr>
					<tr>
						<td>{$smarty.const.PAGE_NAME}:</td>
						<td><input type="text" id="header" name="header" size="32" maxlength="64" class="dsb" /></td>
					</tr>
					<tr>
						<td colspan="2">{$smarty.const.PAGE_URI}: <span id="url">{$in->uri}/</span></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset><legend>{$smarty.const.PARENT_PAGE}</legend>
				<table cellspacing="3">
					<tr>
						<td>{$smarty.const.CREATE_PAGE_IN}:</td>
						<td><b>{$in->uri}</b></td>
					</tr>
					<tr>
						<td>{$smarty.const.PARENT_PAGE}:</td>
						<td>{$in->header}</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_CREATE}" id="create" name="create" disabled />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>