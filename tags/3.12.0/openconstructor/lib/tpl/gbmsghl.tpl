<div class="block">
{if $header}
	<h1 class="head">{$header}</h1>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$messages item=message}
			<li class="item" title="{$message.subject}">
				<h5 class="pubname"{if $message.published == 0} style="color: #666666;"{/if}>{$message.subject}</h5>
				<i><a href="mailto:{$message.email}"{if $message.published == 0} style="color: #666666;"{/if}>{$message.author}</a></i> 
				{if $message.date}<nobr class="date">{$message.date}</nobr>{/if}
				{if $message.html}
				<div class="intro">
				{$message.html}
				</div>
				{/if}
				<a href="{$message.href}"{if $message.published == 0} style="color: #666666;"{/if} class="more" title="{$more}">{$more}</a>
			</li>
		{/foreach}
		</ul>
	</div>
</div>