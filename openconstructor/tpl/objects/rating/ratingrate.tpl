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
<fieldset><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DOCUMENT_ID}:</td>
			<td><input type="text" name="idKey" value="{$obj->idKey}"></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" {if $obj->no404}checked{/if}> {$smarty.const.PR_NO_404}</td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}