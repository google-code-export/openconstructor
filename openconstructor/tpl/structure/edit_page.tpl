{*$objs|@debug_print_var*}
{assign var="ocm_home" value="/openconstructor"}
{assign var="skinhome" value="$ocm_home/i/newskin"}
{assign var="img" value="$skinhome/images"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.H_EDIT_PAGE} {$page->uri}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script language="Javascript" src="{$skinhome}/js/js/base.js"></script>
	</head>
	<body style="border-style:groove;padding:0 20 20">
		<script language="JavaScript" type="text/JavaScript">
			var page_id = {$page->id}, dis = {if $super or $WCS->decide($page, 'managesub')}false{else}true{/if};
			{literal}
				var re=new RegExp('[^\\s]','gi');
				var old_value = '';
                $(document).ready(function(){                	$("#close").click(function(){
						window.close();
					});
					$("#ch_caching").click(function(){
						$("#fs_caching *").attr('disabled',!$(this).attr('checked'));
					});

					$("#fs_caching *").attr('disabled',!$(this).attr('checked'));

					$("#chk_location").click(function(){
						$("#location").attr('disabled',!$(this).attr('checked'));
					});

					$(".dsb").keyup(function(){
						if(!$("#header").val().match(re) || !$("#uri_name").val().match(re) || dis)
							$("#save").attr('disabled',true);
						else
							$("#save").attr('disabled',false);
					});

					$("#published").change(function(){						if($(this).attr('selectedIndex') == 0){
                			old_value = $("#f_recursive").attr('checked');
                			$("#f_recursive").attr('checked', true);
                			$("#f_recursive").attr('disabled', true);
                		}
                		else{                			$("#f_recursive").attr('checked', old_value);
                			$("#f_recursive").attr('disabled', false);
                		}					});

					$("#template-link").click(function(){						if($("#template").val() > 0)
							wxyopen('../templates/editpage.php?dstype=site&id=' + $("#template").val(), 660);
					});

					$("#cache_vary").click(function(){						var res = openModal('cachevary.php?id='+page_id, 460, 350);
						if(res.length) {							$("#cacheVary").text('');
							for(var i = 0; i < res.length; i++)
								$("#cacheVary").append(res[i] + "\n");
						}
					});

					$("#select_all").click(function(){						$("#users option").each(function(){							this.selected = true;						});
					});

					$("#unselect_all").click(function(){
						$("#users option").each(function(){
							this.selected = false;
						});
					});

					$("#profiles_inherit").click(function(){
						$("#fs_profiles *").attr('disabled', $(this).attr('checked'));
					});

					$("#fs_profiles *").attr('disabled', !$(this).attr('checked'));
				});

				function wxyopen(uri,x,y) {
					window.open(uri,'',"resizable=yes, scrollbars=yes, status=yes" + (y > 0 ? ", height=" + y : "") + (x > 0 ? ", width=" + x : ""));
				}
			{/literal}
		</script>
		<br />
		<h3>{$smarty.const.H_EDIT_PAGE} {$page->uri}</h3>
		<form name="f" method="POST" action="i_structure.php">
			<input type="hidden" name="uri_id" value="{$page->id}" />
			<input type="hidden" name="action" value="edit_page" />
			<fieldset style="padding:10" {if !($super or $WCS->decide($page, 'editpage.uri'))}disabled{/if}><legend>URI</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>URI:</td>
						<td><b>{$page->uri}</b></td>
					</tr>
					{if $page->uri neq '/'}
						<tr>
							<td>{$smarty.const.PAGE_FOLDER}:</td>
							<td><input type="text" name="uri_name" id="uri_name" value="{$page->name}" size="32" maxlength="32" class="dsb" /></td>
						</tr>
					{else}
						<tr><td colspan="2"><input type="hidden" name="uri_name" value="{$page->name}" /></td></tr>
					{/if}
					<tr>
						<td>
							{if $page->linkTo}
								<a href="?node={$page->linkTo}">{$smarty.const.PAGE_REDIRECT_URI}</a>
							{else}
								{$smarty.const.PAGE_REDIRECT_URI}
							{/if}
							:
						</td>
						<td>
							<input type="checkbox" id="chk_location" {if $page->location}checked{/if} />
							<input type="text" name="location" id="location" size="60" value="{$page->location}" {if !$page->location}disabled{/if} />
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="checkbox" name="router" id="f.router" value="true" {if $page->uri eq '/'}disabled{/if} {if $page->router}checked{/if} /> <label for="f.router">{$smarty.const.PAGE_ROUTE}</label></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.PAGE_GENERAL}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>{$smarty.const.PAGE_NAME}:</td>
						<td><input type="text" name="header" id="header" value="{$page->header|escape}" size="40" maxlength="64" class="dsb" /></td>
					</tr>
					<tr>
						<td>{$smarty.const.PAGE_STATUS}:</td>
						<td>
							<select size="1" name="published" id="published" {if !(($super or $WCS->decide($page, 'editpage.uri')) and (!$page->parent or $pr->isPublished($page->parent)))}disabled{/if}>
								<option value="false">{$smarty.const.PAGE_IS_UNPUBLISHED}</option>
								<option value="true" {if $page->published}selected{/if}>{$smarty.const.PAGE_IS_PUBLISHED}</option>
							</select>
							<input type="checkbox" name="recursive" id="f_recursive" value="true" {if !$page->published}checked disabled{/if} {if !($super or $WCS->decide($page, 'editpage.publish'))}disabled{/if} /> <label for="f_recursive">{$smarty.const.PAGE_STATUS_AFFECTS_CHILDREN}</label>
						</td>
					</tr>
					<tr>
						<td><a href="#" id="template-link">{$smarty.const.PAGE_TEMPLATE}</a>:</td>
						<td><select size="1" name="template" id="template">
								<option value="0" style="background:#eee;color: gray;">{$smarty.const.H_NO_TPL_SELECTED}</option>
								{foreach from=$tpls key=id item=name}
									<option value="{$id}" {if $id eq $page->tpl}selected{/if}>{$name}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							{if $tpl and $tpl->mockup gt ''}
								<div class="mockGroup">{$tpl->mockup}</div>
							{elseif !$tpl and $page->tpl gt 0}
								<span style="color:red;">{$smarty.const.TPL_NOT_FOUND_W}</span>
							{/if}
						</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.PAGE_WEB}</legend>
				<table style="margin:5 0" cellspacing="3" width="100%">
					<tr>
						<td>{$smarty.const.PAGE_TITLE}:</td>
						<td><input type="text" name="title" value="{$page->title|escape}" maxlength="128" style="width: 100%;font-family: monospace; font-size: 100%;"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="checkbox" name="appendparent" id="f.appendparent" value="true" {if $page->addTitle}checked{/if} {if $page->uri eq '/'}disabled{/if} /> <label for="f.appendparent">{$smarty.const.PAGE_ADD_PARENTS_TITLE}</label><p></td>
					</tr>
					<tr>
						<td valign="top">
							{$smarty.const.PAGE_CSS}:
							<div style="font-size:90%;">{"`$smarty.const.FILES`/css"|string_format:$smarty.const.TT_PAGE_CSS}</div>
						</td>
						<td>
							<select size="5" name="css[]" multiple>
								{html_options values=$css_files output=$css_files selected=$css_selected}
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
			<br />
			<fieldset style="padding:10"><legend>{$smarty.const.PAGE_META_INFO}</legend>
			<table style="margin:5 0" cellspacing="3">
				<tr>
					<td>{$smarty.const.PAGE_CONTENT_TYPE}:</td>
					<td><select size="1" name="contentType" style="font-family: monospace;">
							{if $page->uri neq '/'}
								<option value="" style="background:#eee;color:#888;">{$smarty.const.PAGE_INHERIT_CONTENT_TYPE}</option>
							{/if}
							{foreach from=$types item=type}
								<option value="{$type}" {if $type eq $page->contentType}selected{/if}>{$type}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td valign="top">{$smarty.const.PAGE_META_KEYWORDS}:</td>
					<td><textarea cols="51" rows="5" name="m_keywords">{$page->meta.keywords|escape}</textarea></td>
				</tr>
				<tr>
					<td valign="top">{$smarty.const.PAGE_META_DESCRIPTION}:</td>
					<td><textarea cols="51" rows="5" name="m_description">{$page->meta.description|escape}</textarea></td>
				</tr>
				<tr>
					<td>{$smarty.const.PAGE_ROBOTS}:</td>
					<td><select size="1" name="robots">
						<option value="{$smarty.const.ROBOTS_I_F}" {if $page->robots eq $smarty.const.ROBOTS_I_F}selected{/if}>INDEX, FOLLOW</option>
						<option value="{$smarty.const.ROBOTS_I_NOF}" {if $page->robots eq $smarty.const.ROBOTS_I_NOF}selected{/if}>INDEX, NOFOLLOW</option>
						<option value="{$smarty.const.ROBOTS_NOI_F}" {if $page->robots eq $smarty.const.ROBOTS_NOI_F}selected{/if}>NOINDEX, FOLLOW</option>
						<option value="{$smarty.const.ROBOTS_NOI_NOF}" {if $page->robots eq $smarty.const.ROBOTS_NOI_NOF}selected{/if}>NOINDEX, NOFOLLOW</option>
					</selects>
					</td>
				</tr>
			</table>
			</fieldset>
			<br />
			<fieldset style="padding:10" {if !($super or $WCS->decide($page, 'editpage.security'))}disabled{/if}><legend>{$smarty.const.PAGE_CACHING}</legend>
				<input type="checkbox" id="ch_caching" name="caching" value="true" style="margin-top: 10px;" {if $page->caching}checked="checked"{/if} />
					<label for="ch_caching">{$smarty.const.PAGE_ENABLE_CACHING}</label>
				<fieldset id="fs_caching" style="padding:10" {if !($super or $WCS->decide($page, 'editpage.security'))}disabled{/if}><legend>{$smarty.const.PAGE_CACHE_PROPS}</legend>
					<table style="margin:5 0" cellspacing="3">
						<tr>
							<td>{$smarty.const.PAGE_CACHE_LIFETIME}:</td>
							<td><input type="text" name="cacheLife" value="{$page->cacheLife|string_format:'%d'}"></td>
						</tr>
						<tr>
							<td>{$smarty.const.PAGE_CACHE_VARY}:</td>
							<td rowspan="2">
								<textarea name="cacheVary" id="cacheVary" cols="20" rows="5">{$page->cacheVary|escape}</textarea>
							</td>
						</tr>
						<tr>
							<td height="100%">
								<input type="button" id="cache_vary" value="{$smarty.const.PAGE_CACHE_VARY_SUGGEST}" style="margin: 10px;" />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="checkbox" name="cacheGz" id="ch.cacheGz" value="true" {if $page->cacheGz}checked{/if} />
								<label for="ch.cacheGz">{$smarty.const.PAGE_TRY_TO_GZIP_CACHE}</label>
							</td>
						</tr>
					</table>
				</fieldset>
			</fieldset>
			<br />
			<fieldset style="padding:10" {if !($super or $WCS->decide($page, 'editpage.security'))}disabled{/if}><legend>{$smarty.const.PAGE_USERS}</legend>
				<table style="margin:5 0" cellspacing="3">
					<tr>
						<td>
							<select size="10" id="users" name="users[]" multiple>
								{html_options options=$groups selected=$selected_groups}
							</select>
						</td>
						<td valign="top">
							<input type="checkbox" name="usrEnforceChildren" id="f.usrEnforceChildren" value="true" /> <label for="f.usrEnforceChildren">{$smarty.const.H_MAKE_CHILDS_USR_THE_SAME}</label>
							<br><input type="button" id="select_all" value="{$smarty.const.PAGE_SELECT_ALL_USERS}" style="width:120;margin:3 5" />
							<br><input type="button" id="unselect_all" value="{$smarty.const.PAGE_UNSELECT_ALL_USERS}" style="width:120;margin:3 5" />
						</td>
					</tr>
				</table>
				<fieldset style="padding:10"><legend>{$smarty.const.H_PAGE_USER_PROFILES}</legend>
					{if $page->uri neq '/'}
						<div style="padding: 5px;">
							<input type="checkbox" name="profiles_inherit" id="profiles_inherit" value="true" {if $page->profilesInherit}checked{/if} /> <label for="profiles_inherit">{$smarty.const.PAGE_PROFILES_INHERIT}</label>
						</div>
					{/if}
					<fieldset id="fs_profiles" style="border: none;">
						<table style="margin:5 0" cellspacing="3">
							<tr>
								<td nowrap>{$smarty.const.PAGE_PROFILES_LOADER}:</td>
								<td style="width: 100%;">
									<select name="profiles_load">
										<option style="background: #eee;">-</option>
										{html_options options=$obj_prof selected=$selected_obj}
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="checkbox" name="profiles_fetch_once" id="f.profiles_fetch_dynamic" value="true" {if !$page->profilesDynamic}checked{/if} /> <label for="f.profiles_fetch_dynamic">{$smarty.const.PAGE_PROFILES_LOAD_ONCE}</label>
								</td>
							</tr>
						</table>
					</fieldset>
				</fieldset>
			</fieldset>
			<br />
			<div class="right_btns">
				<input type="submit" value="{$smarty.const.BTN_SAVE}" id="save" name="save" {if !($super or $WCS->decide($page, 'managesub'))}disabled{/if} />
				<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
			</div>
		</form>
	</body>
</html>