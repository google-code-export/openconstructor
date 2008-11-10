{*$vary|@debug_print_var*}
{assign var="ocm_home" value="/openconstructor"}
{assign var="skinhome" value="$ocm_home/i/newskin"}
{assign var="img" value="$skinhome/images"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Pragma" content="no-cache">
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.H_CACHE_VARY_SUGGEST}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
	</head>
	<body style="border-style:groove; border-width: 2px;padding:0 20 20">
		<script>
			{literal}
				var a = [], j = 0;
				$(document).ready(function(){					$("#close").click(function(){
						window.close();
					});

					$("#insert").click(function(){
						$("#div_ch input").each(function(){
							if(this.checked)
								a[j++] = this.value;
						});
						returnValue = a;
						$("#close").click();
					});
				});
			{/literal}
		</script>
		<br />
		<h3>{$smarty.const.H_CACHE_VARY_SUGGEST}</h3>
		<div style="font-size: 90%;">{$smarty.const.SUGGESTTIONS_MAY_BE_INVALID}</div>
		<form name="f">
			<fieldset style="padding:10"><legend>{$smarty.const.H_SUGGESTIONS}</legend>
				<div style="padding: 10px; font-size: 110%;" id="div_ch">
				{if $vary|@sizeof}
					<div style="font-family: monospace;">
						{foreach from=$vary item=part}
							<input type="checkbox" value="{$part}" checked> {$part}<br />
						{/foreach}
					</div>
				{else}
					{$smarty.const.H_NO_SUGGESTIONS}
				{/if}
				</div>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="button" id="insert" onclick="returnSelected()" value="{$smarty.const.BTN_INSERT}" name="create"{if !($vary|@sizeof)}disabled{/if} />
				<input type="button" id="close" value="{$smarty.const.BTN_CANCEL}" />
			</div>
		</form>
	</body>
</html>