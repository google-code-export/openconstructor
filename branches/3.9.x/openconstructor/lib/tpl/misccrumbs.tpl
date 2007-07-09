<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
	{foreach from=$crumbs key=href item=cr name=cr}
		{if $href != '.'}
			<a href="{$href}" class="crumbs">{$cr|@implode:' &gt; '}</a>
		{else}
			<b>{$cr|@implode:' &gt; '}</b>
		{/if}
		{if !$smarty.foreach.cr.last} &gt; {/if}
	{/foreach}
	</div>
</div>