{if sizeof($pages) > 0}
<div align="center" class="pager">
	{if $first}
	<a href="{$first}"><img src="/images/first.gif" alt="|<" title="|<"/></a>&nbsp;
	{else}
	<img src="/images/first_.gif" alt="|<" title="|<"/>&nbsp;
	{/if}
	{if $prev}
	<a href="{$prev}"><img src="/images/prev.gif" alt="<<" title="<<"/></a>&nbsp;
	{else}
	<img src="/images/prev_.gif" alt="<<" title="<<"/>&nbsp;
	{/if}
	{foreach from=$pages key=p item=href}
	{if $href}
	<a href="{$href}" title="{$p}">{$p}</a>&nbsp;
	{else}
	{$p}&nbsp;
	{/if}
	{/foreach}
	{if $next}
	<a href="{$next}"><img src="/images/next.gif" alt=">>" title=">>"/></a>&nbsp;
	{else}
	<img src="/images/next_.gif" alt=">>" title=">>"/>&nbsp;
	{/if}
	{if $last}
	<a href="{$last}"><img src="/images/last.gif" alt=">|" title=">|"/></a>&nbsp;
	{else}
	<img src="/images/last_.gif" alt=">|" title=">|"/>&nbsp;
	{/if}
</div>
{/if}