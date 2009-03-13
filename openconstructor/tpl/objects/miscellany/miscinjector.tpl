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
		function addInjection() {
			var sample = document.getElementById('sample');
			var inj = sample.cloneNode(true);
			sample.parentNode.appendChild(inj);
			inj.style.display = 'block';
			return inj;
		}
		function removeInjection(button) {
			var inj = button.parentNode.parentNode.parentNode.parentNode.parentNode;
			inj.parentNode.removeChild(inj);
		}
		function setInjection(inj, type, param, src, srcId, field) {
			var inputs = inj.getElementsByTagName('INPUT');
			var selects = inj.getElementsByTagName('SELECT');
			selects[0].selectedIndex = type;
			inputs[0].value = param;
			inputs[0].onchange();
			selects[1].selectedIndex = src;
			inputs[1].value = srcId;
			inputs[2].value = field;
		}
	{/literal}
</script>
<fieldset><legend>{$smarty.const.OBJ_INJECTIONS}</legend>
	<div>
		<fieldset id="sample" class="fldsFilters">
			<legend class="fwd"></legend>
			<table cellspacing="3" width="100%">
				<tr class="trInjections">
					<td>&nbsp;</td>
					<td>{$smarty.const.H_INJECT_SRC}</td>
					<td>{$smarty.const.H_INJECT_SRC_PARAM}</td>
					<td>&nbsp;</td>
					<td rowspan="4" valign="top"><img src="{$img}/h/remove.gif" class="rmCond" onclick="removeInjection(this)" alt="{$smarty.const.BTN_REMOVE_INJECTION}"></td>
				</tr>
				<tr>
					<td class="tdCond">{$smarty.const.H_INJECT}</td>
					<td>
						<select size="1" name="type[]">
							<option value="{$smarty.const.INJ_CTX}">Context</option>
							<option value="{$smarty.const.INJ_GET}">GET</option>
							<option value="{$smarty.const.INJ_POST}">POST</option>
							<option value="{$smarty.const.INJ_COOKIE}">Cookie</option>
							<option value="{$smarty.const.INJ_SESSION}">Session</option>
							<option value="{$smarty.const.INJ_VALUE}">Value</option>
						</select>
					</td>
					<td colspan="2" width="100%"><input type="text" name="param[]" size="35" onchange="$(this).parents('fieldset:first').children('legend:first').html($(this).val());"></td>
				</tr>
				<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
				<tr class="trInjections">
					<td>&nbsp;</td>
					<td nowrap>{$smarty.const.H_INJECT_DEST_TYPE}</td>
					<td nowrap>{$smarty.const.H_INJECT_DEST_ID}</td>
					<td>{$smarty.const.H_INJECT_DEST_FIELD}</td>
				</tr>
				<tr>
					<td class="tdCond">{$smarty.const.H_INJECT_TO}</td>
					<td>
						<select size="1" name="src[]">
							<option value="{$smarty.const.INJ_BY_ID}">{$smarty.const.H_INJECT_DEST_OBJECT}</option>
							<option value="{$smarty.const.INJ_BY_BLOCK}">{$smarty.const.H_INJECT_DEST_BLOCK}</option>
						</select>
					</td>
					<td><input type="text" size="10" name="srcId[]"></td>
					<td><input type="text" name="field[]"></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="button" value="{$smarty.const.BTN_ADD_INJECTION}" onclick="addInjection()">
	<script>
		{foreach from=$obj->jobs item=job}
			{math assign="type" equation="val % 10" val=$job[0]}
			{math assign="src" equation="val / 10 - 1" val=$job[0]}
			setInjection(addInjection(), {$type}, '{$job[3]}', {$src}, '{$job[1]}', '{$job[2]}');
		{/foreach}
	</script>
</fieldset>
{include file="objects/footer.tpl"}