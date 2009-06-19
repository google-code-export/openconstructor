<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		{foreach from=$tree item=node}
		{if $node.id > 1}
		{if $node.level > $prevLev}
		<ul>
		{elseif $node.level < $prevLev}
		</ul>
		{/if}
		<li>
			{if $node.at == 1}
				<b>{$node.header}</b>
			{elseif $node.at == 2}
				<b><a href="{$node.href}" title="{$node.header}">{$node.header}</a></b>
			{else}
				<a href="{$node.href}" title="{$node.header}">{$node.header}</a>
			{/if}
		</li>
		{/if}
		{assign var="prevLev" value=$node.level}
		{/foreach}
		</ul>
	</div>
</div>