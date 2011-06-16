<div class="block">
{if $header}
	<h1 class="head">{$header}</h1>
{/if}
	<div class="blockcont">
		<h3 class="pubname">{$message.subject}</h3>
		<i><a href="mailto:{$message.email}">{$message.author}</a></i>
		{if $message.date}<nobr class="date">{$message.date}</nobr>{/if}
		{$message.html}
	</div>
</div>