<div class="pager">
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
	{foreach from=$pages item=href key=p name=for}
	{if $href}
	<span><a href="{$href}">{$p}</a></span>
	{else}
	<span class="selected">{$p}</span>
	{/if}
	{/foreach}
	{if $next}
	&nbsp;&nbsp;<a href="{$next}"><img src="/images/next.gif" alt=">>" title=">>"/></a>&nbsp;
	{else}
	&nbsp;&nbsp;<img src="/images/next_.gif" alt=">>" title=">>"/>&nbsp;
	{/if}
	{if $last}
	<a href="{$last}"><img src="/images/last.gif" alt=">|" title=">|"/></a>&nbsp;
	{else}
	<img src="/images/last_.gif" alt=">|" title=">|"/>&nbsp;
	{/if}
</div>