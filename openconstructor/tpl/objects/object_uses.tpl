<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.H_OBJECT_USES} {$obj->name|escape}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/object_uses.js"></script>
	</head>
	<body id="inner_page">
		{literal}
			<style>
				#sitemap {
					font-size: 11px;
					margin-top: 20px;
					}
				#sitemap .r0 {
					}
				#sitemap .r1 {
					background: #eee;
					}
				#sitemap SELECT {
					visibility: hidden;
					}
				#sitemap THEAD TD {
					font-size: 14px;
					padding: 5px 10px;
					background: #ddd;
					}
				#pages UL {
					margin:0 0 0 30px;
					padding:0;
					list-style-type:none;
					font-size:11px;
					}
				#pages LI {
					margin:2px 0;
					padding:0;
					}
			</style>
		{/literal}
		<script language="JavaScript" type="text/JavaScript">
			{literal}
				window.onload = function(){					setTimeout(initUses, 100);
				}
			{/literal}
		</script>
		<h3 class="hTitle">{$smarty.const.H_OBJECT_USES}</h3>
		<form name="f" method="POST" action="i_objects.php">
			<input type="hidden" name="action" value="edit_uses">
			<input type="hidden" name="obj_id" value="{$obj->obj_id}">
			<fieldset><legend>{$smarty.const.OBJECT}</legend>
				<table cellspacing="5">
					<tr>
						<td nowrap>{$smarty.const.PR_OBJ_NAME}:</td>
						<td><b>{$obj->name|escape}</b></td>
					</tr>
					<tr>
						<td nowrap>{$smarty.const.PR_OBJ_TYPE}:</td>
						<td>{$obj_type_name}</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset><legend>{$smarty.const.H_OBJECT_USES}</legend>
				<script language="JavaScript" type="text/JavaScript">
					{literal}
						var map = {
					{/literal}
						{foreach from=$map name=tree key=id item=node}
							"{$node.id}" : [{$node.pref}, {$node.level}, "{$node.block}", {$node.blocks_ref}, "{$node.name}"]{if !$smarty.foreach.tree.last},{/if}
						{/foreach}
					{literal}
						}
					{/literal}
				</script>
				<table id="sitemap" style="width: 100%;" cellspacing=0 cellpadding=0>
					<thead>
						<tr>
							<td>{$smarty.const.H_PAGE_BLOCK}</td>
							<td>{$smarty.const.H_PAGE_TITLE}</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="padding: 3px 5px;">
								<select size="1" class="blocks" disabled="">
									<option class="gray" value="">-</option>
									{foreach from=$events item=evt}
										<option value="{$evt}">{$evt}</option>
									{/foreach}
								</select>
							</td>
							<td style="width: 100%;"></td>
						</tr>
					</tbody>
				</table>
				<hr size="1">
				<table border=0>
					<tr>
						<td>{$smarty.const.H_BULK_SELECT_BLOCK}:</td>
						<td>
							<input type="text" id="txt.bulkBlock" size="20"> <input type="button" value="{$smarty.const.BTN_BULK_SELECT_BLOCK}" onclick="selectBlock(document.getElementById('txt.bulkBlock').value)">
						</td>
					</tr>
				</table>
			</fieldset>
			<br />
			{set newblocks=$blocks|@implode:"],["}
			<script>
				var blocks = [[{$newblocks}]];
			</script>
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" id="f_save" name="save" />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
		<br />
	</body>
</html>
