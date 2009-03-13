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
	</table>
</fieldset>
<br />