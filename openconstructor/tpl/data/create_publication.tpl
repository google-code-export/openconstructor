<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.CREATE_DS_PUBLICATION}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script language="JavaScript" type="text/JavaScript">
			var re = new RegExp('[^\\s]','gi');
			var dis = {$dis};
			{literal}
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
			{/literal}
		</script>
	</head>
	<body>
		<h3>{$smarty.const.CREATE_DS_PUBLICATION}</h3>
		{$reportResult}
		<form name="f" method="POST" action="i_data.php">
			<input type="hidden" name="action" value="create_dspublication">
			<fieldset style="padding:10"><legend>{$smarty.const.DS_GENERAL_PROPS}</legend>
				<div class="property"{$isValid.ds_name}>
					<span>{$uf.ds_name}:</span>
					<input type="text" name="ds_name" id="f_name" class="dsb" value="{$ds_name}" size="64" maxlength="64" />
				</div>
				<div class="property"{$isValid.description}>
					<span>{$uf.description}:</span>
					<textarea cols="51" rows="5" name="description">{$description}</textarea>
				</div>
			</fieldset><br>
			<fieldset style="padding:10"><legend>{$smarty.const.DS_PROPS}</legend>
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td>{$smarty.const.DS_SIZE}:</td>
					<td><input type="text" name="dssize" size="5" maxlength="4" /> {$smarty.const.DS_RECORDS}</td>
				</tr>
				<tr>
					<td>{$smarty.const.DS_INTROSIZE}:</td>
					<td><input type="text" name="introsize" size="5" maxlength="4" /> {$smarty.const.DS_CHARS}</td>
				</tr>
			</table>
			</fieldset><br>
			<fieldset style="padding:10"><legend>{$smarty.const.DS_SEARCH}</legend>
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td nowrap><input type="checkbox" name="isindexable" checked /> {$smarty.const.IS_INDEXABLE}</td><td></td>
				</tr>
			</table>
			</fieldset><br>
			<fieldset style="padding:10"><legend>{$smarty.const.DS_GRAPH_PROPS}</legend>
				<fieldset style="padding:10"><legend>{$smarty.const.DS_GRAPH_BOUNDS}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.DS_GRAPH_MIN_RECT}:</td>
						<td nowrap valign="top">{$smarty.const.DS_GRAPH_WIDTH} <input type="text" name="xmin" size="4" maxlength="4" />&nbsp;&nbsp;{$smarty.const.DS_GRAPH_HEIGHT} <input type="text" name="ymin" size="4" maxlength="4" /></td>
					</tr>
					<tr>
						<td>{$smarty.const.DS_GRAPH_MAX_RECT}:</td>
						<td nowrap>{$smarty.const.DS_GRAPH_WIDTH} <input type="text" name="xmax" size="4" maxlength="4" />&nbsp;&nbsp;{$smarty.const.DS_GRAPH_HEIGHT} <input type="text" name="ymax" size="4" maxlength="4" /></td>
					</tr>
				</table>
				</fieldset><br>
				<fieldset style="padding:10"><legend>{$smarty.const.DS_GRAPH_IMAGES}</legend>
				<table style="margin:5 0" cellspacing="3" width="100%">
					<tr>
						<td nowrap><input type="checkbox" name="img_main" /> {$smarty.const.DS_GRAPH_IMAGEMAIN}</td><td></td>
					</tr>
					<tr>
						<td colspan="2"><hr size="1"></td>
					</tr>
					<tr>
						<td nowrap valign="top"><input type="checkbox" name="img_intro" onclick="f.intro.disabled=!this.checked" value="enabled" /> {$smarty.const.DS_GRAPH_IMAGEINTRO}</td>
						<td></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;{$smarty.const.DS_GRAPH_IMAGEINTRO_SIZE}:</td>
						<td width="100%"><input type="text" name="intro" size="4" maxlength="3" disabled />{$smarty.const.DS_GRAPH_PERCENT_10_100}</td>
					</tr>
				</table>
				</fieldset><br>
			</fieldset><br>
			<fieldset style="padding:10"><legend>{$smarty.const.DS_ATTACHED_GALLERY}</legend>
				<br><input type="checkbox" name="attach_gallery" onclick="$('#ag').slideToggle()" value="true" /> {$smarty.const.DS_AUTO_ATTACH_GALLERY}
				<fieldset style="padding:10;display:none;" id="ag"><legend>{$smarty.const.DS_PROPS}</legend>
					<fieldset style="padding:10"><legend>{$smarty.const.DS_GRAPH_BOUNDS}</legend>
					<table style="margin:5 0" cellspacing="3">
						<tr>
							<td>{$smarty.const.DS_GRAPH_MIN_RECT}:</td>
							<td nowrap valign="top">{$smarty.const.DS_GRAPH_WIDTH} <input type="text" name="g_xmin" size="4" maxlength="4" />&nbsp;&nbsp;{$smarty.const.DS_GRAPH_HEIGHT} <input type="text" name="g_ymin" size="4" maxlength="4" /></td>
						</tr>
						<tr>
							<td>{$smarty.const.DS_GRAPH_MAX_RECT}:</td>
							<td nowrap>{$smarty.const.DS_GRAPH_WIDTH} <input type="text" name="g_xmax" size="4" maxlength="4" />&nbsp;&nbsp;{$smarty.const.DS_GRAPH_HEIGHT} <input type="text" name="g_ymax" size="4" maxlength="4" /></td>
						</tr>
					</table>
					</fieldset><br>
					<fieldset style="padding:10"><legend>{$smarty.const.DS_GRAPH_IMAGES}</legend>
					<table style="margin:5 0" cellspacing="3" width="100%">
						<tr>
							<td nowrap><input type="checkbox" name="g_img_main" /> {$smarty.const.DS_GRAPH_IMAGEMAIN}</td><td></td>
						</tr>
						<tr>
							<td colspan="2"><hr size="1"></td>
						</tr>
						<tr>
							<td nowrap valign="top"><input type="checkbox" name="g_img_intro" onclick="f.g_intro.disabled=!this.checked" value="enabled" /> {$smarty.const.DS_GRAPH_IMAGEINTRO}</td>
							<td></td>
						</tr>
						<tr>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;{$smarty.const.DS_GRAPH_IMAGEINTRO_SIZE}:</td>
							<td width="100%"><input type="text" name="g_intro" size="4" maxlength="3" disabled />{$smarty.const.DS_GRAPH_PERCENT_10_100}</td>
						</tr>
					</table>
					</fieldset><br>
				</fieldset><br>
			</fieldset><br>
			<div align="right">
				<input type="submit" value="{$smarty.const.BTN_CREATE_DS}" name="create" id="f_save" disabled /> 
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>