{include file="objects/header.tpl"}
<script language="JavaScript" type="text/JavaScript">
	var	dis = {if $WCS->decide($obj, 'editobj')}false{else}true{/if};
	var attCount = {$obj->files|@sizeof};
		{literal}
		var re=new RegExp('[^\\s]','gi');

		$(document).ready(function(){
			$("#close").click(function(){
				window.close();
			});

			$(".dsb").keyup(function(){
				if(!$("#f_name").val().match(re) || !$("#f_description").val().match(re) || dis)
					$("#f_save").attr('disabled',true);
				else
					$("#f_save").attr('disabled',false);
			});

			$("#f_cId").keyup(function(){
				$("#f_captcha").attr('disabled',!$(this).val().match(/[^\s]+/));
			});
			$("#f_cId").keyup();
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
		function addAttachment() {
			attCount++;
			var sample = document.getElementById('sampleAttach');
			var att = sample.cloneNode(true);
			sample.parentNode.appendChild(att);
			att.style.display = 'block';
			var sel = att.getElementsByTagName('SELECT');
			var inp = att.getElementsByTagName('INPUT');
			sel[0].onchange = function() {fsrcChanged(att);};
			sel[1].onchange = function() {ftypeChanged(att);};
			var i;
			for(i = 0; i < sel.length; i++)
				sel[i].name = sel[i].name.substr(0, sel[i].name.length - 1) + attCount + "]";
			for(i = 0; i < inp.length; i++)
				inp[i].name = inp[i].name.substr(0, inp[i].name.length - 1) + attCount + "]";
			return att;
		}
		function addField() {
			var sample = document.getElementById('sample');
			var inj = sample.cloneNode(true);
			sample.parentNode.appendChild(inj);
			inj.style.display = 'block';
			return inj;
		}
		function removeField(button) {
			var inj = button.parentNode.parentNode.parentNode.parentNode.parentNode;
			inj.parentNode.removeChild(inj);
		}
		function setField(inj, src, srcId, type, validator, errorText) {
			var inputs = inj.getElementsByTagName('INPUT');
			var selects = inj.getElementsByTagName('SELECT');
			selects[0].selectedIndex = src;
			inputs[0].value = srcId;
			inputs[0].onchange();
			selects[1].selectedIndex = type;
			inputs[1].value = validator;
			inputs[2].value = errorText;
		}
		function setFile(att, src, srcId, type, ext, size, errorText, isReq) {
			var inputs = att.getElementsByTagName('INPUT');
			var selects = att.getElementsByTagName('SELECT');
			selects[0].selectedIndex = src;
			inputs[0].value = srcId;
			selects[1].selectedIndex = type;
			inputs[1].value = ext;
			inputs[2].value = size;
			inputs[3].value = errorText;
			inputs[4].checked = isReq > 0;
			selects[0].onchange();
			selects[1].onchange();
			inputs[0].onchange();
		}
		function fsrcChanged(att) {
			var inputs = att.getElementsByTagName('INPUT');
			var selects = att.getElementsByTagName('SELECT');
			var dis = selects[0].selectedIndex == 1;
			if(dis)
				selects[1].selectedIndex = 0;
			selects[1].disabled = dis;
			inputs[1].disabled = dis;
			inputs[2].disabled = dis;
			inputs[3].disabled = dis;
			inputs[4].disabled = dis;
		}
		function ftypeChanged(att) {
			var inputs = att.getElementsByTagName('INPUT');
			var selects = att.getElementsByTagName('SELECT');
			inputs[4].disabled = selects[1].selectedIndex == 1 || selects[1].disabled;
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl" disableCaching="true"}
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_MAIL_SUBJECT}:</td>
			<td><input type="text" name="subject" value="{$obj->subject|escape}" size="40"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_MAIL_FROM}:</td>
			<td><input type="text" name="from" value="{$obj->from|escape}" size="40"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_MAIL_TO}:</td>
			<td><input type="text" name="to" value="{$obj->to|escape}" size="40"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_MAIL_CC}:</td>
			<td><input type="text" name="cc" value="{$obj->cc|escape}" size="40"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_MAIL_BCC}:</td>
			<td><input type="text" name="bcc" value="{$obj->bcc|escape}" size="40"></td>
		</tr>
		<tr>
			<td colspan=2>
				<fieldset style="padding:10"><legend>{$smarty.const.PR_MAIL_CONTENT_TYPE}</legend>
					<table style="margin:5 0" cellspacing="3">
						<tr>
							<td colspan="2"><input type=checkbox name="isHtml" value="true" onclick="$('#allowedTags').attr('disabled', !$(this).attr('checked'));" {if $obj->isHtml}checked{/if}> {$smarty.const.PR_MSG_IS_HTML}</td>
						</tr>
						<tr>
							<td colspan=2>{$smarty.const.PR_MSG_ALLOWED_TAGS}:</td>
						</tr>
						<tr>
							<td colspan=2><textarea cols="52" rows="4" id="allowedTags" name="allowedTags" {if !$obj->isHtml}disabled{/if}>{$obj->allowedTags}</textarea></td>
						</tr>
					</table>
				</fieldset>
				<br>
			</td>
		</tr>
	</table>
