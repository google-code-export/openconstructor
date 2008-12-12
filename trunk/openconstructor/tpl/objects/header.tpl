<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.EDIT_OBJECT} {$obj->name|escape}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$ocm_home}/lib/js/base.js"></script>
	</head>
	<body style="border-style:groove;padding:0 20 20">
		<br />
		<h3>{$smarty.const.EDIT_OBJECT}</h3>
		<form name="f" method="POST" action="i_{$obj->ds_type}.php">
			<input type="hidden" name="action" value="edit_{$obj->obj_type}" />
			<input type="hidden" name="obj_id" value="{$obj->obj_id}" />
			<fieldset style="padding:10"><legend>{$smarty.const.OBJECT}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.PR_OBJ_NAME}:</td>
						<td><input type="text" id="f_name" name="name" size="64" maxlength="64" value="{$obj->name|escape}" class="dsb" /></td>
					</tr>
					<tr>
						<td>{$smarty.const.PR_OBJ_DESCRIPTION}:</td>
						<td><textarea cols="51" rows="5" id="f_description" name="description" class="dsb">{$obj->description}</textarea></td>
					</tr>
				</table>
			</fieldset>
			<br />