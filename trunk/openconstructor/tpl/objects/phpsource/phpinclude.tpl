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

            $("#f_ds_id").change(function(){                $("#f_source").val(0);
                $("form").submit();
			});
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
		function wxyopen(uri, x, y) {
			window.open(uri, '', "resizable=yes, scrollbars=yes, status=yes" + (y > 0 ? ", height=" + y : "") + (x > 0 ? ", width=" + x : ""));
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
			<td nowrap><a href="{$ocm_home}/data/phpsource/edit.php?ds_id={$obj->ds_id}&id={$obj->source}" onclick="wxyopen(this.href,788,520);return false;">{$smarty.const.PR_PHP_SOURCE}</a>:</td>
			<td>
				<select size="1" name="source" id="f_source">
					<option value="0">-</option>
					{foreach from=$sources key=id item=name}
						<option value="{$id}" {if $id eq $obj->source}selected{/if}>{$name|escape}</option>
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
			<td><input type="text" name="header" value="{$obj->header|escape}" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="once" value="true" {if $obj->once}checked{/if}> {$smarty.const.PR_INCLUDE_ONCE}</td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}