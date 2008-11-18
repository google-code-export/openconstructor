{*$objs|@debug_print_var*}
{assign var="ocm_home" value="/openconstructor"}
{assign var="skinhome" value="$ocm_home/i/newskin"}
{assign var="img" value="$skinhome/images"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_OBJECT} {$obj->name|escape}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/js/base.js"></script>
	</head>
	<body style="border-style:groove;padding:0 20 20">
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
		<br />
		<h3>{$smarty.const.EDIT_OBJECT}</h3>
		<form name="f" method="POST" action="i_htmltext.php">
			<input type="hidden" name="action" value="edit_htmltextbody" />
			<input type="hidden" name="obj_id" value="{$obj->obj_id}" />
			<fieldset style="padding:10"><legend>{$smarty.const.OBJECT}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.PR_OBJ_NAME}:</td>
						<td><input type="text" id="f_name" name="name" size="64" maxlength="64" value="{$obj->name|escape}" class="dsb" /></td>
					</tr>
					<tr>
						<td>{$smarty.const.PR_OBJ_DESCRIPTION}:</td>
						<td><textarea cols="51" rows="5" id="f_description" name="description" class="dsb">{$obj->description}</textarea></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10" {if !$WCS->decide($obj, 'editobj.ds')}disabled{/if}><legend>{$smarty.const.OBJ_DATA}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td nowrap><a href="{$ocm_home}/data/?node={$obj->ds_id}" target="_blank" title="{$smarty.const.H_OPEN_DATASOURCE}">{$smarty.const.PR_DATASOURCE}</a>:</td>
						<td>
							<select size="1" name="ds_id">
								{foreach from=$ds item=val}
									<option value="{$val.id}" {if $val.id eq $obj->ds_id}selected{/if}>{$val.name}</option>
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
						<td><input type="text" name="header" value="{$obj->header|escape}"></td>
					</tr>
					<tr>
						<td>{$smarty.const.PR_PAGE_URI}:</td>
						<td>
							<select name="page_id" size="1">
								<option value="0">.</option>
								{foreach from=$pages key=id item=uri}
									<option value="{$id}" {if $id eq $obj->page_id}selected{/if}>{$uri}</option>
								{/foreach}
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="button" value="{$smarty.const.BTN_MANAGE_OBJECT_USES}" style="float: left;" onclick="openObjectUses({$obj->obj_id});">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" id="f_save" name="save" />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>