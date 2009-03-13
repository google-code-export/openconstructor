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
			<td nowrap><a href="{$ocm_home}/data/?node={$obj->ds_id}" target="_blank" title="{$smarty.const.H_OPEN_DATASOURCE}">{$smarty.const.PR_DATASOURCE}</a>:</td>
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
			<td>{$smarty.const.PR_PAGE_URI}:</td>
			<td>
				<select name="page_id" size="1">
					<option value="0">.</option>
					{foreach from=$pages key=id item=uri}
						<option value="{$id}" {if $id eq $obj->page_id}selected{/if}>{$uri}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}