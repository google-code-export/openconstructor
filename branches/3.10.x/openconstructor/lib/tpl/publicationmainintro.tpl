<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
	{if $news.image}
		<a href="{$news.href}" title="{$news.header}">
		<{$news.image} alt="{$news.header}" title="{$news.header}" class="pubimg"/>
		</a>
	{/if}
		<h3 class="pubname"><a href="{$news.href}" title="{$news.header}">{$news.header}</a></h3>
		{if $news.date}<nobr class="date">{$news.date}</nobr>{/if}
		<div class="intro">
		{$news.intro}
		</div>
		<a href="{$news.href}" class="more" title="{$more}">{$more}</a>
	</div>
</div>