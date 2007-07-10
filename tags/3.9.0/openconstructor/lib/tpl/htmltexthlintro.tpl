<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$pages item=p}
			<li title="{$p.header}">
				<b class="pubname"><a href="{$p.href}" title="{$p.header}">{$p.header}</a></b>
				<div class="intro">
					{$p.intro} 
					<a href="{$p.href}" class="more" title="{$more}">{$more}</a>
				</div>
			</li>
		{/foreach}
		</ul>
	</div>
</div>