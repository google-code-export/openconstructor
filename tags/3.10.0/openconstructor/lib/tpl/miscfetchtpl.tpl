<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<p>Block: {$block}</p>
		<p>Args: {$args|@debug_print_var}</p>
	</div>
</div>