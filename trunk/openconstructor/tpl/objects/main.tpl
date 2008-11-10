{*$tree|@debug_print_var*}
{assign var="ocm_home" value="/openconstructor"}
{assign var="skinhome" value="$ocm_home/i/newskin"}
{assign var="img" value="$skinhome/images"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.SITEMAP} | {$page->header|escape}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<base href="http://{$smarty.server.SERVER_NAME}{$ocm_home}/{$cur_section}/" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/common.js"></script>
	</head>
	<body>
		<script>
			var
				wchome = "{$ocm_home}",
				skin = "{$smarty.const.SKIN}",
				imghome = wchome+'/i/'+skin+'/images',
				curnode="{$curnode}",
				nodetype="{$nodetype}",
				REMOVE_SELECTED_OBJECTS_Q = "{$smarty.const.REMOVE_SELECTED_OBJECTS_Q}"
				;
		</script>
		<div class="wrapper">
			<div class="top">
				<div class="header">
					<p>
						<span class="website">{$smarty.const.EDITING_SITE}: <a href="http://{$smarty.server.SERVER_NAME}">{$smarty.server.SERVER_NAME}</a></span>
						<span>[<a href="javascript:wxyopen(wchome+'/users/edituser.php?id={$auth->userLogin}',600)" title="{$auth->userLogin}">{$auth->userName}</a>]</span>
					</p>
					<ul>
						<li class="about"><a href="javascript:aboutOpenConstructor()" title="{$smarty.const.ABOUT_WC}">{$smarty.const.ABOUT_WC}</a></li>
						<li class="settings"><a href="{$ocm_home}/setup/" title="{$smarty.const.WC_SETUP}">{$smarty.const.WC_SETUP}</a></li>
						<li class="switch-user"><a href="javascript:switch_user()" title="{$smarty.const.SWITCH_USER}">{$smarty.const.SWITCH_USER}</a></li>
						<li class="logout"><a href="{$ocm_home}/logout.php" title="{$smarty.const.LOGOUT}">{$smarty.const.LOGOUT}</a></li>
					</ul>
					<br class="clear" />
				</div>
				<div class="tabs">
					<ul>
						{foreach from=$menu key=uri item=title}
							{if $uri eq $cur_section}
								<li class="cur">{$title}</li>
							{else}
								<li><a href="{$ocm_home}/{$uri}">{$title}</a></li>
							{/if}
						{/foreach}
					</ul>
					<br class="clear" />
				</div>
				<div class="tabs-bg"></div>
				<div class="toolbar">
					<img class="first" src="{$img}/tbar/beginner.gif" alt="" />
						<ul>
							{foreach from=$toolbar key=key item=val}
								{if $val == "separator"}
									</ul><img src="{$img}/tbar/separator.gif" alt="|" /><ul>
								{else}
									{assign var="act" value=""}
									{if $val.action neq ""}
										{assign var="act" value="javascript:`$val.action`"}
									{/if}
									<li>
										{if $act neq ""}
											<a href='{$act}' id={$val.pic}>
												<img src="{$img}/tbar/{$val.pic}.gif" alt="{$key}" id="btn_{$val.pic}" />
											</a>
										{else}
											<img src="{$img}/tbar/{$val.pic}_.gif" alt="{$key}" name="btn_{$val.pic}" />
										{/if}
									</li>
								{/if}
							{/foreach}
						</ul>
					<div class="select-template">
						<p>{$smarty.const.PR_PAGE_TPL}:</p>
						<select id="tplId" name="select-template" {if !$super and 	!$WCS->decide($page, 'editpage')}disabled="disabled"{/if}>
							<option value="0">{$smarty.const.H_NO_TPL_SELECTED}</option>
							{foreach from=$tpls key=id item=name}
								<option value="{$id}" {if $id eq $page->tpl}selected="selected"{/if}>{$name|escape}</option>
							{/foreach}
						</select>
						{if $tpl}
							<a href="javascript:editTpl('{$page->tpl}');" class="inline"><img src="{$img}/tbar/edittpl.gif" alt="edit" /></a>
						{else}
							<img src="{$img}/tbar/edittpl_.gif" alt="" />
						{/if}
					</div>
					<br class="clear" />
				</div>
			</div>
			<table class="center">
				<tr>
					<td class="left">
						<script src="{$skinhome}/js/jquery.treeview.js" type="text/javascript"></script>
						<script src="{$skinhome}/js/jquery.cookie.js" type="text/javascript"></script>
						{literal}
						<script type="text/javascript">
							$(document).ready(function(){
								$("#tree").treeview({
									persist: "location"
								});
								$("a.selected").click(function(){
									return false;
								});
							});
						</script>
						{/literal}
						{foreach from=$tree key=i item=node}
							{set unpub=""}
							{if !$node.published}{set unpub="-unpub"}{/if}
								{if $node.level eq 0}
									{if $node.at eq 1}
										<strong class="tree-name {$cur_section}">{$node.title|escape}</strong>
									{else}
										<a id="{$node.id}" name="{$node.title|escape}" href="?node={$node.id}" title="{$node.uri}" class="tree-name {$cur_section}">{$node.title|escape}</a>
									{/if}
									<ul id="tree" class="filetree">
								{else}
									{if $node.level gt $prevLev && $prevLev neq 0}
										<ul style="display: none;">
									{elseif $node.level lt $prevLev && $prevLev neq 0}
										{math assign="i" equation="pl - nl" nl=$node.level pl=$prevLev}
										{section name=cycle loop=$i}
												</ul>
											</li>
										{/section}
									{/if}
									{if $node.children|@count gt 0}
										<li class="{if $node.obj->isLastChild()}last{/if}">
											<span class="folder{$unpub}">
												{if $node.at eq 1}
													<a href="?node={$node.id}"><strong>{$node.title|escape}</strong></a>
												{else}
													<a id="{$node.id}" name="{$node.title|escape}" href="?node={$node.id}" title="{$node.uri}">{$node.title|escape}</a>
												{/if}
											</span>
									{else}
										<li class="{if $node.obj->isLastChild()}last{/if}">
											<span class="file{$unpub}">
												{if $node.at eq 1}
													<a href="?node={$node.id}"><strong>{$node.title|escape}</strong></a>
												{else}
													<a id="{$node.id}" name="{$node.title|escape}" href="?node={$node.id}" title="{$node.uri}">{$node.title|escape}</a>
												{/if}
											</span
										</li>
									{/if}
								{/if}
								{assign var="prevLev" value=$node.level}
						{/foreach}
						</ul>
					</td>
					<td class="middle">
						<form name="f_doc" action="{$ocm_home}/structure/i_structure.php" method="POST">
							<input type="hidden" name="action" value="save_blocks" />
							<input type="hidden" name="uri_id" value="{$page->id}" />
							<input type="hidden" name="tplId" value="{$page->tpl}" />
							<table>
								<thead>
									<tr id="hlhead">
										<td class="checkbox">
											<input type="checkbox" onclick="doall()" title="{$smarty.const.SELECT_ALL}" id="checkall" name="checkall" />
										</td>
										{foreach name=header from=$fields key=key item=title}
											{if !($title === (1 == 1))}
												<td {if $key eq 'crumbs'}width="9%"{/if}>{if !$smarty.foreach.header.last}<p class="divider"></p>{/if}{$title}</td>
											{/if}
										{/foreach}
									</tr>
								</thead>
								{foreach from=$obj key=id item=val}
									<tr id="r_{$id}" class="{cycle values="odd,even"}">
										{assign var="b" value=""}
										{foreach from=$fields key=key item=title}
											{if !($title === (1 == 1))}
												{if !$b}
													<td class="checkbox">
														<input type="checkbox" name="ids[]" value="{$id}" id="ch_{$id}" onclick="chk(this)" />
													</td>
													<td>
														<img src="{$img}/f/{$icon.$id}.gif" class="name-icon" />
														<a href="{$editor}&id={$id}" onclick="wxyopen(this.href,{$editor_width},{$editor_height});return false;" {if !$val.published}class="dis"{/if}>{$val.$key|escape}</a>
														{if $val.description && $fields.description} $val.description {/if}
													</td>
												{else}
													<td {if $key eq 'class'}class="type-sitemap"{elseif $key eq 'crumbs'}class="crumbs"{else}class="block-sitemap"{/if}>{$val.$key}</td>
												{/if}
												{assign var="b" value=true}
											{/if}
										{/foreach}
									</tr>
								{/foreach}
							</table>
						</form>
						<p class="site-link">
							{$smarty.const.HL_OPEN_IN_NEW_WINDOW}
							<a href="{$pageHref}" target="_blank">http://{$smarty.server.HTTP_HOST}{$pageHref}</a>
						</p>
						<div>
							{if $tpl && $tpl->mockup gt ''}
								<div class="mockGroup"><div>{$tpl->mockup}</div></div>
							{elseif !$tpl && $page->tpl gt 0}
								<span>{$smarty.const.TPL_NOT_FOUND_W}</span>
							{/if}
						</div>
					</td>
					<td class="ushko">
						<a class="ushko" href="javascript:if(document.getElementById('right').style.display=='none') {document.getElementById('right').style.display='';} else {document.getElementById('right').style.display='none';};void(0);"><img alt="" src="images/vbkmrk_state_inline.gif" /></a>
					</td>
					<td class="right" id="right">
						<a href="javascript:document.getElementById('right').style.display='none';void(0);">?</a>
						<br class="clear" />
						<fieldset>
							<legend>Поиск документов</legend>
							<form method="get" action="">
								<div><input type="text" /><input id="search" type="submit" value="Искать" /></div>
							</form>
						</fieldset>
						<form method="get" action="" name="frm_v" onsubmit="rf_view();return false;">
							<fieldset>
								<legend>{$smarty.const.VIEW}</legend>
								<div><input id="show-uri" type="checkbox" /><label for="show-uri">Показывать URI</label></div>
								<div><input id="show-date" type="checkbox" name="date" /><label for="show-date">Показывать дату</label></div>
							</fieldset>
							<fieldset>
								<legend>{$smarty.const.INFO}</legend>
								<input type="text" name="pagesize" value="30" size="2" maxlength="3" />
							</fieldset>
						</form>
						<input type="button" class="refresh" value="{$smarty.const.REFRESH}" />
					</td>
				</tr>
			</table>
			<div class="footer">
				<p>{$smarty.const.WC_COPYRIGHTS}</p>
			</div>
			<script type="text/javascript">
				disableButton(btn_remove,imghome+'/tool/remove_.gif');
				disableButton(btn_editsec,imghome+'/tool/editsec_.gif');
				function rf_view() {					<?php
						if(is_array(@$fieldnames))
							foreach($fieldnames as $k=>$v)
								echo 'if(!frm_v.'.$k.'.checked) setCookie("vf['.$k.']","disabled","'.WCHOME.'/objects/"); else setCookie("vf['.$k.']","enabled","'.WCHOME.'/objects/");';
					?>
					setCookie("pagesize",frm_v.pagesize.value,"<?=WCHOME?>/objects/");
					window.location.reload();
				}
			</script>
		</div>
	</body>
</html>