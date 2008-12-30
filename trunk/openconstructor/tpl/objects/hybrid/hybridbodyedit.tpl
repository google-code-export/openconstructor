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

			$("#f_ds_id").change(function(){
                $("#objFields *").attr('disabled', ($(this).val() != ds_id));
			});
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl"}
<fieldset style="padding:10" {if !$WCS->decide($obj, 'editobj.ds')}disabled{/if}><legend>{$smarty.const.OBJ_DATA}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><a href="{$ocm_home}/data/?node={$obj->ds_id}" target="_blank" title="{$smarty.const.H_OPEN_DATASOURCE}">{$smarty.const.PR_DATASOURCE}</a>:</td>
			<td>
				<select size="1" name="ds_id" id="f_ds_id">
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
			<td nowrap>{$smarty.const.PR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DYNAMIC_DS_ID}:</td>
			<td><input type="text" name="dsIdKey" value="{$obj->dsIdKey}"></td>
		</tr>
		<tr>
			<td nowrap valign="top">{$smarty.const.PR_DOCUMENT_ID}:</td>
			<td><input type="text" name="docId" value="{$obj->docId}"></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" {if $obj->no404}checked{/if}> {$smarty.const.PR_NO_404}</td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset id="objFields" style="padding:10"><legend>{$smarty.const.PR_FETCH_FIELDS}</legend>
	<table class="fieldlist" cellspacing="0">
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="id" disabled checked/></td>
			<td class="sys">id</td><td colspan="2">ID</td>
		</tr>
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="header" disabled checked/></td>
			<td class="sys">header</td><td colspan="2" width="100%">Header</td>
		</tr>
		{foreach name=fields from=$objfields item=val}
			{if $val.ds_name neq $dsid}
				{set dsid = $val.ds_name}
				<tr><td colspan="3" style="padding-top:10px">{$ds.$dsid.name} :</td></tr>
			{/if}
			<tr>
				<td class="f"><input type="checkbox" name="field[][id]" value="{$val.id}" {if $val.checked}checked{/if}></td>
				<td class="sys">{$val.key}</td>
				<td colspan="2"><a href="javascript:wxyopen('../../data/hybrid/field/{$val.family}.php?id={$val.id}',550,430)">{$val.header}</a></td>
			</tr>
		{/foreach}
	</table>
</fieldset>
{include file="objects/footer.tpl"}