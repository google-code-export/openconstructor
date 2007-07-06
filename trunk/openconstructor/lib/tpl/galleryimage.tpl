<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<{$image.image} alt="{$image.header}" title="{$image.header}" class="mainimg"/>
		<h3 class="pubname">{$image.header}</h3>
		{$image.content}
	</div>
</div>