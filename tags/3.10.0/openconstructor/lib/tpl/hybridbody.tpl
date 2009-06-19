<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<h3>{$doc.header}</h3>
		<p>{$doc|@debug_print_var}</p>
	</div>
</div>