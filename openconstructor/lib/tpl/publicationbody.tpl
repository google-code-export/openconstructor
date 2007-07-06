<div class="block">
	{if $header}
		<h2 class="head">{$header}</h2>
	{/if}
	<div class="blockcont">
	{if $news.image}
		<{$news.image} title="{$news.header}" alt="{$news.header}" class="mainimg"/>
	{/if}
		<h3 class="pubname">{$news.header}</h3>
		{if $news.date}<nobr class="date">{$news.date}</nobr>{/if}
		{$news.content}
	</div>
</div>