<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$messages item=message}
			<li title="{$guestbook.subject}">
				<span class="pubname">{$message.subject}</span>
				<nobr><a href="mailto:{$message.email}">{$message.author}</a> {$message.date}</nobr>
				<p>
				<b>Answer:</b> {$message.content}
				</p>
			</li>
		{/foreach}
		</ul>
	</div>
</div>