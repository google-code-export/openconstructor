<div class="block">
{if $header}
	<h2 class="head">{$header}</h2>
{/if}
	<table cellpadding=0 cellspacing=0 border=0 class="calendar" width="100%">
	<tr class="weeks">
	<td><h4><b>Mo</b></h4></td>
	<td><h4><b>Tu</b></h4></td>
	<td><h4><b>We</b></h4></td>
	<td><h4><b>Th</b></h4></td>
	<td><h4><b>Fr</b></h4></td>
	<td><h4><b>St</b></h4></td>
	<td><h4><b>Su</b></h4></td>
	</tr>
	<tr class="calbreak"><td colspan=7>&nbsp;</td></tr>
	{foreach from=$events item=row}
	    <tr class="calbody">
	    {foreach from=$row item=cell}
            <td align="left" width="14%">
	    	{$cell.day}
	        {foreach from=$cell.events item=event}
	            {if $event}
	                {if $event.day==$smarty.now|date_format:"%d"}
	                    <h5 class="today">{$event.day}</h5>
	                    <div class="today">
	                {elseif $event.day<$smarty.now|date_format:"%d"}
	                    <h5>{$event.day}</h5>
	                    <div class="before">
	                {else}
	                    <h5>{$event.day}</h5>
	                    <div class="after">
	                {/if}
	                {if $event.header}
	                    <a href="{$event.href}" title="{$event.header}">
	                    <{$event.image} alt="{$event.header}" title="{$event.header}"/>
	                    </a>
	                    <span class="pubname">{$event.header}</span>
	                {else}
	                    &nbsp;
	                {/if}
	                </div>
	            {/if}
	        {/foreach}
            </td>
	    {/foreach}
	    </tr>
	    <tr class="calbreak"><td colspan=7>&nbsp;</td></tr>
	{/foreach}
	</table>
</div>