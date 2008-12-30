{*$tree|@debug_print_var*}
{set true = "true"|bool}
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
		<script type="text/javascript" src="{$skinhome}/js/{$cur_section}.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/common.js"></script>
	</head>
	<body>
		<script>
			var
				wchome = "{$ocm_home}",
				skin = "{$smarty.const.SKIN}",
				imghome = wchome+'/i/'+skin+'/images',
				curnode = {$page->id},
				isVersion ={if $page->id eq $sitemap->root->id} 'true' {else} 'false'{/if},
				REMOVE_PAGE_Q = "{$smarty.const.REMOVE_PAGE_Q}",
				SURE_REMOVE_PAGE_Q = "{$smarty.const.SURE_REMOVE_PAGE_Q}",
				EXCLUDE_SELECTED_OBJECTS_Q = "{$smarty.const.EXCLUDE_SELECTED_OBJECTS_Q}",
				YOU_CANNOT_REMOVE_SITEROOT_W = "{$smarty.const.YOU_CANNOT_REMOVE_SITEROOT_W}",
				pri1 = new Array(),
				ob = new Array()
				;
			pri1[0] = new Image; pri1[0].src = imghome + '/t/fg.gif';
			pri1[1] = new Image; pri1[1].src = imghome + '/t/fg_.gif';
		</script>
		<form name="f_remove" method="POST" action="{$ocm_home}/structure/i_structure.php">
			<input type="hidden" name="action" value="remove_page" />
			<input type="hidden" name="page_id" value="{$page->id}" />
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
											<span class="folder{$unpub}"><span class="fldname">
												{if $node.at eq 1}
													<a href="?node={$node.id}"><strong>{$node.title|escape}</strong></a>
												{else}
													<a id="{$node.id}" name="{$node.title|escape}" href="?node={$node.id}" title="{$node.uri}">{$node.title|escape}</a>
												{/if}
											</span></span>
									{else}
										<li class="{if $node.obj->isLastChild()}last{/if}">
											<span class="file{$unpub}"><span class="fldname">
												{if $node.at eq 1}
													<a href="?node={$node.id}"><strong>{$node.title|escape}</strong></a>
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
											{if !($title === $true)}
												<td {if $key eq 'crumbs'}width="9%"{/if}>{if !$smarty.foreach.header.last}<p class="divider"></p>{/if}{$title}</td>
											{/if}
										{/foreach}
									</tr>
								</thead>
								{foreach from=$obj key=id item=val}
									<tr id="r_{$id}" class="{cycle values="odd,even"}">
										{assign var="b" value=""}
										{foreach from=$fields key=key item=title}
											{if !($title === $true)}
												{if !$b}
													<td class="checkbox">
														<input type="checkbox" name="ids[]" value="{$id}" id="ch_{$id}" onclick="chk(this)" />
													</td>
													<td>
														<img src="{$img}/f/{$icon.$id}.gif" class="name-icon" />
														<a href="{$editor}&id={$id}" onclick="wxyopen(this.href,{$editor_width},{$editor_height});return false;" {if !$val.published}class="dis"{/if}>{$val.$key|escape}</a>
														{if $val.description && $fields.description}{$val.description}{/if}
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
							<script type="text/javascript">
								var blocks = [{if $objIds|@sizeof gt 0}"{'", "'|join:$objIds}"{else}''{/if}];
								{set stb=$stubs|@array_keys}
								var stubs = [{if $stubs|@sizeof gt 0}"{'", "'|join:$stb}"{else}''{/if}];
								for(var i = 0; i < blocks.length; i++)
									document.getElementById("block" + blocks[i]).onchange();
							</script>
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
				</tr>
			</table>
			<div class="footer">
				<p>{$smarty.const.WC_COPYRIGHTS}</p>
			</div>
			{if $page->id eq $sitemap->root->id}
				<script>
					disableButton("btn_remove",imghome+"/tbar/remove_.gif");
				</script>
			{/if}
		</div>
	</body>
</html>