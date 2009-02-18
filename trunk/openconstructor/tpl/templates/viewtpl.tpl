<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.VIEW_TEMPLATE} | {$types[$type]}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script language="Javascript" src="{$ocm_home}/lib/js/base.js"></script>
	</head>
	<body  style="border:groove;border-width:2;margin:0;">
		<script language="JavaScript" type="text/JavaScript">
			{literal}
				$(document).ready(function(){
					var src = getSrcEdit();
					src.setSource($("#f_tpl").val());
					src.setEditable(false);
				});
				function getSrcEdit() {
					if (document.all) return document.getElementById("srcIE.tpl");
					else return document.getElementById("src.tpl");
				}
			{/literal}
		</script>
		<textarea id="f_tpl" style="display: none;">{$tpl|escape}</textarea>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
			<tr>
				<td style="padding:10px;"><b>{$smarty.const.H_TPL_TYPE}</b>: {$types[$type]} ({$type})</td>
			</tr>
			<tr height="100%">
				<td style="padding:0 10px 10px;" valign="top">
					<div style=" border-width:2px; border-style: groove; height: 100%;">
						<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" id="srcIE.tpl" style="width: 100%; height: 100%;">
							{$SyntaxHighlighter->getHtmlEditor('IE')}
							<comment>
								<embed
									{$SyntaxHighlighter->getHtmlEditor()}
									id="src.tpl" style="width: 100%; height: 100%;">
								</embed>
							</comment>
						</object>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>
<html>