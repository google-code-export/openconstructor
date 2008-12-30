{include file="objects/header.tpl"}
<script language="JavaScript" type="text/JavaScript">
	var	dis = {if $WCS->decide($obj, 'editobj')}false{else}true{/if};
	var ds_id = {$obj->ds_id};
	var	host='{$host}', skin='newskin';
	var create_doc='{$smarty.const.H_CREATE_DOC}', add_doc='{$smarty.const.H_ADD_DOC}', remove_doc='{$smarty.const.H_REMOVE_DOC}';
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
		function addCondition() {
			var sample = document.getElementById('sample');
			var cond = sample.cloneNode(true);
			sample.parentNode.appendChild(cond);
			cond.style.display = 'block';
			return cond;
		}
		function removeCondition(button) {
			var cond = button.parentNode.parentNode.parentNode.parentNode.parentNode;
			cond.parentNode.removeChild(cond);
		}
		function setCondition(cond, type, field, src, value, invert) {
			var inputs = cond.getElementsByTagName('INPUT');
			var selects = cond.getElementsByTagName('SELECT');
			inputs[0].value = field;
			inputs[0].onchange();
			selects[0].selectedIndex = type;
			selects[1].selectedIndex = src;
			inputs[1].checked = invert;
			inputs[1].onclick();
			inputs[3].value = value;
		}
		function fieldClicked(id) {
			var ch = document.getElementById("ch.f" + id);
			var inp = document.getElementById("ep" + id);
			if(inp) {
				inp.disabled = !ch.checked;
				document.getElementById("trep" + id).style.display = ch.checked ? '' : 'none';
			}
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl"}
<fieldset style="padding:10" {if !$WCS->decide($obj, 'editobj.ds')}disabled{/if}><legend>{$smarty.const.OBJ_DATA}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_DATASOURCE}:</td>
			<td>
				<select size="1" name="ds_id" id="f_ds_id">
					{foreach from=$ds item=val}
						<option value="{$val.id}" {if $val.id eq $obj->ds_id}selected{/if}>{$val.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><br><input type="checkbox" id="ch.onlySub" name="onlySub" value="true" {if $obj->onlySub}checked{/if}> <label for="ch.onlySub">{$smarty.const.PR_REQUIRE_SUB_DS}</label></td>
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
			<td nowrap>{$smarty.const.PR_DOCUMENT_ID}:</td>
			<td><input type="text" name="docId" value="{$obj->docId}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DOCUMENT_ID_FIELD}:</td>
			<td>
				<select name="idField" size="1">
					<option value="0" style="background:#eee;">{$smarty.const.H_SYS_ID_FIELD}</option>
					{foreach from=$idFields key=id item=header}
						<option value="{$id}" {if $id eq $obj->idField}selected{/if}>{$header}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_HL_URI}:</td>
			<td><input type="text" name="browseUri" value="{$obj->browseUri}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_NODE_ID}:</td>
			<td><input type="text" name="nodeId" value="{$obj->nodeId}"></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="_pma" value="true" {if $pma}checked{/if}> {$smarty.const.PR_GROUP_PRIMITIVES_AS_ARRAYS}</td>
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
				<tr {if !$smarty.foreach.fields.first} class="f"{/if}><td colspan="4" style="padding-top:10px">{$ds.$dsid.name} :</td></tr>
			{/if}
			<tr {if !$smarty.foreach.fields.first} class="f"{/if}>
				<td class="f"><input type="checkbox" name="field[{$val.id}][id]" value="{$val.id}" onclick="fieldClicked({$val.id})" id="ch.f{$val.id}" {if $val.checked}checked{/if}></td>
				<td class="sys">{$val.key}</td>
				<td colspan="2"><a href="javascript:wxyopen('../../data/hybrid/field/{$val.family}.php?id={$val.id}',550,430)">{$val.header}</a></td>
			</tr>
			{if $val.rating}
				<tr class="extraprop" id="trep{$val.id}">
					<td colspan="2">&nbsp;</td>
					<td>{$smarty.const.H_RATING_PERIOD}:</td>
					<td><input type="text" size="40" name="field[{$val.id}][range]" value="{$val.rating|escape}" id="ep{$val.id}" disabled></td>
				</tr>
			{/if}
			<script>fieldClicked({$val.id});</script>
		{/foreach}
	</table>
</fieldset>
<br />
<fieldset style="padding:10" id="objFilters"><legend>{$smarty.const.OBJ_FILTERS}</legend>
	<div>
		<fieldset id="sample" style="margin:20px 0 30px;display:none;"><legend class="test" style="font-weight:bold;"></legend>
			<table style="margin:5 0" cellspacing="3" width="100%">
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_COND_WHERE}</td>
					<td colspan="2" width="100%">
						<input name="filter[]" type="text" style="margin-top:5px;" onchange="$(this).parents('fieldset:first').children('legend:first').html($(this).val());">
					</td>
					<td rowspan="3" valign="top"><img src="{$img}/h/remove.gif" style="cursor:pointer;margin-right:10px;" onclick="removeCondition(this)" alt="{$smarty.const.BTN_REMOVE_CONDITION}"></td>
				</tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_COND}</td>
					<td>
						<select size="1" name="type[]">
							<option value="{$smarty.const.COND_EQ}">Equal</option>
							<option value="{$smarty.const.COND_BTW}">Between</option>
							<option value="{$smarty.const.COND_GT}">Greater than</option>
							<option value="{$smarty.const.COND_LT}">Less than</option>
							<option value="{$smarty.const.COND_GTEQ}">Greater than or equal</option>
							<option value="{$smarty.const.COND_LTEQ}">Less than or equal</option>
							<option value="{$smarty.const.COND_CONTAINS}">Contains</option>
							<option value="{$smarty.const.COND_LIKE}">Like</option>
						</select>
						<input type="checkbox" onclick="$(this).next().val($(this).attr('checked'));"><input type="hidden" name="invert[]" value="false"> {$smarty.const.H_INVERT_COND}
					</td>
				</tr>
				<tr><td colspan="2" style="font-size:2px;">&nbsp;</td></tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_COND_VALUE}</td>
					<td>
						<select size="1" name="src[]">
							<option value="{$smarty.const.VALUE_CTX}">Context</option>
							<option value="{$smarty.const.VALUE_PLAIN}">Plain</option>
						</select>
						<input type="text" name="value[]">
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="button" value="{$smarty.const.BTN_ADD_CONDITION}" onclick="addCondition()">
	<script>
		{foreach from=$condition item=val}
			setCondition(addCondition(), {$val.type}, '{$val.name|escape:javascript}', {$val.src}, '{$val.value|escape:javascript}', {$val.invert});
		{/foreach}
	</script>
</fieldset>
{include file="objects/footer.tpl"}