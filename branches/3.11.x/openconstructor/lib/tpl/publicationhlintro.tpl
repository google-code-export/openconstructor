<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
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
				<h4 class="pubname"><a href="{$news.href}" title="{$news.header}">{$news.header}</a></h4>
				{if $news.date}<nobr class="date">{$news.date}</nobr>{/if}
				<div class="intro">
					{$news.intro} 
					<a href="{$news.href}" class="more" title="{$more}">{$more}</a>
				</div>
			</li>
		{/foreach}
		</ul>
	</div>
</div>