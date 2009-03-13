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
<fieldset><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_CALENDAR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_CALENDAR_YEAR}:</td>
			<td><input type="text" name="year" value="{$obj->year}"></td>
		</tr>
		<tr>
			<td nowrap valign=top>{$smarty.const.PR_MONTH_NAMES}:</td>
			{set months=","|join:$obj->months}
			<td><textarea cols="41" rows="5" name="month">{$months|escape}</textarea></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DATE_FORMAT}:</td>
			<td><input type="text" name="dateformat" value="{$obj->dateFormat}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_EVENT_URI}:</td>
			<td><input type="text" name="srvuri" value="{$obj->srvuri}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_EVENT_ID}:</td>
			<td><input type="text" name="eventid" value="{$obj->eventid}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_MONTH_ID}:</td>
			<td><input type="text" name="monthid" value="{$obj->monthid}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_MO_WEEK}</td>
			<td><select name="moWeek">
				<option value="true" {if $obj->moWeek}selected{/if}>{$smarty.const.PR_MO_WEEK_MO}</option>
				<option value="false" {if !$obj->moWeek}selected{/if}>{$smarty.const.PR_MO_WEEK_SU}</option>
			</select></td>
		</tr>
	</table>
</fieldset>
{include file="objects/footer.tpl"}