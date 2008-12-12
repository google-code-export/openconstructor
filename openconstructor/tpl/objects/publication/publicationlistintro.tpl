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

			$("#f_clause").click(function(){
				$("#clause_from").add($("#clause_to")).attr('disabled',!$(this).attr('checked'));
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
			<td><input type="text" name="header" value="{$obj->header|escape}" /></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_CUT_INTRO}:</td>
			<td><input type="text" name="cutintro" value="{if $obj->cutIntro gt 0}{$obj->cutIntro}{else}0{/if}" /></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DATE_FORMAT}:</td>
			<td><input type="text" name="dateformat" value="{$obj->dateFormat|escape}" /></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PUBLICATIONS_PER_PAGE}:</td>
			<td><input type="text" name="pagesize" value="{$obj->pageSize}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PAGE_ID}:</td>
			<td><input type="text" name="pid" value="{$obj->pid}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PUBLICATION_URI}:</td>
			<td><input type="text" name="srvuri" value="{$obj->srvuri}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PUBLICATION_ID}:</td>
			<td><input type="text" name="publicationid" value="{$obj->publicationid}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PUBLICATION_MORE_HREF_TEXT}:</td>
			<td><input type="text" name="more" value="{$obj->more}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_GALLERY_URI}:</td>
			<td><input type="text" name="glruri" value="{$obj->glruri}"></td>
		</tr>
		<tr>
			<td nowrap><input type=checkbox name="clause" id="f_clause" {if $obj->clause}checked{/if} />&nbsp;{$smarty.const.PR_FILTER_BY_HEADERS}:</td>
			<td>{$smarty.const.PR_FILTER_FROM}&nbsp;<input type="text" name="from" id="clause_from" value="{$obj->from}" size="3" maxlength=1 {if !$obj->clause}disabled{/if} />&nbsp;&nbsp;{$smarty.const.PR_FILTER_TO}&nbsp;<input type="text" name="to" id="clause_to" value="{$obj->to}" size="3" maxlength=1 {if !$obj->clause}disabled{/if} /></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="reverseorder" value="true" {if $obj->reverseOrder}checked{/if} /> {$smarty.const.PR_SHOW_IN_REVERSE_ORDER}</td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="ohnemain" value="true" {if $obj->ohneMain}checked{/if} /> {$smarty.const.PR_HIDE_MAIN_PUBLICATION}</td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" {if $obj->no404}checked{/if} /> {$smarty.const.PR_NO_404}</td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}