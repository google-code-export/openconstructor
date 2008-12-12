<script language="JavaScript" type="text/JavaScript">
	var	ds_type = '{$obj->ds_type}', obj_type = '{$obj->obj_type}';
	var cached_by_WC = {if !$obj->cached_by_WC}true{else}false{/if};
	var searchdss = {if $obj->obj_type eq 'searchdss'}true{else}false{/if};
	var disableCaching = {if $disableCaching}true{else}false{/if};
	{literal}
		$(document).ready(function(){			$("#link_tpl_id").click(function(){				if($("#f_tpl_id").val() > 0)
					wxyopen('../../templates/edit.php?dstype='+ds_type+'&id='+$("#f_tpl_id").val(), 660);
				return false;			});

			$("#btn_tpl_id").click(function(){				wxyopen('../../templates/edit.php?dstype='+ds_type+'&type='+obj_type+'&id=new&select=1&header=' + encodeURIComponent($("#f_name").val()), 660);
				return false;
			});

			$("#enableCaching").click(function(){
				$("#fCaching *").attr('disabled',!$(this).attr('checked'));
				if($("#f_byWC").attr('checked'))
					$("#cacheLifetime").attr('disabled',$("#f_byWC").attr('checked'));
			});

			$("#f_byWC").click(function(){				$("#cacheLifetime").attr('disabled',$(this).attr('checked'));
			});

			$("#f_time").click(function(){
				$("#cacheLifetime").attr('disabled',!$(this).attr('checked'));
			});

			if(cached_by_WC){				$("#f_time").attr('checked',true);				$("#cacheLifetime").attr('disabled',false);
			}

			if(searchdss){				$("#f_byWC").attr('disabled',true);
				$("#f_time").attr('checked',true);
				$("#cacheLifetime").attr('disabled',false);
			}

			if(disableCaching){				$("#f_tpl_args").attr('disabled',true);				$("#enableCaching").attr('disabled',true);
				$("#fCaching *").attr('disabled',true);
			}
		});

		function wxyopen(uri,x,y) {
			window.open(uri,'',"resizable=yes, scrollbars=yes, status=yes" + (y > 0 ? ", height=" + y : "") + (x > 0 ? ", width=" + x : ""));
		}
		function addAndSelectTpl(id, name) {
			var s = $("#f_tpl_id").get(0);
			var o = new Option();
			o.innerHTML = name;
			o.value = id;
			s.appendChild(o);
			o.selected = true;
		}
	{/literal}
</script>

<fieldset style="padding:10" {if !$WCS->decide($obj, 'editobj.tpl')}disabled{/if}>
	<legend>{$smarty.const.OBJ_TEMPLATE}</legend>
	<table style="margin:5 0" cellspacing="3" width="100%">
		<tr>
			<td nowrap>
				{if $obj->tpl}
					<a href="/tpl [id = {$obj->tpl}]" id="link_tpl_id">{$smarty.const.PR_SMARTY_TEMPLATE}</a>
				{else}
					{$smarty.const.PR_SMARTY_TEMPLATE}
				{/if}:
			</td>
			<td width="100%"><select size="1" id="f_tpl_id" name="tpl_id">
				{foreach from=$tmp key=tpl_id item=name}
					<option value="{$tpl_id}" {if $tpl_id eq $obj->tpl}selected{/if}>{$name}</option>
				{/foreach}
				</select>
				<input type="button" id="btn_tpl_id" value="{$smarty.const.BTN_NEW_TEMPLATE}" />
			</td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_TEMPLATE_ARGS}:</td>
			<td><input type="text" name="tpl_args" id="f_tpl_args" style="width: 100%;" value="{$obj->tpl_args}" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="checkbox" name="enableCaching" id="enableCaching" value="true" {if $obj->caching gt 0}checked{/if} /> {$smarty.const.PR_ENABLE_CACHING}
				<fieldset style="margin-top:5px" id="fCaching">
					<legend>{$smarty.const.OBJ_CACHE_OPTIONS}</legend>
					<div style="padding:10px;">
						{if $cid_tpl eq 1}
							{$smarty.const.PR_CACHE_CUSTOM_PART}: <input type="text" name="cidTpl" size="40" value="{$obj->cidTpl}">
							<hr>
						{/if}
						<input type="radio" name="flushCache" id="f_byWC" value="byWC" CHECKED /> {$smarty.const.PR_WC_CACHE_MANAGEMENT}<br>
						<input type="radio" name="flushCache" id="f_time" value="time" /> {$smarty.const.PR_TIME_CACHE_MANAGEMENT}:
						<input type="text" name="cacheLifetime" id="cacheLifetime" value="{$obj->cache_lifetime}" size="6" maxlength="6" disabled /><br>
					</div>
				</fieldset>
			</td>
		</tr>
	</table>
</fieldset>
<br />