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

			$("#f_cId").keyup(function(){				$("#f_cVal").attr('disabled',!$(this).val().match(/[^\s]+/));
			});
			$("#f_cId").keyup();

			$("input[name='policy']").click(function(){				$("#f_addAs").attr('disabled',!$("input[name='policy']").get(2).checked);
			});
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl" disableCaching = true}
<fieldset {if !$WCS->decide($obj, 'editobj.ds')}disabled{/if}><legend>{$smarty.const.OBJ_DATA}</legend>
	<table cellspacing="3">
		<tr>
			<td nowrap valign="top">{$smarty.const.PR_DATASOURCE}:</td>
			<td>
				{set ds_id=','|@explode:$obj->ds_id}
				{set false = "false"|bool}
				<select size="10" name="ds_id[]" multiple>
					{foreach from=$ds item=val}
						{set sel = $val.id|array_search:$ds_id}
						<option value="{$val.id}" {if $sel !== $false}selected{/if}>{$val.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$smarty.const.PR_DEFINE_GB_DYNAMICALLY_BY_POST}:</td>
			<td><input type="checkbox" onclick='$("#f_gbid").attr("disabled", !this.checked);' {if $obj->gbid}checked{/if}><input type="text" id="f_gbid" name="gbid" value="{if $obj->gbid}{$obj->gbid}{else}subject{/if}" {if !$obj->gbid}disabled{/if} /></td>
		</tr>
		<tr>
			<td valign="top">{$smarty.const.PR_DEFAULT_GB}:</td>
			<td>
				<select size="10" name="defaultgb">
					{foreach from=$ds|@array_keys item=val}
						<option value="{$val}" {if $val eq $obj->defaultGB}selected{/if}>{$ds.$val.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_GB_SUBJECT_ID}:</td>
			<td nowrap><input type="checkbox" name="r_subject" value="true" {if $obj->fields.subject.required}checked{/if}> <input type="text" name="subjectid" value="{$obj->fields.subject.id}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_GB_MESSAGE_ID}:</td>
			<td nowrap><input type="checkbox" name="r_message" value="true" {if $obj->fields.message.required}checked{/if}> <input type="text" name="messageid" value="{$obj->fields.message.id}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_GB_AUTHOR_ID}:</td>
			<td nowrap><input type="checkbox" name="r_author" value="true" {if $obj->fields.author.required}checked{/if}> <input type="text" name="authorid" value="{$obj->fields.author.id}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_GB_EMAIL_ID}:</td>
			<td nowrap><input type="checkbox" name="r_email" value="true" {if $obj->fields.email.required}checked{/if}> <input type="text" name="emailid" value="{$obj->fields.email.id}"></td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset><legend>{$smarty.const.OBJ_CAPTCHA_PROPS}</legend>
	<table cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_CAPTCHA_ID}:</td>
			<td><input type="text" name="cId" id="f_cId" value="{$obj->cId}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_GB_CAPTCHA_VALUE}:</td>
			<td>
				<input type="text" name="cVal" id="f_cVal" value="{$obj->cVal}">
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="closeSess" id="ch.closeSess" value="true" {if $obj->closeSess}checked{/if}> <label for="ch.closeSess">{$smarty.const.PR_CLOSE_SESS}</label></td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset><legend>{$smarty.const.PR_ADDMSG_POLICY}</legend>
	<table cellspacing="3">
		<tr>
			<td colspan="2"><input name="policy" type="radio" {if $obj->policy eq $smarty.const.GBAML_ADD_AS_FROMAUTH}checked{/if} value="{$smarty.const.GBAML_ADD_AS_FROMAUTH}"> {$smarty.const.H_GBAML_ADD_AS_FROMAUTH}</td>
		</tr>
		<tr>
			<td colspan="2"><input name="policy" type="radio" {if $obj->policy eq $smarty.const.GBAML_ADD_AS_DSOWNER}checked{/if} value="{$smarty.const.GBAML_ADD_AS_DSOWNER}"> {$smarty.const.H_GBAML_ADD_AS_DSOWNER}</td>
		</tr>
		<tr>
			<td colspan="2"><input name="policy" type="radio" {if $obj->policy eq $smarty.const.GBAML_ADD_AS_SPECIFIED}checked{/if} value="{$smarty.const.GBAML_ADD_AS_SPECIFIED}"> {$smarty.const.H_GBAML_ADD_AS_SPECIFIED} <input type="text" id="f_addAs" name="addAs" value="{$obj->addAs}" {if !($obj->policy eq $smarty.const.GBAML_ADD_AS_SPECIFIED)}disabled{/if}></td>
		</tr>
		<tr>
			<td colspan="2"><input name="ignoreDsAuths" type="checkbox" {if $obj->ignoreDsAuths}checked{/if}> {$smarty.const.PR_IGNORE_DS_AUTHS}</td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset><legend>{$smarty.const.PR_NOTIFICATION}</legend>
	<table cellspacing="3">
		<tr>
			<td colspan="2"><input id="ch.notifyEmail" type="checkbox"{if $obj->notifyEmail}checked{/if} onclick='$("#f_notifyEmail").attr("disabled", !this.checked);$("#f_mailSubject").attr("disabled", !this.checked);'> <label for="ch.notifyEmail">{$smarty.const.PR_NOTIFY_ON_NEW_MESSAGE}</label></td>
		</tr>
		<tr>
			<td>{$smarty.const.PR_NOTIFY_TO_EMAIL}:</td>
			<td><input type="text" name="notifyEmail" id="f_notifyEmail" size="64" maxlength="128" value="{$obj->notifyEmail}" {if !$obj->notifyEmail}disabled{/if}></td>
		</tr>
		<tr>
			<td>{$smarty.const.PR_MAIL_SUBJECT}:</td>
			<td><input type="text" name="mailSubject" id="f_mailSubject" size="64" maxlength="64" value="{$obj->mailSubject|escape}" {if !$obj->notifyEmail}disabled{/if}></td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}