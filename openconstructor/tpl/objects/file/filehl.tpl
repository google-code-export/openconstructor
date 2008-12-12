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
{include file="objects/select_data.tpl"}
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_OFFSET}:</td>
			<td><input type="text" name="listOffset" value="{if $obj->listOffset}{$obj->listOffset}{else}0{/if}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_FILES_PER_PAGE}:</td>
			<td><input type="text" name="listSize" value="{$obj->listSize}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_KEYWORD_KEY}:</td>
			<td><input type="text" name="keywordKey" value="{$obj->keywordKey}"></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" {if $obj->no404}checked{/if} /> {$smarty.const.PR_NO_404}</td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset style="padding:10" id="objDocOrder"><legend>{$smarty.const.OBJ_DOC_ORDER}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td>{$smarty.const.H_ORDER_LIST_BY}</td>
			<td>
				{math assign="order" equation=abs(x) x=$obj->order}
				<select name="order">
					{foreach from=$fields key=id item=name}
						<option value="{$id}" {if $id eq $order}selected{/if}>{$name}</option>
					{/foreach}
				</select>
			</td>
			<td>
				<select name="reverse">
					<option value="1" {if $obj->order gt 0}selected{/if}>{$smarty.const.PR_FILE_SORT_ASC}</option>
					<option value="-1" {if $obj->order lt 0}selected{/if}>{$smarty.const.PR_FILE_SORT_DESC}</option>
				</select>
			</td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}