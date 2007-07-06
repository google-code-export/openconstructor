<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$articles item=article}
			<li title="{$article.header}">
				<span class="pubname">
				{if $article.href}
				<a href="{$article.href}" title="{$article.header}">{$article.header}</a>
				{else}
				<b>{$article.header}</b>
				{/if}
				</span>
			</li>
		{/foreach}
		</ul>
	</div>
</div>