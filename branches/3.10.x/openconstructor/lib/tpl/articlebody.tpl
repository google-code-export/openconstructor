<div class="block">
{if $article.header}
	<h2 class="head">{$article.header}</h2>
{/if}
	<div class="blockcont">
	{foreach from=$pages item=p}
		{if $article.image and $article.current_page==1}
		<{$article.image}" title="{$article.header}" alt="{$article.header}" class="mainimg"/>
		{/if}
		<h3>{$p.header}</h3>
		<nobr class="date">{$p.date}</nobr>
		{$p.content}
	{/foreach}
	</div>
</div>