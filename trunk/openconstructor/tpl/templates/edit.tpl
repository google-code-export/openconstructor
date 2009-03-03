<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{if $tplVars.id neq 'new'}{$smarty.const.EDIT_TEMPLATE} | {$tpl->name|escape}{else}{$smarty.const.CREATE_TEMPLATE}{/if}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script language="Javascript" src="{$ocm_home}/lib/js/base.js"></script>
	</head>
	<body id="tpl_page" ondrag="return false;">
		<script language="JavaScript" type="text/JavaScript">
			var	tpl_type = '{$tpl->type}', tpl_id = '{$tpl->id}',
				save = {$save}, var_id = '{$tplVars.id}', var_caret = '{$tplVars.caret}',
				tpl_clear = '{$smarty.const.CACHE_HB_CLEARED_I|escape:"url"}',
				tpl_recompiled = '{$smarty.const.TPL_HB_RECOMPILED_I|escape:"url"}',
				hide_errors = '{$smarty.const.BTN_HIDE_ERRORS}', show_errors = '{$smarty.const.BTN_SHOW_ERRORS}';
			{literal}
				var re=new RegExp('[^\\s]','gi');
				$(document).ready(function(){
					$("#tpl_name").keyup(function(){						$("#f_save").attr('disabled',!(save && $(this).val().match(re)));
					});
					$("#tpl_name").keyup();

					$("#aErrors").click(function(){
						if($("#div_errors").css('display') == 'none'){							$("#div_errors").show();
							$(this).text(hide_errors);
						}
						else{
							$("#div_errors").hide();
							$(this).text(show_errors);
						}
					});

					keys = new KeyBinder(document);
					keys.addShortcut("ctrl+alt+l", window.goToLineDialog);

					var src = getSrcEdit();
					src.setSource($("#f_html").val());
					if(var_id != 'new' && !save)
						src.setEditable(false);
					window.setTimeout(function() {src.setCaretPosition(var_caret);}, 50);
				});

				function clearCache() {
					var d=new Date();
					openModal("manage_tpl.php?clearcache=1&type="+tpl_type+"&id="+tpl_id+"&msg="+tpl_clear+"&j="+Math.ceil(d.getTime()/1000),350,170);
					document.getElementById('btn.clearcache').disabled=true;
				}
				function clearCompiled() {
					var d=new Date();
					openModal("manage_tpl.php?clearcompiled=1&type="+tpl_type+"&id="+tpl_id+"&msg="+tpl_recompiled+"&j="+Math.ceil(d.getTime()/1000),350,170);
					document.getElementById('btn.clearcompiled').disabled=true;
				}
				function saveTpl() {
					var src = getSrcEdit();
					$("#f_html").val(src.getSource());
					src.setEditable(false);
					$("#f_caret").val(src.getCaretPosition());
					$("#f_save").attr('disabled',true);
					$("form[name='f']").submit();
				}
				function goToLineDialog() {
					var src = getSrcEdit();
					goToLine(prompt("Go to line [1.." + src.getLineCount() + "]:", src.getCaretLine() + 1));
				}
				function goToLine(line) {
					var src = getSrcEdit();
					try {
						var index = parseInt(line) - 1;
						if(index >= 0 && index < src.getLineCount())
							src.setCaretLine(index);
					} catch(e){}
					src.focus();
				}
				function loadDefaultTpl() {
					var src = getSrcEdit();
					src.setSource($("#defaultTpl").val());
				}
				function getSrcEdit() {					if (document.all) return document.getElementById("htmlObject");
					else return document.getElementById("htmlEmbed");
				}
			{/literal}
		</script>
		<form method="POST" style="margin:0;padding:0" enctype="multipart/form-data" name="f" action="i_templates.php">
			<input type=hidden name="dstype" value="{$tplVars.dstype}">
			<input type=hidden name="action" value="{if $tplVars.id eq 'new'}create{else}edit{/if}_tpl">
			<input type=hidden name="id" value="{$tplVars.id}">
			<input type="hidden" name="type" value="{$tpl->type}">
			<input type="hidden" name="caret" id="f_caret" value="0">
			<input type="hidden" name="select" value="{if !$tpl->id and $tplVars.select}1{else}0{/if}">
			<textarea name="html" id="f_html" style="display:none;">{if $tplVars.id neq 'new'}{$tpl->tpl|escape}{/if}</textarea>
			<textarea name="defaultTpl" id="defaultTpl" style="display:none;">{if !$tpl->id}{$defTpl|escape}{/if}</textarea>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
				<tr>
					<td style="padding:10px;">
						{$smarty.const.H_TPL_NAME}:<br>
						<input type="text" name="tpl_name" id="tpl_name" style="width: 100%;" maxlength="255" value="{$tpl->name|escape}">
					</td>
				</tr>
				{if $tpl->errors|@sizeof gt 0}
					<tr>
						<td style="padding:0px 10px 5px;">
							<div style="text-align: right; font-size: 85%; padding-bottom: 3px;">
								<a href="#errors" id="aErrors">{$smarty.const.BTN_HIDE_ERRORS}</a>
							</div>
							<div class="fresult" id="div_errors">
								<ul>
									{foreach from=$tpl->errors item=val}
										{if $val.type eq $smarty.const.E_USER_ERROR}
											<li><b>{$errorText[$val.type]}</b>: {$val.msg|regex_replace:'~^(\s*)\[(line (\d+))\]~su':'\1[<a href="javascript: goToLine(\3);">\2</a>]'}</li>
										{else}
											<li>{$errorText.$val.type}: {$val.msg|regex_replace:'~^(\s*)\[(line (\d+))\]~su':'\1[<a href="javascript: goToLine(\3);">\2</a>]'}</li>
										{/if}
									{/foreach}
								</ul>
							</div>
						</td>
					</tr>
				{/if}
				<tr>
					<td height="100%" style="padding:0 10px;">
						<div style=" border-width:2px; border-style: groove; height: 300px;">
							{$SyntaxHighlighter->getHtmlEditor('html', 'style="width:100%; height:100%;"')}
						</div>
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 10px;">
						<div style="float: left;">
							{if $tplVars.id neq 'new'}
								<input type="button" id="btn.clearcache" onclick="clearCache()" value="{$smarty.const.BTN_CLEAR_SMARTY_TPL_CACHE}" {if !$WCS->decide($tpl, 'edittpl')}disabled{/if}>
								<input type="button" id="btn.clearcompiled" onclick="clearCompiled()" style="width:190px;" value="{$smarty.const.BTN_CLEAR_SMARTY_TPL_COMPILED}" {if !$WCS->decide($tpl, 'edittpl')}disabled{/if}>
								<input type="button" onclick="window.location.assign('?id=new&dstype={$tplVars.dstype}&type={$tpl->type}')" value="{$smarty.const.BTN_NEW_TEMPLATE}">
							{else}
								<input type="button" onclick="loadDefaultTpl()" value="{$smarty.const.BTN_LOAD_DEFAULT}" {if !$defTpl}disabled{/if}>
							{/if}
						</div>
						<div style="float: right;">
							<input type="button" name="save" id="f_save" onclick="saveTpl()" value="{$smarty.const.BTN_SAVE}">
						</div>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>