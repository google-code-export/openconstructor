<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$guestbooks item=guestbook}
			<li title="{$guestbook.header}">
				<span class=pubname""><a href="{$guestbook.href}" title="{$guestbook.header}">{$guestbook.header}</a></span>
				{$guestbook.intro}
			</li>
		{/foreach}
		</ul>
	</div>
</div>