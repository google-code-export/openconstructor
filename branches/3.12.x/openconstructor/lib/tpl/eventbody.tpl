<div class="block">
	{if $header}
		<h2 class="head">{$header}</h2>
	{/if}
	<div class="blockcont">
	{if $event.image}
		<{$event.image} title="{$event.header}" alt="{$event.header}" class="mainimg"/>
	{/if}
		<h3 class="pubname">{$event.header}</h3>
		<nobr class="date">{$event.begin} - {$event.end}</nobr>
		<div class="place">{$event.place}</div>
		{$event.content}
	</div>
</div>