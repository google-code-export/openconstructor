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
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl"}
<fieldset><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}"></td>
		</tr>
	</table>
	<fieldset id="ex-pages"><legend>{$smarty.const.H_EXCLUDE_PAGES}</legend>
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
					<li id="l{$node.id}" title="{$node.title}">{$node.name}
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