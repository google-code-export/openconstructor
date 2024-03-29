{*$tree|@debug_print_var*}
{set true = "true"|bool}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.TEMPLATES}</title>
		<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
		<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
		<base href="http://{$smarty.server.SERVER_NAME}{$ocm_home}/{$cur_section}/" />
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/common.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/{$cur_section}.js"></script>
	</head>
	<body>
		<script>
			var
				wchome = "{$ocm_home}",
				skin = "{$smarty.const.SKIN}",
				imghome = wchome+'/i/'+skin+'/images',
				curnode="{$curnode}",
				nodetype="{$nodetype}",
				REMOVE_SELECTED_TEMPLATES_Q = "{$smarty.const.REMOVE_SELECTED_TEMPLATES_Q}"
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
							$(document).ready(function(){								/* For tree */

								$("#tree").treeview({
									persist: "curnode",
									link_unfold_onclick: true
								});
								$("a.selected").click(function(){
									return false;
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

								/* For right panel */

								$("p.ushko").click(function(){									if($("#right").css('display') == 'none'){
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

								$("#f_search").submit(function(){									$("#search_btn").attr('disabled',true);
								});
							});
						</script>
						{/literal}
						{foreach from=$map key=i item=node}
							{if $node.level eq 0}
								<a id="{$node.id}" name="{$node.title|escape}" href="" title="{$node.uri}" class="tree-name {$cur_section}">{$node.title|escape}</a>
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
								{if $node.level eq 1}
									<li>
										<span class="folder">
											<span class="fldname"><a id="{$node.id}" name="{$node.title|escape}" title="{$node.uri}"><strong class="notbold">{$node.title|escape}</strong></a></span>
										</span>
								{else}
									<li>
										<span class="file"><span class="fldname">
											{if $node.at eq 2}
												<a href="?node={$node.id}" id="{$node.id}"><strong>{$node.title|escape}</strong></a>
											{else}
												<a id="{$node.id}" name="{$node.title|escape}" href="?node={$node.id}" title="{$node.uri}">{$node.title|escape}</a>
											{/if}
										</span></span>
									</li>
								{/if}
							{/if}
							{assign var="prevLev" value=$node.level}
						{/foreach}
						</ul>
					</td>
					<td class="middle">
						<form name="f_doc" action="{$ocm_home}/templates/i_templates.php?dstype={$nodetype}" method="POST">
							<input type="hidden" name="action" value="delete_tpl" />
							<table>
								<thead>
									<tr>
										<td class="checkbox">
											<input type="checkbox" onclick="doall()" title="{$smarty.const.SELECT_ALL}" id="checkall" name="checkall" />
										</td>
										{foreach name=header from=$fields key=key item=title}
											{if !($title === $true)}
												<td class="{if $key eq 'date'}date{elseif $key eq 'tpl'}file{else}name-object{/if}">{$title}</td>
											{/if}
										{/foreach}
									</tr>
								</thead>
								{foreach from=$objs key=id item=val}
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
														<a href="{$editor}&id={$id}" onclick="wxyopen(this.href,{$editor_width},{$editor_height});return false;" {if !$val.published}class="dis"{/if}>{$val.$key|escape}</a>
														<p class="des">{if $val.description && $fields.description}{$val.description}{/if}</p>
													</td>
												{else}
													<td class="{if $key eq 'date'}date{elseif $key eq 'tpl'}file{/if}">{$val.$key}</td>
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
					</td>
					<td class="ushko">
						<p class="ushko"><img alt="" src="{$img}/vbkmrk_state_inline.gif" /></p>
					</td>
					<td class="right" id="right">
						<img src="{$img}/close.gif" class="close" alt="{$smarty.const.HIDE_PANEL}" title="{$smarty.const.HIDE_PANEL}" />
						<br class="clear" />
						<fieldset>
							<legend>{$smarty.const.RP_SEARCH_FOR_TEMPLATES}</legend>
							<form method="get" action="" id="f_search" name="f_search">
								<div>
									<input type="text" name="search" size="19" value="{$search_text}" />
									<input id="search_btn" name="s" type="submit" value="{$smarty.const.START_SEARCH}" />
								</div>
							</form>
						</fieldset>
						<form method="post" action="{$ocm_home}/templates/i_templates.php" name="frm_v">
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
				disableButton("btn_remove",imghome+"/tbar/remove_.gif");
				disableButton("btn_editsec",imghome+"/tbar/editsec_.gif");
			</script>
		</div>
	</body>
</html>