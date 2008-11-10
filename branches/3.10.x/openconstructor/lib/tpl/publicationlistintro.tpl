<div class="block">
{if $header}
	<h2 class="head">{$header} ({$from} - {$to})</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$news item=news}
			<li title="{$news.header}">
			{if $news.image}
				<a href="{$news.href}" title="{$news.header}">
				<{$news.image} alt="{$news.header}" title="{$news.header}" class="pubimg"/>
				</a>
			{/if}
				<span class="pubname"><a href="{$news.href}" title="{$news.header}">{$news.header}</a></span>
				{if $news.date}<nobr class="date">{$news.date}</nobr>{/if}
				<div class="intro">
				{$news.intro}
				</div>
				<a href="{$news.href}" class="more" title="{$more}">{$more}</a>
			</li>
		{/foreach}
		</ul>
	</div>
</div>