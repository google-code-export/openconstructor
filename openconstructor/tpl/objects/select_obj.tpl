<fieldset style="padding:10" {if !$WCS->decide($obj, 'editobj.ds')}disabled{/if}><legend>{$smarty.const.OBJ_DEPENDENCIES}</legend>
	<table style="margin:5 0" cellspacing="3" width="100%">
		<tr>
			<td nowrap>{$smarty.const.PR_MASTER_OBJ}:</td>
			<td width="100%">
				<select size="1" name="master_obj">
					{foreach from=$tmp_obj key=obj_id item=val}
						<option value="{$obj_id}" {if $obj_id eq $master}selected{/if}>{$val.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
</fieldset>
<br />