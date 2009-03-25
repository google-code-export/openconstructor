<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_DS_HYBRID} | {$ds_name}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/common.js"></script>
		<script language="JavaScript" type="text/JavaScript">
			var re = new RegExp('[^\\s]','gi');
			var dis = {$dis};
			{literal}
			var rek = new RegExp('^[a-zA-Z][a-zA-Z0-9]{0,5}(\\.[a-zA-Z][a-zA-Z0-9]{0,5})*$','g');
			$(document).ready(function(){
				$("#close").click(function(){
					window.close();
				});

				$(".dsb").keyup(function(){
					if(!$("#f_name").val().match(re) || dis)
						$("#f_save").attr('disabled',true);
					else
						$("#f_save").attr('disabled',false);
				});
			});
			function addfield(){
				wxyopen('./hybrid/field/add.php?ds_id={/literal}{$ds->ds_id}{literal}',550,430);
			}
			function removefields(){
				if(mopen("../confirm.php?q={/literal}{$smarty.const.REMOVE_SELECTED_FIELDS_Q|escape:'url'}{literal}",350,170)) {
					$("form[name=f]").attr("action", "./hybrid/i_hybrid.php");
					$("form[name=f] input[name=action]").val("remove_field");
					$("form[name=f]").submit();
				}
			}
			{/literal}
		</script>
	</head>
	<body>
		<h3>{$smarty.const.EDIT_DS_HYBRID}</h3>
		{$reportResult}
<form name="f" method="POST" action="i_data.php">
	<input type="hidden" name="action" value="edit_dshybrid" />
	<input type="hidden" name="ds_id" value="{$ds_id}" />
	<input type="hidden" name="dshkey" value="{$ds->key}" />
	<fieldset style="padding:10"><legend>{$smarty.const.DS_GENERAL_PROPS}</legend>
		<div class="property"{$isValid.ds_key}>
			<span>{$uf.ds_key}:</span>
			<input disabled type="text" name="ds_key" value="{$ds_key}" size="64" maxlength="128" />
		</div>
		<div class="property"{$isValid.ds_name}>
			<span>{$uf.ds_name}:</span>
			<input type="text" name="ds_name" id="f_name" class="dsb" value="{$ds_name}" size="64" maxlength="64" />
		</div>
		<div class="property"{$isValid.description}>
			<span>{$uf.description}:</span>
			<textarea cols="51" rows="5" name="description">{$description}</textarea>
		</div>
	</fieldset><br>
	<fieldset style="padding:10"><legend>{$smarty.const.DS_HYBRID_FIELDS}</legend>
		<table class="fieldlist" cellspacing="0">
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="id" disabled/></td>
			<td class="sys">id</td><td>ID</td>
		</tr>
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="header" disabled/></td>
			<td class="sys">header</td><td>Header</td>
		</tr>
		{foreach from=$record->fields item=f}
			{if $f->ds_id != $lastDs}
			<tr><td colspan="3" style="padding-top:10px">{$dss.$f->ds_id.name} :</td></tr>
			{/if}
			<tr>
				<td class="f"><input type="checkbox" name="field[]" value="{$f->key}"{if $f->ds_id != $ds->ds_id} disabled{/if}></td>
				<td class="sys">{$f->key}</td>
				<td><a href="javascript:wxyopen('./hybrid/field/{$f->family}.php?id={$f->id}',550,430)">{$f->header}</a></td>
			</tr>
			{set lastDs=$f->ds_id}
		{/foreach}
		</table>
		<input type="button" value="{$smarty.const.BTN_ADD_FIELD}" onclick="addfield()"> <input type="button" value="{$smarty.const.BTN_REMOVE_FIELDS}" onclick="removefields()">
	</fieldset><br>
	<fieldset style="padding:10"><legend>{$smarty.const.DS_SEARCH}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap><input type="checkbox" name="isindexable"{if $ds->isIndexable} checked{/if} onclick="$('#fIndexed').attr('disabled', !this.checked)"> {$smarty.const.IS_INDEXABLE}</td>
		</tr>
	</table>
		<fieldset style="padding:10" id="fIndexed" disabled><legend>{$smarty.const.H_INDEX_PROPS}</legend>
		<div class="property">
			<span>{$smarty.const.INDEXED_DOC_PATTERN}:</span>
			<textarea cols="51" rows="5" name="indexedDoc">{$indexedDoc}</textarea>
		</div>
		<div class="property">
			<span>{$smarty.const.INDEX_INTRO_FIELD}:</span>
			<select size="1" name="docIntro">
				<option value="0">-</option>
				{foreach from=$recFlds item=f}
				<option value="{$f.key}"{if $f.key == $ds->docIntro} selected{/if}>{$f.header}</option>
				{/foreach}
			</select>
		</div>
		</fieldset><br>
	</fieldset><br>
	<script>$('#fIndexed').attr('disabled', !$("input[name=isindexable]").attr("checked"));</script>
	<fieldset style="padding:10"><legend>{$smarty.const.DS_PROPS}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td>
				{if $ds->editTpl > 0}
				<a href="/tpl [id = {$ds->editTpl}]" onclick="wxyopen('../templates/edit.php?dstype=hybridbodyedit&id={$ds->editTpl}', 660); return false;">{$smarty.const.DS_DOC_EDIT_TPL}</a>
				{else}
				{$smarty.const.DS_DOC_EDIT_TPL}
				{/if}
			</td>
			<td><select name="editTpl" size="1">
					<option value="0" style="background:#eee;">- &nbsp; &nbsp; &nbsp;</option>
					{foreach from=$etpls item=title key=tplId}
					<option value="{$tplId}"{if $tplId == $ds->editTpl} selected{/if}>{$title}</option>
					{/foreach}
			</select></td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="autoPublish" value="true"{if $ds->autoPublish} checked{/if}{if !$disPublish} disabled{/if}> {$smarty.const.DS_ALLOW_AUTOPUBLISHING}</td>
		</tr>
	</table>
	</fieldset><br>
	<div align="right">
		<input type="submit" value="{$smarty.const.BTN_SAVE}" name="create" id="f_save" /> 
		<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
	</div>
</form>
</body>
</html>