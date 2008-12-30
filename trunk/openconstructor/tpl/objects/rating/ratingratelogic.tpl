{include file="objects/header.tpl"}
<script language="JavaScript" type="text/JavaScript">
	var	dis = {if $WCS->decide($obj, 'editobj')}false{else}true{/if};
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

			$("#f_cId").keyup(function(){
				$("#f_cVal").attr('disabled',!$(this).val().match(/[^\s]+/));
			});
			$("#f_cId").keyup();
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl" disableCaching = true}
<fieldset style="padding:10" {if !$WCS->decide($obj, 'editobj.ds')}disabled{/if}><legend>{$smarty.const.OBJ_DATA}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>
				{if $obj->ds_id > 0}
					<a href="{$ocm_home}/data/?node={$obj->ds_id}" target="_blank" title="{$smarty.const.H_OPEN_DATASOURCE}">{$smarty.const.PR_DATASOURCE}</a>:
				{else}
					{$smarty.const.PR_DATASOURCE}:
				{/if}
			</td>
			<td>
				<select size="1" name="ds_id">
					{foreach from=$ds item=val}
						<option value="{$val.id}" {if $val.id eq $obj->ds_id}selected{/if}>{$val.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_RATED_DOC_KEY}:</td>
			<td><input type="text" name="idKey" value="{$obj->idKey}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_RATING_VALUE_KEY}:</td>
			<td><input type="text" name="ratingKey" value="{$obj->ratingKey}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_RATING_COMMENT_KEY}:</td>
			<td><input type="text" name="commentKey" value="{$obj->commentKey}"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="ignoreAuths" id="ch.ignoreAuths" value="true" {if $obj->ignoreAuths}checked{/if}> <label for="ch.ignoreAuths">{$smarty.const.PR_IGNORE_DS_AUTHS}</label></td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_CAPTCHA_PROPS}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_CAPTCHA_ID}:</td>
			<td><input type="text" name="cId" id="f_cId" value="{$obj->cId}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_GB_CAPTCHA_VALUE}:</td>
			<td><input type="text" name="cVal" id="f_cVal" value="{$obj->cVal}"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="closeSess" id="ch.closeSess" value="true" {if $obj->closeSess}checked{/if}> <label for="ch.closeSess">{$smarty.const.PR_CLOSE_SESS}</label></td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset style="padding:10"><legend>{$smarty.const.PR_NOTIFICATION}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td colspan="2"><input id="ch.notifyEmail" type="checkbox" onclick="$('#f_notifyEmail').attr('disabled', !$(this).attr('checked'));$('#f_mailSubject').attr('disabled', !$(this).attr('checked'));" {if $obj->notifyEmail}checked{/if}> <label for="ch.notifyEmail">{$smarty.const.PR_NOTIFY_ON_NEW_MESSAGE}</label></td>
		</tr>
		<tr>
			<td>{$smarty.const.PR_NOTIFY_TO_EMAIL}:</td>
			<td><input type="text" name="notifyEmail" size="64" maxlength="64" id="f_notifyEmail" value="{$obj->notifyEmail}" {if !$obj->notifyEmail}disabled{/if}></td>
		</tr>
		<tr>
			<td>{$smarty.const.PR_MAIL_SUBJECT}:</td>
			<td><input type="text" name="mailSubject" size="64" maxlength="64" id="f_mailSubject" value="{$obj->mailSubject|escape}" {if !$obj->notifyEmail}disabled{/if}></td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}