<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$news item=news}
			<li title="{$news.header}">
				<span class="pubname">
				{if $news.href}
				<a href="{$news.href}" title="{$news.header}">{$news.header}</a>
				{else}
				<b>{$news.header}</b>
				{/if}
				</span>
			</li>
		{/foreach}
		</ul>
	</div>
</div>