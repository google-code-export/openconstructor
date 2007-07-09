<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:import href="tree.xsl"/>
	<xsl:import href="docs.xsl"/>
	
	<xsl:output method="html" indent="yes" standalone="yes" encoding="utf-8"/>
	
	<xsl:variable name="wchome" select="/interface/session/site/@insight"/>
	<xsl:variable name="msg" select="/interface/messages/msg"/>
	<xsl:variable name="node" select="/interface/@node"/>
	<xsl:variable name="skin" select="'metallic'"/>
	<xsl:variable name="skinhome" select="concat($wchome,'/skins/',$skin)"/>
	<xsl:variable name="img" select="concat($wchome,'/i/',$skin)"/>

	<xsl:template match="/">
		<html>
			<head>
				<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
				<title><xsl:value-of select="/interface/title"/></title>
				<link href="{concat($wchome,'/',$skin,'.css')}" type="text/css" rel="stylesheet"/>
				<link rel="shortcut icon" type="image/x-icon" href="{$img}/favicon.ico" />
				<link rel="icon" type="image/gif" href="{$img}/favicon.gif" />
				<xsl:apply-templates select="interface/script"/>
				<base href="http://{concat(interface/session/site/@host,interface/@uri)}"/>
				<script>var cururi=&quot;<xsl:value-of select="interface/@uri"/>&quot;;</script>
			</head>
			<body style="background:white;" onclick="dd_i();" onkeypress="if(window.event.keyCode==28) viewSource()">
				<xsl:apply-templates select="interface"/>
			</body>
		</html>
	</xsl:template>

	<xsl:template match="script">
		<xsl:copy-of select="."/>
	</xsl:template>

	<xsl:template match="interface">
		<xsl:apply-templates select="//form"/>
		<xsl:choose>
			<xsl:when test="header">
				<h3 style="margin:15 20"><xsl:value-of select="header"/></h3>
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates select="session"/>
				<xsl:call-template name="logo"/>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:apply-templates select="sections"/>
		<xsl:apply-templates select="toolbar"/>
		<table cellpadding="0" cellspacing="0" bgcolor="white" width="100%" style="border-bottom:solid #999999;border-width:1px;table-layout:fixed" height="80%">
			<tr>
				<xsl:if test="not(header)">
					<td width="30%" style="background:#F8F8F8;padding:0px 0px 20px;" valign="top">
						<xsl:apply-templates select="navigation"/>
					</td>
				</xsl:if>
				<td style="border:solid #999999;border-width:0 0 0 1;padding-bottom:30px;" valign="top">
					<xsl:apply-templates select="documents"/>
					&#160;
					<xsl:apply-templates select="blocks"/>
				</td>
				<xsl:if test="settings">
					<td width="3px" valign="top" style="background:transparent url({$img}/panel_br.gif) top left repeat-y;">
						<div style="position:relative;left:-17px;width:20px;height:90px;">
							<a href="javascript:metallic_swp('{$wchome}/')" class="vbkmrk">
								<img src="{$img}/vbkmrk_state_{./settings/@display}.gif" border="0" name="bk_arrow" alt="{$msg[@id='SHOW_HIDE_PANEL']}" width="8"/>
							</a>
						</div>
					</td>
					<td width="255" style="padding:0 10;background:#EBEBEB;display:{./settings/@display}" id="view" valign="top">
						<img src="{$img}/close.gif" onclick="metallic_swp('{$wchome}/')" style="margin:5 -5 0 0;cursor:hand;float:right" alt="{$msg[@id='HIDE_PANEL']}" width="20"/>
						<xsl:apply-templates select="settings/searchbar"/>
						<xsl:apply-templates select="sort"/>
						<xsl:call-template name="fieldsstate"/>
					</td>
				</xsl:if>
			</tr>
		</table>
		<xsl:apply-templates select="//postscript"/>
		<xsl:apply-templates select="copyrights"/>
	</xsl:template>
	
	<xsl:template match="form">
		<xsl:copy-of select="."/>
	</xsl:template>
	
	<xsl:template match="session">
		<table id="head" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td nowrap="">
					<xsl:value-of select="$msg[@id='EDITING_SITE']"/>:&#160;<a href="http://{./site/@host}" target="_blank"><xsl:value-of select="./site/@host"/></a>
					&#160;[<a href="javascript:wxyopen(wchome+'/users/edituser.php?id={./user/@id}',600)" title="{./user/@id}" class="gray"><xsl:value-of select="./user/@name"/></a>]
				</td>
				<td nowrap="" align="right">
					<a href="javascript:aboutOpenConstructor()" class="gray" title="{$msg[@id='ABOUT_WC']}"><img src="{$img}/about.gif" border="0" hspace="3" alt="{$msg[@id='ABOUT_WC']}" align="absmiddle"/><xsl:value-of select="$msg[@id='ABOUT_WC']"/></a>&#160;
					<xsl:if test="./user/@id = 'root'">
						<a href="{$wchome}/setup/" class="gray" title="{$msg[@id='WC_SETUP']}"><img src="{$img}/setup.gif" border="0" hspace="3" alt="{$msg[@id='ABOUT_WC']}" align="absmiddle"/><xsl:value-of select="$msg[@id='WC_SETUP']"/></a>&#160;
					</xsl:if>
					<a href="javascript:switch_user()" class="gray" title="{$msg[@id='SWITCH_USER']}"><img src="{$img}/switchuser.gif" border="0" hspace="3" alt="{$msg[@id='SWITCH_USER']}" align="absmiddle"/><xsl:value-of select="$msg[@id='SWITCH_USER']"/></a>&#160;
					<a href="{$wchome}/logout.php" class="gray"><img src="{$img}/logout.gif" border="0" hspace="3" alt="{$msg[@id='LOGOUT']}" align="absmiddle"/><xsl:value-of select="$msg[@id='LOGOUT']"/></a>
				</td>
			</tr>
		</table>
		<!--form name="f_authorize" method="post" action="{concat($wchome,'/i_login.php?login_page=',/interface/@uri)}" style="margin:0;display:none">
			<input name="autologin" type="hidden" value="disabled"/>
			<input name="login" type="hidden"/>
			<input type="password" name="password"/>
		</form-->
	</xsl:template>
	
	<xsl:template name="logo">
		<div style="height:15px;background:#ccc;">&#160;</div>
	</xsl:template>

	<xsl:template match="sections">
		<div id="menu" nowrap="">
			<xsl:for-each select="item">
				<xsl:choose>
					<xsl:when test="./@current='yes'">
						<span class='cur'><xsl:value-of select="."/></span>
					</xsl:when>
					<xsl:otherwise>
						<span><a href="{./@href}" title="{.}" class="gray"><xsl:value-of select="."/></a></span>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>
		</div>
		<div id="bkline"></div>
	</xsl:template>

	<xsl:template match="toolbar">
		<div id="toolbar" nowrap="">
			<xsl:apply-templates select="../posttoolbar"/>
			<img src="{$img}/tool/beginner.gif" align="absmiddle" vspace="4" hspace="5"/>
			<xsl:apply-templates select="../pretoolbar"/>
			<xsl:apply-templates select="*"/>
		</div>
	</xsl:template>
	
	<xsl:template match="toolbar/button">
		<xsl:if test="./@separator = 'yes'">
			<img src="{$img}/tool/separator.gif" alt="|" align="absmiddle" hspace="3"/>
		</xsl:if>
		<xsl:choose>
			<xsl:when test="@action=''">
				<img src="{$img}/tool/{./@id}_.gif" alt="{.}" class="t" align="absmiddle" name="btn_{./@id}"/>
			</xsl:when>
			<xsl:otherwise>
				<a href="{./@action}" class="tool"><img src="{$img}/tool/{./@id}.gif" alt="{.}" class="t" align="absmiddle" name="btn_{./@id}"/></a>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template match="buttonset">
		<xsl:if test="./@separator = 'yes'">
			<img src="{$img}/tool/separator.gif" alt="|" align="absmiddle" hspace="3"/>
		</xsl:if>
		<xsl:apply-templates select="button[@default='yes']"/>
		<table cellpadding="0" cellspacing="0" class="bs" id="{@id}" onclick="dd_choose('{@id}')">
		<xsl:for-each select="button">
			<xsl:choose>
				<xsl:when test="@action=''">
					<tr><td onselectstart="return false" nowrap="">
						<img src="{$img}/tool/{./@id}_.gif" alt="{.}" class="bs" align="absmiddle" name="btn_{./@id}"/><xsl:value-of select="."/>
					</td></tr>
				</xsl:when>
				<xsl:otherwise>
					<tr id="r_btn_{@id}"><td nowrap="">
						<a href="{./@action}" class="tool"><img src="{$img}/tool/{./@id}.gif" alt="{.}" class="bs" align="absmiddle" name="btn_{./@id}"/><xsl:value-of select="."/></a>
					</td></tr>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:for-each>
		</table>
	</xsl:template>
	
	<xsl:template match="buttonset/button">
		<a href="{./@action}" class="bstool"><img src="{$img}/tool/{./@id}.gif" alt="{.}" class="t" align="absmiddle" name="btn_def_{../@id}" aliasof="r_btn_{@id}"/><img src="{$img}/tool/drop.gif" class="drop" onclick="dropdown({../@id});this.blur();return false" id="{../@id}_"/></a>
		<xsl:if test="@action=''">
			<script>disableButton(btn_def_<xsl:value-of select="../@id"/>,&quot;<xsl:value-of select="concat($img,'/tool/',@id,'_.gif')"/>&quot;);</script>
		</xsl:if>
	</xsl:template>

	<xsl:template match="pretoolbar">
		<xsl:value-of select="." disable-output-escaping="yes"/>
	</xsl:template>
	
	<xsl:template match="posttoolbar">
		<div class="right">
			<xsl:value-of select="." disable-output-escaping="yes"/>
		</div>
	</xsl:template>
	
	<xsl:template match="navigation">
		<xsl:apply-templates select="tabs"/>
		<script>var tree = new Array();</script>
		<xsl:for-each select="tree | node">
			<table class="tree" cellpadding="0" cellspacing="0" style="margin-top:20px;">
				<xsl:apply-templates select="."/>
			</table>
		</xsl:for-each>
		<xsl:apply-templates select="apply"/>
	</xsl:template>
	
	<xsl:template match="navigation/apply">
		<div style="text-align:right;padding:10px 20px;">
			<hr size="1"/>
			<input type="button" value="Apply" onclick="applyNodeFilter()"/>
		</div>
	</xsl:template>
	
	<xsl:template match="navigation/tabs">
		<div id="navtabs" nowrap="">
			<xsl:for-each select="item">
				<xsl:choose>
					<xsl:when test="./@current='yes'">
						<span class='cur'><xsl:value-of select="."/></span>
					</xsl:when>
					<xsl:otherwise>
						<span><a href="{./@href}" title="{.}" class="gray"><xsl:value-of select="."/></a></span>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>
		</div>
	</xsl:template>
		
	<xsl:template match="blocks">
		<xsl:value-of select="." disable-output-escaping="yes"/>
	</xsl:template>

	<xsl:template match="postscript">
		<script>
		<xsl:value-of select="." disable-output-escaping="yes"/>
		</script>
	</xsl:template>

	<xsl:template match="copyrights">
		<xsl:value-of select="." disable-output-escaping="yes"/>
	</xsl:template>
	
	<xsl:template match="searchbar">
		<fieldset style="padding:5">
			<form method="get" action="." name="f_search" onsubmit="this.s.disabled=true" style="margin: 0;">
			<legend><xsl:value-of select="@title"/></legend>
			<table cellspacing="3" width="100%">
				<tr>
					<td><input type="text" name="search" size="19" value="{@text}"/></td>
					<td align="right"><input type="submit" name="s" value="{$msg[@id='START_SEARCH']}"/></td>
				</tr>
				<xsl:if test="@showNoIndex = 'yes'">
					<tr>
						<td colspan="2">
							<input type="checkbox" name="noindex" id="f.noindex">
								<xsl:if test="@useIndex = 'no'">
									<xsl:attribute name="checked">1</xsl:attribute>
								</xsl:if>
							</input>
							<label for="f.noindex"><xsl:value-of select="$msg[@id='RP_DONT_USE_INDEX']"/></label></td>
					</tr>
				</xsl:if>
			</table>
			</form>
		</fieldset>
	</xsl:template>
	
	<xsl:template name="fieldsstate">
		<xsl:variable name="fields" select="/interface/documents/fields/field[not(@title='')]"/>
		<xsl:if test="count($fields) &gt; 0">
			<form name="frm_v" style="margin:0" onsubmit="rf_view();return false;">
			<fieldset style="padding:10 5">
				<legend style="margin:5"><xsl:value-of select="$msg[@id='VIEW']"/></legend>
				<xsl:for-each select="$fields">
					<xsl:choose>
						<xsl:when test="@enabled = 'yes'"><input type="checkbox" name="{@id}" CHECKED="" id="fs_{@id}"/></xsl:when>
						<xsl:otherwise><input type="checkbox" name="{@id}" id="fs_{@id}"/></xsl:otherwise>
					</xsl:choose>
						<label for="fs_{@id}"><xsl:value-of select="@title"/></label>
					<br/>
				</xsl:for-each>
			</fieldset>
			<fieldset style="padding:10 5">
				<legend style="margin:5"><xsl:value-of select="$msg[@id='INFO']"/></legend>
				<input type="text" name="pagesize" value="{$fields/../../@size}" size="2" maxlength="3"/>&#160;
				<xsl:value-of select="$msg[@id='DOCUMENTS_PER_PAGE']"/>
			</fieldset>
			<br/>
			</form>
			<div align="right">
				<input type="button" name="s" value="{$msg[@id='REFRESH']}" onclick="rf_view()"/>
			</div>
		</xsl:if>
	</xsl:template>
	
</xsl:stylesheet>