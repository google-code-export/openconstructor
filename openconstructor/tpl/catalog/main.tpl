{set true = "true"|bool}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.CATALOG}{$ds_name}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<base href="http://{$smarty.server.SERVER_NAME}{$ocm_home}/{$cur_section}/" />
		<script type="text/javascript">
			var
				wchome = "{$ocm_home}",
				skin = "{$smarty.const.SKIN}",
				imghome = wchome+'/i/'+skin+'/images',
				curTab = "{$curtab}",
				curDS = "{$currentDs}",
				curnode = "{$curnode}",
				preset = "{$presetValue}",
				rootNode = {if $curnode > 1 && $curnode == $rootNode->id}{$curnode}{else}0{/if},
				REMOVE_NODE_Q = "{$smarty.const.REMOVE_NODE_Q}",
				SURE_REMOVE_NODE_Q = "{$smarty.const.SURE_REMOVE_NODE_Q}",
				REMOVE_SELECTED_DOCUMENTS_Q = "{$smarty.const.REMOVE_SELECTED_DOCUMENTS_Q}",
				PUBLISH_SELECTED_DOCUMENTS_Q = "{$smarty.const.PUBLISH_SELECTED_DOCUMENTS_Q}",
				UNPUBLISH_SELECTED_DOCUMENTS_Q = "{$smarty.const.UNPUBLISH_SELECTED_DOCUMENTS_Q}"
				;
		</script>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$ocm_home}/lib/js/base.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/common.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/{$cur_section}.js"></script>
	</head>
	<body>
		<form name="f_remove" method="POST" action="{$ocm_home}/catalog//i_catalog.php" style="margin:0">
			<input type="hidden" name="action" value="remove_node"/>
			<input type="hidden" name="node_id" value="{$curnode}"/>
		</form>
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
					<select size="1" onchange="setCookie('dsh', this.options[this.selectedIndex].value, wchome + '/'); location.href = location.href;" style="margin-right:10px;">
					{foreach from=$dsh item=v}
						<option value="{$v.id}"{if $v.id == $currentDs} selected{/if}>{$v.pathView}{$v.name}</option>
					{/foreach}
					</select>
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
					<br class="clear" />
				</div>
			</div>
			<table class="center">
				<tr>
					<td class="left">
						<ul class="cat-tabs">
							{if $curtab eq "browse"}
							<li class="cur">{$smarty.const.H_BROWSE_TAB}</li>
							<li><a href="{$ocm_home}/catalog/index.php/trees/">{$smarty.const.H_TREES_TAB}</a></li>
							{/if}
							{if $curtab eq "trees"}
							<li><a href="{$ocm_home}/catalog/index.php/browse/">{$smarty.const.H_BROWSE_TAB}</a></li>
							<li class="cur">{$smarty.const.H_TREES_TAB}</li>
							{/if}
						</ul>
						<script src="{$skinhome}/js/jquery.treeview.js" type="text/javascript"></script>
						<script src="{$skinhome}/js/jquery.cookie.js" type="text/javascript"></script>
						{if $curtab == 'browse'}
							{literal}
							<script type="text/javascript">
								$(document).ready(function(){
									/* For tree */
							{/literal}
									{foreach from=$tree item=node}
										{foreach from=$node item=n}
											{if $n->parent->id == 1}
												$("#tree-{$n->key}").treeview(
												{literal}
												{
													persist: "multiple"
												}
												{/literal}
												);
												$("a.{$n->key}").toggle(function()
													{literal}
													{
													{/literal}
														$("#tree-{$n->key}").hide();
														return false;
													{literal}
													},
													function(){
													{/literal}
														$("#tree-{$n->key}").show();
														return false;
													{literal}
													}
													{/literal}
												);
											{/if}
										{/foreach}
									{/foreach}
						{else}
							{literal}
							<script type="text/javascript">
								$(document).ready(function(){
									/* For tree */
									$("#tree").treeview({
										persist: "curnode"
									});
									$("a.tree-name").toggle(function(){
											$("#tree").hide();
											return false;
										},
										function(){
											$("#tree").show();
											return false;
										}
									);
									$("a.selected").click(function(){
										return false;
									});
							{/literal}
						{/if}
						{literal}
								$(".folder img").click(function(){
									nodeChecked($(this));
								});
								$(".file img").click(function(){
									nodeChecked($(this));
								});
								/* For right panel */

								$("p.ushko").click(function(){
									if($("#right").css('display') == 'none'){
										$("#right").css('display', '');
										$(this).children().attr('src',imghome+'/vbkmrk_state_inline.gif');
									}
									else{
										$("#right").css('display', 'none');
										$(this).children().attr('src',imghome+'/vbkmrk_state_none.gif');
									}
								});

								$("img.close").click(function(){
									$("#right").css('display', 'none');
									$("p.ushko").children().attr('src',imghome+'/vbkmrk_state_none.gif');
								});

								$("#f_search").submit(function(){
									$("#search_btn").attr('disabled',true);
								});
							});
						</script>
						{/literal}
						{if $curtab == 'browse'}
							{foreach from=$treeFields item=nodeId key=fieldName}
								{if $tree->exists($nodeId)}
								{set sub = $tree->getSubTree($nodeId)}
								{$sub->export($view)}
								{/if}
							{/foreach}
							<div class="hr">
								<div>
									<input type="button" value="Apply" onclick="applyNodeFilter()" />
								</div>
							</div>
							<script type="text/javascript">
							{foreach from=$selected item=id}
								{if $tree->node.$id}
									setNodeState({$id},1);
								{/if}
							{/foreach}
							</script>
						{else}
							{$tree->export($view)}
						{/if}
					</td>
					<td class="middle">
						{if $curnode}
						<form name="f_doc" action="{$ocm_home}/data/hybrid/i_hybrid.php" method="POST">
							<input type="hidden" name="action" value="delete_hybrid" />
							<input type="hidden" name="ds_id" value="{$currentDs}" />
							<input type="hidden" name="dest_ds_id" value="{$currentDs}" />
							<table>
								<thead>
									<tr>
										<td class="checkbox">
											<input type="checkbox" onclick="doall()" title="{$smarty.const.SELECT_ALL}" id="checkall" name="checkall" />
										</td>
										{foreach name=header from=$fields key=key item=title}
											{if !($title === $true)}
												<td class="{if $key eq 'date'}date{else}name-object{/if}">{$title}</td>
											{/if}
										{/foreach}
									</tr>
								</thead>
								{foreach from=$docs key=id item=val}
									<tr id="r_{$id}" class="{cycle values="odd,even"}">
										{assign var="b" value=""}
										{foreach from=$fields key=key item=title}
											{if !($title === $true)}
												{if !$b}
													<td class="checkbox">
														<input type="checkbox" name="ids[]" value="{$id}" id="ch_{$id}" onclick="chk(this)" />
													</td>
													<td>
														<img src="{$img}/f/{$icon}.gif" class="name-icon" />
														<a href="{$editor}{if $val.ds_id}&ds_id={$val.ds_id}{/if}&id={$id}" onclick="wxyopen(this.href,{$editor_width},{$editor_height});return false;" {if !$val.published}class="dis"{/if}>{$val.$key|escape}</a>
														<p class="des">{if $val.description && $fields.description}{$val.description}{/if}</p>
													</td>
												{else}
													<td {if $key eq 'date'}class="date"{/if}>{$val.$key}</td>
												{/if}
												{assign var="b" value=true}
											{/if}
										{/foreach}
									</tr>
								{/foreach}
								<tr class="itogo">
									<td colspan="5">
										{if $pager.pages|@sizeof gt 1}
											<ul>
												<li>
													{if $pager.first}
														<a href="{$pager.first}"><img src="{$img}/p/first.gif" alt="{$smarty.const.GOTO_FIRST_PAGE}" title="{$smarty.const.GOTO_FIRST_PAGE}"/></a>
													{else}
														<img src="{$img}/p/first.gif" alt="{$smarty.const.GOTO_FIRST_PAGE}" title="{$smarty.const.GOTO_FIRST_PAGE}"/>
													{/if}
												</li>
												<li>
													{if $pager.prev}
														<a href="{$pager.prev}"><img src="{$img}/p/prev.gif" alt="{$smarty.const.GOTO_PREVIOUS_PAGE}" title="{$smarty.const.GOTO_PREVIOUS_PAGE}"/></a>
													{else}
														<img src="{$img}/p/prev.gif" alt="{$smarty.const.GOTO_PREVIOUS_PAGE}" title="{$smarty.const.GOTO_PREVIOUS_PAGE}"/>
													{/if}
												</li>
												{foreach from=$pager.pages key=p item=href}
													{if $href}
														<li class="pages"><a href="{$href}" title="{$p}">{$p}</a>
													{else}
														<li class="page-cur">{$p}</li>
													{/if}
												{/foreach}
												<li>
													{if $pager.next}
														<a href="{$pager.next}"><img src="{$img}/p/next.gif" alt="{$smarty.const.GOTO_NEXT_PAGE}" title="{$smarty.const.GOTO_NEXT_PAGE}"/></a>
													{else}
														<img src="{$img}/p/next.gif" alt="{$smarty.const.GOTO_NEXT_PAGE}" title="{$smarty.const.GOTO_NEXT_PAGE}"/>
													{/if}
												</li>
												<li>
													{if $pager.last}
														<a href="{$pager.last}"><img src="{$img}/p/last.gif" alt="{$smarty.const.GOTO_LAST_PAGE}" title="{$smarty.const.GOTO_LAST_PAGE}"/></a>
													{else}
														<img src="{$img}/p/last.gif" alt="{$smarty.const.GOTO_LAST_PAGE}" title="{$smarty.const.GOTO_LAST_PAGE}"/>
													{/if}
												</li>
											</ul>
										{/if}
										<p>{$smarty.const.TOTAL} {$pager.items}</p>
									</td>
								</tr>
							</table>
						</form>
						{/if}
					</td>
					<td class="ushko">
						<p class="ushko"><img alt="" src="{$img}/vbkmrk_state_inline.gif" /></p>
					</td>
					<td class="right" id="right">
						<img src="{$img}/close.gif" class="close" alt="{$smarty.const.HIDE_PANEL}" title="{$smarty.const.HIDE_PANEL}" />
						<br class="clear" />
						<fieldset>
							<legend>{$smarty.const.RP_SEARCH_FOR_DOCUMENTS}</legend>
							<form method="get" action="." id="f_search" name="f_search">
								<div>
									<input type="text" name="search" size="19" value="{$search_text}" />
									<input id="search_btn" name="s" type="submit" value="{$smarty.const.START_SEARCH}" />
								</div>
							</form>
						</fieldset>
						<form method="post" name="frm_v" action="{$ocm_home}/catalog/i_catalog.php">
							<input type="hidden" name="action" value="view_detail" />
							<fieldset>
								<legend>{$smarty.const.VIEW}</legend>
								{foreach from=$fieldnames item=val}
									<div>
										<input name="vdetail[{$val.name}]" id="{$val.name}" type="checkbox" value="true" {if $val.st}checked{/if} />
										<label for="{$val.name}">{$val.title}</label>
									</div>
								{/foreach}
							</fieldset>
							<fieldset>
								<legend>{$smarty.const.INFO}</legend>
								<input type="text" name="pagesize" id="pagesize" value="{$pagesize}" size="2" maxlength="3" />
							</fieldset>
							<input type="submit" class="refresh" value="{$smarty.const.REFRESH}" />
						</form>
					</td>
				</tr>
			</table>
			<div class="footer">
				<p>{$smarty.const.WC_COPYRIGHTS}</p>
			</div>
			<script type="text/javascript">
//				disableButton("btn_addrecord",imghome+'/tbar/addrecord_.gif');
				disableButton("btn_publish",imghome+'/tbar/publish_.gif');
				disableButton("btn_unpublish",imghome+'/tbar/unpublish_.gif');
				if(!rootNode)
					disableButton("btn_editsec",imghome+'/tbar/editsec_.gif');
				if({if $curtab == 'browse'}1{else}0{/if})
					disableButton("btn_remove",imghome+'/tbar/remove_.gif');
			</script>
		</div>
	</body>
</html>