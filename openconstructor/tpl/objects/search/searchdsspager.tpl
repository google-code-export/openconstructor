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
{include file="objects/select_obj.tpl"}
<fieldset><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}" /></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PAGE_ID}:</td>
			<td><input type="text" name="pageNumberKey" value="{$obj->pageNumberKey}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PAGER_SIZE}:</td>
			<td><input type="text" name="pagersize" value="{$obj->pagerSize}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DOCUMENTS_LIST_SIZE_KEY}:</td>
			<td><input type="text" name="listSizeKey" value="{$obj->listSizeKey}"></td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}