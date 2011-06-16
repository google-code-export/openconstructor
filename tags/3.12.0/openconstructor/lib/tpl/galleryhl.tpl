<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<div class="blockcont">
		<ul>
		{foreach from=$images item=image}
			<li title="{$image.header}">
				{if $image.image}
				<a href="{$image.href}" title="{$image.header}">
				<{$image.image} alt="{$image.header}" title="{$image.header}" class="pubimg"/>
				</a>
				{/if}
				<span class="pubname"><a href="{$image.href}" title="{$image.header}">{$image.header}</a></span>
			</li>
		{/foreach}
		</ul>
	</div>
</div>