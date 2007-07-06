<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
	{foreach from=$doc item=props key=id}
		<p><b>{$id}</b> : {$props}</p>
	{/foreach}
	</div>
</div>