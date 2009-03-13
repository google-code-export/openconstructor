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

			/*$("#ch_gbid").click(function(){
				$("input[name='gbid']").attr("disabled", !$(this).attr("checked"));
			});*/
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl"}
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
			<td>{$smarty.const.PR_DEFINE_GB_DYNAMICALLY_BY_GET}:</td>
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
			<td nowrap>{$smarty.const.PR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}" /></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DATE_FORMAT}:</td>
			<td><input type="text" name="dateformat" value="{$obj->dateFormat|escape}" /></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="reverseorder" value="true" {if $obj->reverseOrder}checked{/if} /> {$smarty.const.PR_SHOW_IN_REVERSE_ORDER}</td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}