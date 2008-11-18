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
{literal}
<style>
#sitemap UL {
	margin: 0 0 0 30px;
	padding: 0;
	list-style-type: none;
	font-size: 11px;
}
#sitemap LI {
	margin: 2px 0;
	padding: 0;
}
#sitemap LI I {
	font-style: normal;
}
#sitemap LI I.selected {
	font-style: italic;
	font-weight: bold;
}
{/literal}
</style>
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
			<input type="hidden" name="action" value="edit_htmltexthl" />
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
			<fieldset style="padding:10"><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td nowrap>{$smarty.const.PR_HEADER}:</td>
						<td><input type="text" name="header" value="{$obj->header|escape}"></td>
					</tr>
					<tr>
						<td>{$smarty.const.PR_PAGE_URI}:</td>
						<td>
							<select name="page_id" size="1" onchange="markLi(this.options[this.selectedIndex].value);">
								<option value="0">.</option>
								{foreach from=$pages key=id item=uri}
									<option value="{$id}" {if $id eq $obj->pageId}selected{/if}>{$uri}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td nowrap>{$smarty.const.PR_PAGE_FETCH_LEVEL}:</td>
						<td><input type="text" name="level" value="{$obj->level}"></td>
					</tr>
				</table>
				<fieldset style="padding:10" id="sitemap"><legend>{$smarty.const.H_EXCLUDE_PAGES}</legend>
					<div style="padding: 10px;">
						{foreach from=$map key=i item=node}
							{if $node.level eq 1}
								<div style="padding: 5px 0"><b>{$node.name}</b></div>
								<ul>
							{else}
								{if $node.level gt $prevLev && $prevLev neq 1}
									<ul>
								{elseif $node.level lt $prevLev}
									{math assign="i" equation="pl - nl" nl=$node.level pl=$prevLev}
									{section name=cycle loop=$i}
											</ul>
										</li>
									{/section}
								{/if}
								<li id="{$node.id}" title="{$node.title}"><i>{$node.name}</i>
							{/if}
							{assign var="prevLev" value=$node.level}
						{/foreach}
						</ul>
					</div>
				</fieldset>
				<br />
				{*<script>
					(function () {
						var li = document.getElementById("sitemap").getElementsByTagName("LI");
						for(var i = 0, id = null; i < li.length; i++) {
							id = parseInt(li[i].id.substr(1));
							li[i].innerHTML = "<input type='checkbox' name='exclude[]' value='" + Math.abs(id) + "' id='ex" + Math.abs(id) + "'> " + li[i].innerHTML;
							if(id < 0)
								document.getElementById("ex" + Math.abs(id)).checked = true;
						}
					})();
				</script>*}
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="button" value="{$smarty.const.BTN_MANAGE_OBJECT_USES}" style="float: left;" onclick="openObjectUses({$obj->obj_id});">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" id="f_save" name="save" />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
		<script>
			f.page_id.onchange();
			dsb();
		</script>
	</body>
</html>