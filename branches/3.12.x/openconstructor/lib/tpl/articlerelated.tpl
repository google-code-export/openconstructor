<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$articles item=article}
			<li>
				<span class="pubname">
				{if $article.href}
				<a href="{$article.href}" title="{$article.header}">{$article.header}</a>
				{/if}
				</span>
			</li>
		{/foreach}
		</ul>
	</div>
</div>