<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<ul>
		<li>
			{if $all}
			<a href="{$all}">All pages</a>
			{else}
			<b>All pages</b>
			{/if}
		</li>
		{foreach from=$pages key=header item=href}
		<li>
			{if $href.href}
			<a href="{$href.href}" title="{$href.header}">{$href.header}</a>
			{else}
			<b>{$href.header}</b>
			{/if}
		</li>
	{/foreach}
	</ul>
</div>