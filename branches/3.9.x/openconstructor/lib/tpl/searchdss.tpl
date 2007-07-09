{if $keyword}
<div class="searchbar">
	<form method="GET" action=".">
		<input type="text" name="keyword" class="keywords" value="{$keyword}">
		<input type="submit" value="Search Site">
	</form>
</div>
<div class="block">
	{if $header}
		<h2>{$header}</h2>
	{/if}
	<div class="blockcont">
	{if $found>0}
		<div>
			Result of <b>{$offset+1} - 
			{if ($offset+$size)<$found} {$offset+$size}
			{else} {$found} {/if}
			</b> of about <b>{$found}</b> for "<b>{$keyword}</b>"
		</div>
		{foreach from=$results item=res}
		<h2 class="searchtitle">
		<a href="{$res.href}" title="{$res.title}">{$res.title}</a>
		</h2>
		<b>...</b> {$res.annotation} <b>...</b>
		<div class="searchlink">
		<a href="{$res.href}" title="{$res.title}">http://{$smarty.server.SERVER_NAME}{$res.href}</a>
		</div>
		{/foreach}
	{else}
		<div>Your search - "<b>{$keyword}</b>" - did not match any documents.</div>
		<p><b>Suggestions:</b></p>
		1. Make sure all words are spelled correctly.<br><br>
		2. Try different keywords.<br><br>
		3. Try more general keywords.<br><br>
		4. Try fewer keywords.<br><br>
	{/if}
	</div>
</div>
{/if}
<div class="searchbar">
	<form method="GET" action=".">
		<input type="text" name="keyword" class="keywords" value="{$keyword}">
		<input type="submit" value="Search Site">
	</form>
</div>
