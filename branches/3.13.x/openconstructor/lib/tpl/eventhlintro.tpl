<div class="noblock">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
	{foreach from=$events item=e}
		{if $e.image}
		<a href="{$e.href}" title="{$e.header}">
		<{$e.image} alt="{$e.header}" title="{$e.header}" class="pubimg"/>
		</a>
		{/if}
		<h3 class="pubname">{$e.header}</h3>
		<nobr class="date">{$e.begin} - {$e.end}</nobr>
		<div class="place">{$e.place}</div>
		<div class="intro">
			{$e.intro}
		</div>
	{/foreach}
	</div>
</div>