</fieldset>
<br>
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_CAPTCHA_PROPS}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_CAPTCHA_ID}:</td>
			<td><input type="text" name="cId" id="f_cId" value="{$obj->cId}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_CAPTCHA_VALUE}:</td>
			<td>
				<select name="captcha" id="f_captcha">
					<option value="" style="background: #eee">-&nbsp; &nbsp;</option>
					{foreach from=$obj->fields item=fld}
						<option value="{$fld[1]}" {if $obj->cId and $fld[1] eq $obj->captcha}selected{/if}>{$fld[1]}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" name="closeSess" id="ch.closeSess" value="true" {if $obj->closeSess}checked{/if}> <label for="ch.closeSess">{$smarty.const.PR_CLOSE_SESS}</label></td>
		</tr>
	</table>
</fieldset>
<br>
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_MSG_ATTACHMENTS}</legend>
	<div>
		<fieldset id="sampleAttach" style="margin:20px 0 30px;display:none;">
			<legend style="font-weight:bold;"></legend>
			<table style="margin:5 0" cellspacing="3" width="100%">
				<tr style="font-size:9px;color:#888;">
					<td>&nbsp;</td>
					<td>{$smarty.const.H_MSG_FIELD_SRC}</td>
					<td>{$smarty.const.H_MSG_FILE_SRC_PARAM}</td>
					<td nowrap>{$smarty.const.H_MSG_FIELD_TYPE}</td>
					<td rowspan="7" valign="top"><img src="{$img}/h/remove.gif" style="cursor:pointer;margin-right:10px;" onclick="removeField(this)" alt="{$smarty.const.BTN_REMOVE_ATTACHMENT}"></td>
				</tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_MSG_ATTACHMENT}</td>
					<td>
						<select size="1" name="attachSrc[]">
							<option value="{$smarty.const.FSRC_FILES}">$_FILES</option>
							<option value="{$smarty.const.FSRC_FILESYS}">Filesystem</option>
						</select>
					</td>
					<td><input type="text" name="attachSrcId[]" size="30" onchange="$(this).parents('fieldset:first').children('legend:first').html($(this).val());"></td>
					<td>
						<select size="1" name="attachType[]">
							<option value="{$smarty.const.FTYPE_FILE}">File</option>
							<option value="{$smarty.const.FTYPE_FILES}">Files</option>
						</select>
					</td>
				</tr>
				<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
				<tr style="font-size:9px;color:#888;">
					<td>&nbsp;</td>
					<td nowrap colspan="3">{$smarty.const.H_MSG_FILE_ALLOWED_EXT}</td>
				</tr>
				<tr>
					<td nowrap style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_MSG_FILE_EXT}</td>
					<td colspan="3"><input type="text" size="60" name="ext[]"></td>
				</tr>
				<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
				<tr style="font-size:9px;color:#888;">
					<td>&nbsp;</td>
					<td nowrap colspan="3">{$smarty.const.H_MSG_FILE_ALLOWED_SIZE}</td>
				</tr>
				<tr>
					<td nowrap style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_MSG_FILE_SIZE}</td>
					<td colspan="3"><input type="text" size="60" name="size[]"></td>
				</tr>
				<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
				<tr style="font-size:9px;color:#888;">
					<td>&nbsp;</td>
					<td nowrap>{$smarty.const.H_MSG_FIELD_ERROR_TEXT}</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_MSG_FIELD_ERROR}</td>
					<td colspan="3"><input type="text" size="60" name="attachError[]"></td>
				</tr>
				<tr>
					<td colspan="4"><input type="checkbox" value="true" name="attachIsReq[]">{$smarty.const.H_MSG_ATTACH_IS_REQ}</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="button" value="{$smarty.const.BTN_ADD_ATTACHMENT}" onclick="addAttachment()">
	<script>
		{foreach from=$obj->files item=file}
			{math assign="src" equation="val % 10 - 5" val=$file[0]}
			{math assign="type" equation="val / 10 - 3" val=$file[0]}
			{if $file[4]}{set fl = 1}{else}{set fl = 0}{/if}
			setFile(addAttachment(), {$src}, '{$file[1]}', {$type}, '{$file[2]}', '{$file[3]}', '{$file[5]}', {$fl});
		{/foreach}
	</script>
