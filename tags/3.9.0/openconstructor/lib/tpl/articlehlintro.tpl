<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$articles item=article}
			<li title="{$article.header}">
				{if $article.image}
				<a href="{$article.href}" title="{$article.header}">
				<{$article.image} alt="{$article.header}" title="{$article.header}" class="pubimg"/>
				</a>
				{/if}
				<span class="pubname"><a href="{$article.href}" title="{$article.header}">{$article.header}</a></span>
				<nobr class="date">{$article.date}</nobr>
				{$article.intro} 
				<a href="{$article.href}" class="more" title="{$more}">{$more}</a>
			</li>
		{/foreach}
		</ul>
	</div>
</div>