<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{if $multiple}{$smarty.const.EDIT_DOCS_AUTHS}{else}{$smarty.const.EDIT_DOC_AUTHS} | {$doc->header}{/if}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body id="inner_page">
		<script language="JavaScript" type="text/JavaScript">
			var	dis = {if $Authentication->getUserId() eq $smarty.const.WCS_ROOT_ID}false{else}true{/if};
			{literal}
				$(document).ready(function(){
					$("#close").click(function(){
						window.close();
					});
				});
				function dsb(){
					if(dis)
						$("#f_save").attr('disabled',true);
					else
						$("#f_save").attr('disabled',false);
				}
			{/literal}
		</script>
		<h3 class="hTitle">{if $multiple}{$smarty.const.EDIT_DOCS_AUTHS}{else}{$doc->header}{/if}</h3>
		<form name="f" id="f" method="POST" action="i_security.php">
			<input type="hidden" name="action" value="edit_doc" />
			<input type="hidden" name="ds_id" value="{$ds->ds_id}" />
			<input type="hidden" name="doc_id" value="{$tpl_vars.id}" />
			<fieldset><legend>{$smarty.const.H_RES_OWNERS}</legend>
				<table cellspacing="3">
					<tr>
						<td>{$smarty.const.PR_RES_OWNER}:</td>
						<td><input type="text" name="owner" size="32" maxlength="32" value="{if $owner}{$owner->login}{elseif !$owner and !$multiple}???{/if}"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_SAVE_CHANGES}" id="f_save" name="save" />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
		<script>dsb();</script>
	</body>
</html>