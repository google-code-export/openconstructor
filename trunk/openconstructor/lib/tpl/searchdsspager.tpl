<div class="searchpager">
{if $prev}
<div class="pagertab">
<a href="{$prev}" title="Previous">Previous</a>
</div>
{/if}
{foreach from=$pages key=p item=href}
<div{if !$href} class="curtab"{/if}>
<div class="pagertab">
{if $href}
<a href="{$href}" title="{$p}">{$p}</a>
{else}
{$p}
{/if}
</div>
</div>
{/foreach}
{if $next}
<div class="pagertab">
<a href="{$next}" title="Next">Next</a>
</div>
{/if}
</div>