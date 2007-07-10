<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
	{foreach from=$docs item=doc}
		{foreach from=$doc item=props key=id}
		<p><b>{$id}</b> : {$props}</p>
		{/foreach}
	{/foreach}
	</div>
</div>