</fieldset>
<br>
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_MSG_FIELDS}</legend>
	<div>
		<fieldset id="sample" style="margin:20px 0 30px;display:none;">
			<legend style="font-weight:bold;"></legend>
			<table style="margin:5 0" cellspacing="3" width="100%">
				<tr style="font-size:9px;color:#888;">
					<td>&nbsp;</td>
					<td>{$smarty.const.H_MSG_FIELD_SRC}</td>
					<td>{$smarty.const.H_MSG_FIELD_SRC_PARAM}</td>
					<td nowrap>{$smarty.const.H_MSG_FIELD_TYPE}</td>
					<td rowspan="7" valign="top"><img src="{$img}/h/remove.gif" style="cursor:pointer;margin-right:10px;" onclick="removeField(this)" alt="{$smarty.const.BTN_REMOVE_MSG_FIELD}"></td>
				</tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_MSG_FIELD}</td>
					<td>
						<select size="1" name="src[]">
							<option value="{$smarty.const.FSRC_CTX}">Context</option>
							<option value="{$smarty.const.FSRC_GET}">GET</option>
							<option value="{$smarty.const.FSRC_POST}">POST</option>
							<option value="{$smarty.const.FSRC_COOKIE}">Cookie</option>
							<option value="{$smarty.const.FSRC_SESSION}">Session</option>
						</select>
					</td>
					<td><input type="text" name="srcId[]" size="30" onchange="$(this).parents('fieldset:first').children('legend:first').html($(this).val());"></td>
					<td>
						<select size="1" name="type[]">
							<option value="{$smarty.const.FTYPE_TXT}">Text</option>
							<option value="{$smarty.const.FTYPE_HTML}">HTML</option>
						</select>
					</td>
				</tr>
				<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
				<tr style="font-size:9px;color:#888;">
					<td>&nbsp;</td>
					<td nowrap>{$smarty.const.H_MSG_FIELD_VALIDATOR}</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_MSG_FIELD_VALIDATION}</td>
					<td colspan="3"><input type="text" size="60" name="validator[]"></td>
				</tr>
				<tr><td colspan="4" style="font-size:2px;">&nbsp;</td></tr>
				<tr style="font-size:9px;color:#888;">
					<td>&nbsp;</td>
					<td nowrap>{$smarty.const.H_MSG_FIELD_ERROR_TEXT}</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_MSG_FIELD_ERROR}</td>
					<td colspan="3"><input type="text" size="60" name="error[]"></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="button" value="{$smarty.const.BTN_ADD_MSG_FIELD}" onclick="addField()">
	<script>
		{foreach from=$obj->fields item=field}
			{math assign="src" equation="val % 10" val=$field[0]}
			{math assign="type" equation="val / 10 - 1" val=$field[0]}
			setField(addField(), {$src}, '{$field[1]|escape:javascript}', {$type}, '{$field[2]|escape:javascript}', '{$field[3]|escape:javascript}');
		{/foreach}
	</script>
</fieldset>
{include file="objects/footer.tpl"}