<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{if is_array($tpl)}{$smarty.const.EDIT_TPLS_AUTHS}{else}{$smarty.const.EDIT_TPL_AUTHS}{/if} | {$tpl->name}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body id="inner_page">
        <script language="JavaScript" type="text/JavaScript">
			var	dis = {if $multiple || $WCS->decide($tpl, 'edittpl.chmod')}false{else}true{/if};
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
		<h3 class="hTitle">{if is_array($tpl)}{$smarty.const.EDIT_TPLS_AUTHS}{else}{$tpl->name}{/if}</h3>
		<form name="f" method="POST" action="i_security.php">
			<input type="hidden" name="action" value="edit_template">
			<input type="hidden" name="tpl_id" value="{$tpl_vars.id}">
			<fieldset><legend>{$smarty.const.H_RES_OWNERS}</legend>
				<table cellspacing="3">
					<tr>
						<td>{$smarty.const.PR_RES_OWNER}:</td>
						{if $multiple}
							<td>
								<input type="checkbox" onclick="$('#f_mltOwner').attr('disabled',!$(this).attr('checked'))" {if $owner}checked{/if}>
								<input type="text" name="owner" id="f_mltOwner" size="32" maxlength="32" {if $owner} value="{$owner->login}"{else}disabled{/if}>
							</td>
						{else}
							<td><input type="text" name="owner" size="32" maxlength="32" value="{if $owner}{$owner->login}{else}???{/if}"></td>
						{/if}
					</tr>
					<tr>
						<td>{$smarty.const.PR_RES_GROUP}:</td>
						{if $multiple}
							<td>
								<input type="checkbox" onclick="$('#f_mltGroup').attr('disabled',!$(this).attr('checked'))" {if $ownerGroup}checked{/if}>
								<input type="text" name="ownerGroup" id="f_mltGroup" size="32" maxlength="32" {if $ownerGroup} value="{$ownerGroup->name}"{else}disabled{/if}>
							</td>
						{else}
							<td><input type="text" name="ownerGroup" size="32" maxlength="32" value="{if $ownerGroup}{$ownerGroup->name}{else}???{/if}"></td>
						{/if}
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset><legend>{$smarty.const.H_AUTHS}</legend>
				<table cellspacing="3" id="auths">
					<tr>
						<td>{$smarty.const.H_ACTION}</td>
						<td>{$smarty.const.H_OWNER_AUTHS}</td>
						<td>{$smarty.const.H_GROUP_AUTHS}</td>
					</tr>
					{if !$multiple}
						{foreach from=$authList item=val}
							<tr>
								<td title="Key: {$val.act}">{$val.title}</td>
								<td align="middle"><input type="checkbox" name="oAuths[{$val.act}]" {if $val.ownerBit}checked{/if}></td>
								<td align="middle"><input type="checkbox" name="gAuths[{$val.act}]" {if $val.groupBit}checked{/if}></td>
							</tr>
						{/foreach}
					{else}
						{foreach from=$authList item=val}
							<tr>
								<td title="Key: {$val.act}">{$val.title}</td>
								<td align="middle">
									<select name="oAuths[{$val.act}]">
										{foreach from=$states key=key item=vl}
											<option value="{$key}" {if $val.ownerStatus eq $key}selected{/if} {if $key eq 0} style="background-color:#eee;"{/if}>{$vl}</option>
										{/foreach}
									</select>
								</td>
								<td align="middle">
									<select name="gAuths[{$val.act}]">
										{foreach from=$states key=key item=vl}
											<option value="{$key}" {if $val.groupStatus eq $key}selected{/if} {if $key eq 0} style="background-color:#eee;"{/if}>{$vl}</option>
										{/foreach}
									</select>
								</td>
							</tr>
						{/foreach}
					{/if}
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