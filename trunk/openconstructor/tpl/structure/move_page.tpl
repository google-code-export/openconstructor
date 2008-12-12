<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.H_MOVE_PAGE}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body style="border-style:groove;padding:0 20 20">
		<script>
			{literal}
				$(document).ready(function(){
					$("#close").click(function(){
						window.close();
					});

					$("#dest_id").change(function(){
						if($(this).attr('selectedIndex') != -1){							$("#uri").text($(this.options[this.selectedIndex]).attr('uri'));
							if(!$(this.options[this.selectedIndex]).attr('current'))
								$("#create").attr('disabled', false);
							else
								$("#create").attr('disabled', true);
						}
					});
				});
			{/literal}
		</script>
		<br />
		<h3>{$smarty.const.H_MOVE_PAGE}</h3>
		<form name="f" method="POST" action="i_structure.php">
			<input type="hidden" name="action" value="move_page">
			<input type="hidden" name="uri_id" value="{$page->id}">
			<fieldset style="padding:10"><legend>{$smarty.const.PAGE_WEB}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.PAGE_NAME}:</td>
						<td><span style="font-size: 120%;">{$page->header}</span></td>
					</tr>
					<tr>
						<td>{$smarty.const.PAGE_URI_CURRENT}:</td>
						<td><span style="font-family: monospace; font-size: 120%;">{$page->uri}</span></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.MOVE_TO}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.MOVE_TO}:</td>
						<td>
							<select name="dest_id" size="1" id="dest_id">
								{foreach from=$map key=key item=title}
									{math assign="tabs" equation="(x - y - 1) * 6" x=$title|count_characters y=$title|replace:"/":''|count_characters}
									<option value="{$key}" uri="{$title}" {if $key eq $page->parent}current="1" style="color:#888;"{/if}>{$node[$key]->header|indent:$tabs:"&nbsp;"}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td>{$smarty.const.PAGE_URI_NEW}:</td>
						<td style="font-family:monospace"><span id="uri"></span><b>{$page->name}</b>/</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_MOVE}" id="create" name="create" {if $disabled}disabled{/if} />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>