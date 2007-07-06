<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$pages item=p}
			<li title="{$p.header}">
				{if $p.at == 2}
				<b>{$p.header}</b>
				{elseif $p.at ==1}
				<a href="{$p.href}" title="{$p.header}"><b>{$p.header}</b></a>
				{else}
				<a href="{$p.href}" title="{$p.header}">{$p.header}</a>
				{/if}
			</li>
		{/foreach}
		</ul>
	</div>
</div>