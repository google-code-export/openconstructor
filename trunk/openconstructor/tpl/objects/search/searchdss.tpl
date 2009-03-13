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
		function dsChanged(select) {
			for(i = 0, count = 0; i < select.options.length; i++)
				if(!select.options[i].getAttribute('nohref')) {
					count += select.options[i].selected;
					document.getElementById('hrefs[' + select.options[i].value + ']').disabled = !select.options[i].selected;
					document.getElementById('href' + select.options[i].value).style.display = select.options[i].selected ? '' : 'none';
				}
			document.getElementById('fHrefs').style.display = count > 0 ? '' : 'none';
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
				<select size="10" name="ds_id[]" multiple onchange="dsChanged(this)">
					{foreach from=$ds item=val}
						{set sel = $val.id|array_search:$ds_id}
						<option value="{$val.id}" {if $sel !== $false}selected{/if} {if $val.type eq 'htmltext'} nohref="1"{/if}>{$val.name}</option>
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
			<td nowrap>{$smarty.const.PR_KEYWORD_KEY}:</td>
			<td><input type="text" name="keywordKey" value="{$obj->keywordKey}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DOCUMENTS_LIST_SIZE}:</td>
			<td><input type="text" name="listSize" value="{$obj->listSize}"></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" {if $obj->no404}checked{/if}> {$smarty.const.PR_NO_404}</td>
		</tr>
	</table>
	<fieldset id="fHrefs"><legend>{$smarty.const.PR_DS_HREFS}</legend>
		{foreach from=$ds item=val}
			{set valid = $val.id}
			{set sel = $val.id|array_search:$ds_id}
			<div class="property" {if !($sel !== $false and $val.type neq 'htmltext')} style="display:none"{/if} id="href{$val.id}">
				<span>{$val.name}:</span>
				<input type="text" name="hrefs[{$val.id}]" id="hrefs[{$val.id}]" value="{$obj->hrefs.$valid}" size="64" maxlength="128" {if !($sel !== $false and $val.type neq 'htmltext')}disabled{/if}>
			</div>
		{/foreach}
	</fieldset>
	<br />
</fieldset>
{include file="objects/footer.tpl"}