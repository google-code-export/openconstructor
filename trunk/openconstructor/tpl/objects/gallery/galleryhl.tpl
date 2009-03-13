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

		function setdynamic(stat){			$("select[name='ds_id[]']").get(0).disabled = !($("input[name='dyn_ds_id']").get(0).disabled = !stat);
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl"}
<fieldset><legend>{$smarty.const.PR_DATASOURCES}</legend>
	<table cellspacing="3">
		<tr>
			<td nowrap valign="top"><input type=radio checked name=dynamic_ds value="false" onclick="setdynamic(!this.checked)"> {$smarty.const.PR_STATIC_GALLERIES}:</td>
			<td>
				{set ds_id=','|@explode:$obj->ds_id}
				{set false = "false"|bool}
				<select size="10" name="ds_id[]" multiple {if $obj->dynamic_ds}disabled{/if}>
					{foreach from=$ds item=val}
						{set sel = $val.id|array_search:$ds_id}
						<option value="{$val.id}" {if $sel !== $false}selected{/if}>{$val.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td nowrap valign="top"><input type=radio name=dynamic_ds value="true" onclick="setdynamic(this.checked)" {if $obj->dynamic_ds}checked{/if}> {$smarty.const.PR_PHOTO_PUBLICATION_ID}:</td>
			<td><input type="text" name="dyn_ds_id" value="{if $obj->dynamic_ds}{$obj->ds_id}{else}publication_id{/if}" {if !$obj->dynamic_ds}disabled{/if}></td>
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
			<td nowrap>{$smarty.const.PR_KEYWORD_KEY}:</td>
			<td><input type="text" name="keywordKey" value="{$obj->keywordKey}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_IMAGES_PER_PAGE}:</td>
			<td><input type="text" name="pagesize" value="{$obj->pageSize}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_CUT_NAMES}:</td>
			<td><input type="text" name="cutintro" value="{if $obj->cutIntro gt 0}{$obj->cutIntro}{else}0{/if}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PAGE_ID}:</td>
			<td><input type="text" name="pid" value="{$obj->pid}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_IMAGE_URI}:</td>
			<td><input type="text" name="srvuri" value="{$obj->srvuri}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_IMAGE_ID}:</td>
			<td><input type="text" name="imageid" value="{$obj->imageid}"></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" {if $obj->no404}checked{/if} /> {$smarty.const.PR_NO_404}</td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}