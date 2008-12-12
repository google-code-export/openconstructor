{include file="objects/header.tpl"}
{literal}
	<style>
	#ex-pages UL {
		margin:0 0 0 30px;
		padding:0;
		list-style-type:none;
		font-size:11px;
		}
	#ex-pages LI {
		margin:2px 0;
		padding:0;
		}
	#ex-pages LI I {
		font-style:normal;
		}
	#ex-pages LI I.selected {
		font-style:italic;
		font-weight:bold;
		}
	</style>
{/literal}
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

			$("#page_id").change(function(){
				$("#ex-pages i.selected").removeClass("selected");
				$("li#l"+$(this).val()+" > i").add($("li#l-"+$(this).val()+" > i")).addClass("selected");
			});
			$("#page_id").change();

			$("#ch_children").click(function(){				if($(this).attr('checked') == true)					$("#ex-pages").show();
				else
					$("#ex-pages").hide();
			});
			$("#ex-pages").hide();
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
	{/literal}
</script>
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
{include file="objects/select_tpl.tpl"}
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_CUT_INTRO}:</td>
			<td><input type="text" name="cutintro" value="{if $obj->cutIntro gt 0}{$obj->cutIntro}{else}0{/if}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_PAGE_MORE_HREF_TEXT}:</td>
			<td><input type="text" name="more" value="{$obj->more}"></td>
		</tr>
		<tr>
			<td>{$smarty.const.PR_PAGE_URI}:</td>
			<td>
				<select name="page_id" id="page_id" size="1">
					<option value="0">.</option>
					{foreach from=$pages key=id item=uri}
						<option value="{$id}" {if $id eq $obj->pageId}selected{/if}>{$uri}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan=2>
				<input type=checkbox name="children" value="true" id="ch_children" {if $obj->children}checked{/if} /> <label for="ch_children">{$smarty.const.PR_PAGE_CHILDREN}</label>
			</td>
		</tr>
	</table>
	<fieldset style="padding:10" id="ex-pages"><legend>{$smarty.const.H_EXCLUDE_PAGES}</legend>
		<div style="padding: 10px;">
			{foreach from=$map key=id item=node}
			{math assign="next" equation="n + 1" n=$id}
			{assign var="nextLev" value=$map.$next.level}
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
					<li id="l{$node.id}" title="{$node.title}"><i>{$node.name}</i>
					{if $nextLev lte $node.level}</li>{/if}
				{/if}
				{assign var="prevLev" value=$node.level}
			{/foreach}
			</ul>
		</div>
	</fieldset>
	<br />
	{literal}
	<script>
		(function () {
			var li = document.getElementById("ex-pages").getElementsByTagName("LI");
			for(var i = 0, id = null; i < li.length; i++) {
				id = parseInt(li[i].id.substr(1));
				li[i].innerHTML = "<input type='checkbox' name='exclude[]' value='" + Math.abs(id) + "' id='ex" + Math.abs(id) + "'> " + li[i].innerHTML;
				if(id < 0)
					document.getElementById("ex" + Math.abs(id)).checked = true;
			}
		})();
	</script>
	{/literal}
</fieldset>
{include file="objects/footer.tpl"}