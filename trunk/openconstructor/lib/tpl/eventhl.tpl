<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$events item=event}
			<li title="{$event.header}">
				<span class="pubname">
				{if $event.href}
				<a href="{$event.href}" title="{$event.header}">{$event.header}</a>
				{else}
				<b>{$event.header}</b>
				{/if}
				</span>
				<nobr class="date">{$event.begin} - {$event.end}</nobr>
				<div class="place">{$e.place}</div>
			</li>
		{/foreach}
		</ul>
	</div>
</div>