{include file="objects/header.tpl"}
<input type="hidden" name="docOrder" id="f_docOrder" value="">
<script src="{$ocm_home}/lib/js/controllers.js"></script>
<script src="{$ocm_home}/lib/js/widgets.js"></script>
<script language="JavaScript" type="text/JavaScript">
	var	dis = {if $WCS->decide($obj, 'editobj')}false{else}true{/if};
	var ds_id = {$obj->ds_id};
	var	host='{$host}', skin='newskin';
	var create_doc='{$smarty.const.H_CREATE_DOC}', add_doc='{$smarty.const.H_ADD_DOC}', remove_doc='{$smarty.const.H_REMOVE_DOC}';
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

			$("#f_ds_id").change(function(){
                $("#objFields *").attr('disabled', ($(this).val() != ds_id));
                $("#objDocOrder *").attr('disabled', ($(this).val() != ds_id));
                $("#objFilters *").attr('disabled', ($(this).val() != ds_id));
                $("#objFields *").attr('disabled', ($(this).val() != ds_id));
                $("#f_docOrder").attr('disabled', ($(this).val() != ds_id));
			});

			$("#f").submit(function() {
				prepareDocOrder();
			});

			init();
		});

		function openObjectUses(objId){
			openWindow("../object_uses.php?id=" + objId, 750, null, "objUses_" + objId);
		}
		function init() {
			new ArrayWidget(
				new ArrayController(document.getElementById('doc_ids')),
				document.getElementById('tab.doc_ids'),[create_doc, add_doc, remove_doc]
			);
		}
		function addField() {
			var l, r, o, sel;
			l = document.getElementById('ord.set');
			r = document.getElementById('ord.available');
			if(r.selectedIndex < 0) return;
			o = document.createElement('OPTION');
			o.value = r.options[r.selectedIndex].value;
			o.innerHTML = '+ ' + r.options[r.selectedIndex].innerHTML;
			l.appendChild(o);
			sel = r.selectedIndex;
			o.style.background = r.options[sel].style.background;
			r.removeChild(r.options[r.selectedIndex]);
			while(sel >= 0 && !r.options[sel])
				sel--;
			if(sel >= 0)
				r.selectedIndex = sel;
			o.selected = true;
			orderFieldClicked();
		}

		function removeField() {
			var l, r, o, sel;
			l = document.getElementById('ord.set');
			r = document.getElementById('ord.available');
			if(l.selectedIndex < 0) return;
			o = document.createElement('OPTION');
			o.value = Math.abs(l.options[l.selectedIndex].value);
			o.innerHTML = l.options[l.selectedIndex].innerHTML.substr(2);
			r.appendChild(o);
			sel = l.selectedIndex;
			o.style.background = l.options[sel].style.background;
			l.removeChild(l.options[l.selectedIndex]);
			while(sel >= 0 && !l.options[sel])
				sel--;
			if(sel >= 0)
				l.selectedIndex = sel;
			o.selected = true;
			orderFieldClicked();
		}
		function moveField(step) {
			var l, r, to;
			l = document.getElementById('ord.set');
			to = l.selectedIndex + step;
			if(l.selectedIndex < 0 || to < 0 || !l.options[to]) return;
			var firstIndex = step < 0 ? to : l.selectedIndex;
			var secondIndex = step < 0 ? l.selectedIndex : to;
			var secondNode = $(l.options[secondIndex]);
			$(l.options[secondIndex]).clone().insertBefore($(l.options[firstIndex]));
			l.selectedIndex = to;
			secondNode.remove();
		}
		function swapFieldDir() {
			var l, o;
			l = document.getElementById('ord.set');
			if(l.selectedIndex < 0) return;
			o = l.options[l.selectedIndex];
			o.value = o.value.substr(0, 1) == '-' ? o.value.substr(1) : '-' + o.value;
			o.innerHTML = (o.value.substr(0, 1) == '-' ? '- ' : '+ ') + o.innerHTML.substr(2);
		}
		function prepareDocOrder() {
			var l, order = new Array();
			l = document.getElementById('ord.set');
			for(var i = 0; i < l.options.length; i++)
				order[i] = l.options[i].value;
			$("#f_docOrder").val(order.join(','));
		}
		function addCondition() {
			var sample = document.getElementById('sample');
			var cond = sample.cloneNode(true);
			sample.parentNode.appendChild(cond);
			cond.style.display = 'block';
			return cond;
		}
		function removeCondition(button) {
			var cond = button.parentNode.parentNode.parentNode.parentNode.parentNode;
			cond.parentNode.removeChild(cond);
		}
		function setCondition(cond, type, field, src, value, invert) {
			var inputs = cond.getElementsByTagName('INPUT');
			var selects = cond.getElementsByTagName('SELECT');
			inputs[0].value = field;
			inputs[0].onchange();
			selects[0].selectedIndex = type;
			selects[1].selectedIndex = src;
			inputs[1].checked = invert;
			inputs[1].onclick();
			inputs[3].value = value;
		}
		function fieldClicked(id) {
			var ch = document.getElementById("ch.f" + id);
			var inp = document.getElementById("ep" + id);
			if(inp) {
				inp.disabled = !ch.checked;
				document.getElementById("trep" + id).style.display = ch.checked ? '' : 'none';
			}
		}
		function orderFieldClicked() {
			var sel = document.getElementById('ord.set');
			var tr = null;
			if(sel.oldTr)
				sel.oldTr.style.display = 'none';
			if(sel.selectedIndex != -1) {
				var opt = sel.options[sel.selectedIndex];
				tr = document.getElementById("trr" + Math.abs(opt.value));
				if(tr)
					tr.style.display = '';
			}
			sel.oldTr = tr;
		}
	{/literal}
