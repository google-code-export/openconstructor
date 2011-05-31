<div class="block">
	{if $header}
		<h2 class="head">{$header}</h2>
	{/if}
	<div class="blockcont">
	{foreach_row from=$files item=file cols=2}
		{foreach_cell}
			<h3 class="pubname">
				<a href="{$file.href}" title="{$file.header}">{$file.header}</a>
			</h3>
			<div class="intro">
				{$file.annotation}
			</div>
		{/foreach_cell}
	{/foreach_row}
	</div>
</div>