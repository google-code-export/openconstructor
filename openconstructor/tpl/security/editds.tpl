<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.EDIT_DS_AUTHS} | {$ds->name}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body id="inner_page">
		<script language="JavaScript" type="text/JavaScript">
			var	dis = {if $WCS->decide($ds, 'editds.chmod')}false{else}true{/if};
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
		<h3 class="hTitle">{$ds->name}</h3>
		<form name="f" id="f" method="POST" action="i_security.php">
			<input type="hidden" name="action" value="edit_ds" />
			<input type="hidden" name="ds_id" value="{$ds->ds_id}" />
			<fieldset><legend>{$smarty.const.H_RES_OWNERS}</legend>
				<table cellspacing="3">
					<tr>
						<td>{$smarty.const.PR_RES_OWNER}:</td>
						<td><input type="text" name="owner" size="32" maxlength="32" value="{if $owner}{$owner->login}{else}???{/if}"></td>
					</tr>
					<tr>
						<td>{$smarty.const.PR_RES_GROUP}:</td>
						<td><input type="text" name="ownerGroup" size="32" maxlength="32" value="{if $ownerGroup}{$ownerGroup->name}{else}???{/if}"></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset><legend>{$smarty.const.H_AUTHS}</legend>
				<table cellspacing="3">
					<tr>
						<td>{$smarty.const.H_ACTION}</td>
						<td>{$smarty.const.H_OWNER_AUTHS}</td>
						<td>{$smarty.const.H_GROUP_AUTHS}</td>
					</tr>
					{foreach from=$authList item=val}
						<tr>
							<td title="Key: {$val.act}">{$val.title}</td>
							<td align="middle"><input type="checkbox" name="oAuths[{$val.act}]" {if $val.ownerBit}checked{/if}></td>
							<td align="middle"><input type="checkbox" name="gAuths[{$val.act}]" {if $val.groupBit}checked{/if}></td>
						</tr>
					{/foreach}
				</table>
			</fieldset>
			<br />
			<fieldset><legend>{$smarty.const.H_DOC_AUTHS}</legend>
				<table cellspacing="3">
					<tr>
						<td>{$smarty.const.H_ACTION}</td>
						<td>{$smarty.const.H_OWNER_AUTHS}</td>
					</tr>
					{foreach from=$docList item=val}
						<tr>
							<td title="Key: {$val.act}">{$val.title}</td>
							<td align="middle"><input type="checkbox" name="docAuths[{$val.act}]" {if $val.ownerBit}checked{/if}></td>
						</tr>
					{/foreach}
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_SAVE_CHANGES}" id="f_save" name="save" />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
		<script>dsb();</script>
		<br />
	</body>
</html>