</script>
{include file="objects/select_tpl.tpl"}
<fieldset style="padding:10" {if !$WCS->decide($obj, 'editobj.ds')}disabled{/if}><legend>{$smarty.const.OBJ_DATA}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_DATASOURCE}:</td>
			<td>
				<select size="1" name="ds_id" id="f_ds_id">
					{foreach from=$ds item=val}
						<option value="{$val.id}" {if $val.id eq $obj->ds_id}selected{/if}>{$val.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><br><input type="checkbox" id="ch.onlySub" name="onlySub" value="true" {if $obj->onlySub}checked{/if}> <label for="ch.onlySub">{$smarty.const.PR_REQUIRE_SUB_DS}</label></td>
		</tr>
	</table>
	<fieldset style="padding:10"><legend>{$smarty.const.OBJ_DS_DOCS}</legend>
		<div id="tabs.array" class="tabs"></div>
		<div id="array">
			<div style="width:100%;">
				<div id="tab.doc_ids" class="tabbed">
					<select id="doc_ids" name="doc_ids[]" multiple size="1" style="display:none;" isown="0" doctype="hybrid" fromds="{$obj->ds_id}" hybrid="-1" fieldid="-1">
						{foreach from=$docs key=id item=header}
							<option value="{$id}">{$header}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
	</fieldset>
	<br />
</fieldset>
<br />
<fieldset style="padding:10"><legend>{$smarty.const.OBJ_PROPERTIES}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td nowrap>{$smarty.const.PR_HEADER}:</td>
			<td><input type="text" name="header" value="{$obj->header|escape}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DOCUMENTS_LIST_OFFSET}:</td>
			<td><input type="text" name="listOffset" value="{$obj->listOffset}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DOCUMENTS_LIST_SIZE}:</td>
			<td><input type="text" name="listSize" value="{$obj->listSize}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DYNAMIC_DS_ID}:</td>
			<td><input type="text" name="dsIdKey" value="{$obj->dsIdKey}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_NODE_ID}:</td>
			<td><input type="text" name="nodeId" value="{$obj->nodeId}"></td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_NODE_ID_TYPE}:</td>
			<td>
				<select name="nodeType">
					<option value="{$smarty.const.NID_PLAIN}">{$smarty.const.H_NID_PLAIN}</option>
					<option value="{$smarty.const.NID_OR}" {if $obj->nodeType eq $smarty.const.NID_OR}selected{/if}>{$smarty.const.H_NID_OR}</option>
					<option value="{$smarty.const.NID_AND}" {if $obj->nodeType eq $smarty.const.NID_AND}selected{/if}>{$smarty.const.H_NID_AND}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td nowrap>{$smarty.const.PR_DOCUMENT_URI}:</td>
			<td><input type="text" name="srvUri" value="{$obj->srvUri}"></td>
		</tr>
		<tr>
			<td nowrap valign="top">{$smarty.const.PR_DOCUMENT_ID}:</td>
			<td><textarea name="docId" cols="30" rows="4" wrap="off">{$obj->docId}</textarea></td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="_pma" value="true" {if $pma}checked{/if}> {$smarty.const.PR_GROUP_PRIMITIVES_AS_ARRAYS}</td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="no404" value="true" {if $obj->no404}checked{/if}> {$smarty.const.PR_NO_404}</td>
		</tr>
		<tr>
			<td nowrap colspan=2><input type=checkbox name="sortByRand" value="true" {if $obj->sortByRand}checked{/if}> {$smarty.const.PR_SORT_BY_RAND}</td>
		</tr>
		<tr>
			<td colspan="2" width="100%">
				<fieldset style="padding:10"><legend>{$smarty.const.OBJ_SEARCH_PROPS}</legend>
					<table style="margin:5 0" cellspacing="3">
						<tr>
							<td nowrap>{$smarty.const.PR_KEYWORD_KEY}:</td>
							<td><input type="text" name="keywordKey" value="{$obj->keywordKey}"></td>
						</tr>
						<tr>
							<td nowrap>{$smarty.const.PR_NO_RESULTS_TPL}:</td>
							<td>
								<select size="1" name="noResTpl">
									<option value="0">-</option>
									{foreach from=$no_res_tpl key=tpl_id item=name}
										<option value="{$tpl_id}" {if $tpl_id eq $obj->noResTpl}selected{/if}>{$name}</option>
									{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2"><input type="checkbox" name="sortByRank" value="true" {if $obj->sortByRank}checked{/if}> {$smarty.const.PR_ORDERBY_RANK}</td>
						</tr>
					</table>
				</fieldset>
				<br />
			</td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset id="objFields" style="padding:10"><legend>{$smarty.const.PR_FETCH_FIELDS}</legend>
	<table class="fieldlist" cellspacing="0">
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="id" disabled checked/></td>
			<td class="sys">id</td><td colspan="2">ID</td>
		</tr>
		<tr>
			<td class="f"><input type="checkbox" name="field[]" value="header" disabled checked/></td>
			<td class="sys">header</td><td colspan="2" width="100%">Header</td>
		</tr>
		{foreach name=fields from=$objfields item=val}
			{if $val.ds_name neq $dsid}
				{set dsid = $val.ds_name}
				<tr {if !$smarty.foreach.fields.first} class="f"{/if}><td colspan="4" style="padding-top:10px">{$ds.$dsid.name} :</td></tr>
			{/if}
			<tr {if !$smarty.foreach.fields.first} class="f"{/if}>
				<td class="f"><input type="checkbox" name="field[{$val.id}][id]" value="{$val.id}" onclick="fieldClicked({$val.id})" id="ch.f{$val.id}" {if $val.checked}checked{/if}></td>
				<td class="sys">{$val.key}</td>
				<td colspan="2"><a href="javascript:wxyopen('../../data/hybrid/field/{$val.family}.php?id={$val.id}',550,430)">{$val.header}</a></td>
			</tr>
			{if $val.rating}
				<tr class="extraprop" id="trep{$val.id}">
					<td colspan="2">&nbsp;</td>
					<td>{$smarty.const.H_RATING_PERIOD}:</td>
					<td><input type="text" size="40" name="field[{$val.id}][range]" value="{$val.rating|escape}" id="ep{$val.id}" disabled></td>
				</tr>
			{elseif $val.fetcher_id}
				<tr class="extraprop" id="trep{$val.id}">
					<td colspan="2">&nbsp;</td>
					<td>{$smarty.const.H_NESTED_LOADER}:</td>
					<td>
						<select size="1" disabled name="field[{$val.id}][fetcher]" id="ep{$val.id}">
							<option value="0" style="background: #eee;">- &nbsp; &nbsp;</option>
							{foreach from=$fetchers key=k item=v}
								<option value="{$k}" {if $val.fetcher_id eq $k}selected{/if}>{$v.name}</option>
							{/foreach}
						</select>
					</td>
				</tr>
			{/if}
			<script>fieldClicked({$val.id});</script>
		{/foreach}
	</table>
</fieldset>
<br />
<fieldset style="padding:10" id="objDocOrder"><legend>{$smarty.const.OBJ_DOC_ORDER}</legend>
	<table style="margin:5 0" cellspacing="3">
		<tr>
			<td width="50%">{$smarty.const.H_ORDER_LIST_BY}:<br>
				<select size="10" id="ord.set" style="margin-top:5px;" onclick="orderFieldClicked()">
					{foreach from=$order item=val}
						<option value="{if $val.pref eq 0}-{/if}{$val.id}" {if $val.sysf} style="background: #eee;"{/if}>{if $val.pref eq 0}-{else}+{/if} {$val.header}</option>
					{/foreach}
				</select>
			</td>
			<td>
				<input type="button" value="{$smarty.const.BTN_ADD_FIELD}" onclick="addField();" style="width:85px;margin:2px 0px;">
				<input type="button" value="{$smarty.const.BTN_REMOVE_FIELD}" onclick="removeField();" style="width:85px;margin:2px 0px;">
				<input type="button" value="{$smarty.const.BTN_MOVEUP_FIELD}" onclick="moveField(-1);" style="width:85px;margin:2px 0px;">
				<input type="button" value="{$smarty.const.BTN_MOVEDOWN_FIELD}" onclick="moveField(1);" style="width:85px;margin:2px 0px;">
				<input type="button" value="{$smarty.const.BTN_SWITCH_ORDERING}" onclick="swapFieldDir();" style="width:85px;margin:2px 0px;">
			</td>
			<td width="50%">{$smarty.const.H_AVAILABLE_DSH_FIELDS}:<br>
				<select size="10" id="ord.available" style="margin-top:5px;">
					{foreach from=$availablefilds item=val}
						<option value="{$val.id}" {if $val.sysf} style="background: #eee;"{/if}>{$val.header}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		{foreach from=$ratingperiod item=val}
			<tr id="trr{$val.id}" style="display: none;">
				<td colspan="3">
					{$smarty.const.H_RATING_PERIOD}: <input type="text" name="rRange[{$val.id}]" id="rr{$val.id}" value="{$val.range}" style="vertical-align: middle" size="40">
				</td>
			</tr>
		{/foreach}
	</table>
</fieldset>
<br />
<fieldset style="padding:10" id="objFilters"><legend>{$smarty.const.OBJ_FILTERS}</legend>
	<div>
		<fieldset id="sample" style="margin:20px 0 30px;display:none;"><legend class="test" style="font-weight:bold;"></legend>
			<table style="margin:5 0" cellspacing="3" width="100%">
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_COND_WHERE}</td>
					<td colspan="2" width="100%">
						<input name="filter[]" type="text" style="margin-top:5px;" onchange="$(this).parents('fieldset:first').children('legend:first').html($(this).val());">
					</td>
					<td rowspan="3" valign="top"><img src="{$img}/h/remove.gif" style="cursor:pointer;margin-right:10px;" onclick="removeCondition(this)" alt="{$smarty.const.BTN_REMOVE_CONDITION}"></td>
				</tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_COND}</td>
					<td>
						<select size="1" name="type[]">
							<option value="{$smarty.const.COND_EQ}">Equal</option>
							<option value="{$smarty.const.COND_BTW}">Between</option>
							<option value="{$smarty.const.COND_GT}">Greater than</option>
							<option value="{$smarty.const.COND_LT}">Less than</option>
							<option value="{$smarty.const.COND_GTEQ}">Greater than or equal</option>
							<option value="{$smarty.const.COND_LTEQ}">Less than or equal</option>
							<option value="{$smarty.const.COND_CONTAINS}">Contains</option>
							<option value="{$smarty.const.COND_LIKE}">Like</option>
						</select>
						<input type="checkbox" onclick="$(this).next().val($(this).attr('checked'));"><input type="hidden" name="invert[]" value="false"> {$smarty.const.H_INVERT_COND}
					</td>
				</tr>
				<tr><td colspan="2" style="font-size:2px;">&nbsp;</td></tr>
				<tr>
					<td style="padding-left:5px; padding-right:10px; font-size:115%;">{$smarty.const.H_COND_VALUE}</td>
					<td>
						<select size="1" name="src[]">
							<option value="{$smarty.const.VALUE_CTX}">Context</option>
							<option value="{$smarty.const.VALUE_PLAIN}">Plain</option>
						</select>
						<input type="text" name="value[]">
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="button" value="{$smarty.const.BTN_ADD_CONDITION}" onclick="addCondition()">
	<script>
		{foreach from=$condition item=val}
			setCondition(addCondition(), {$val.type}, '{$val.name|escape:javascript}', {$val.src}, '{$val.value|escape:javascript}', {$val.invert});
		{/foreach}
	</script>
</fieldset>
{include file="objects/footer.tpl"}