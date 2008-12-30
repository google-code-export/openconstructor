{include file="objects/header.tpl"}
<script language="JavaScript" type="text/JavaScript">
	var	dis = {if $WCS->decide($obj, 'editobj')}false{else}true{/if};
	var allowAutoLogin = {if $obj->allowAutoLogin}false{else}true{/if};
	{literal}
		var re=new RegExp('[^\\s]','gi');

		$(document).ready(function(){
			$("#close").click(function(){
				window.close();
			});

			$(".dsb").keyup(function(){
				if(!$("#f_name").val().match(re) || !$("#f_description").val().match(re) || dis)
					$("#f_save").attr('disabled',true);
				else
					$("#f_save").attr('disabled',false);
			});
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
		function onRememberMe(ch) {
			this.clicks = this.clicks ? this.clicks + 1 : 1;
			$("#fs_autologin *").attr('disabled',!ch.checked);
			if(this.clicks == 1 && ch.checked && allowAutoLogin)
				$("#f_allowAutoLogin").val(90);
		}
	{/literal}
</script>
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_LOGIN_ID}:</td>
			<td><input type="text" name="loginid" value="{$obj->loginID}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PASSWORD_ID}:</td>
			<td><input type="text" name="passwordid" value="{$obj->passwordID}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DEFAULT_NEXT_PAGE}:</td>
			<td><input type="text" name="defaultNextPage" value="{$obj->defaultNextPage}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_NEXT_PAGE_KEY}:</td>
			<td><input type="text" name="nextPageKey" value="{$obj->nextPageKey}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_LOGIN_PAGE_KEY}:</td>
			<td><input type="text" name="loginPageKey" value="{$obj->loginPageKey}"></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="checkbox" id="ch.autologin" onclick="onRememberMe(this);" {if $obj->allowAutoLogin}checked{/if}>
				<label for="ch.autologin">{$smarty.const.PR_ALLOW_AUTOLOGIN}</label>
				<fieldset id="fs_autologin" style="border: none; padding-left: 20px;">
					<table style="margin:5 0" cellspacing="3">
						<tr>
							<td nowrap>{$smarty.const.PR_AUTOLOGIN_ID}:</td>
							<td><input type="text" name="autoLoginID" value="{$obj->autoLoginID}" {if !$obj->allowAutoLogin}disabled{/if}></td>
						</tr>
						<tr>
							<td nowrap>{$smarty.const.PR_AUTOLOGIN_TIMEOUT}:</td>
							<td><input type="text" name="allowAutoLogin" id="f_allowAutoLogin" value="{$obj->allowAutoLogin}" {if !$obj->allowAutoLogin}disabled{/if}></td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset style="padding:10"><legend>{$smarty.const.PR_DEFAULT_NEXT_PAGES}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr style="font-size: 115%;">
			<td>{$smarty.const.H_GROUP}</td>
			<td>{$smarty.const.H_NEXTPAGE_URI}</td>
		</tr>
		{foreach from=$groups key=id item=title}
			<tr>
				<td nowrap>{$title}:</td>
				<td><input type="text" name="homes[{$id}]" value="{$obj->homes.$id}" size="40" style="font-family: monospace"></td>
			</tr>
		{/foreach}
	</table>
</fieldset>
{include file="objects/footer.tpl"}