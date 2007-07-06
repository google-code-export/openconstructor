<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html" indent="yes" standalone="yes" encoding="utf-8"/>

	<xsl:variable name="wchome" select="/documentsframe/@insight"/>
	<xsl:variable name="msg" select="/documentsframe/messages/msg"/>
	<xsl:variable name="img" select="concat($wchome,'/i/metallic')"/>
	
	<xsl:template match="/documentsframe">
		<html>
			<head>
				<title>Documents</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<meta http-equiv="Pragma" content="no-cache"/>
				<link href="../../metallic.css" type="text/css" rel="stylesheet"/>
				<xsl:apply-templates select="//script"/>
				<script language="JavaScript" src="{$wchome}/common.js"></script>
			</head>
			<body style="background:white;padding:0;border:none;">
				<xsl:apply-templates select="documents"/>
				<xsl:for-each select="//postscript">
					<script><xsl:value-of select="." disable-output-escaping="yes"/></script>
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template match="script">
		<xsl:copy-of select="."/>
	</xsl:template>
	
	<xsl:template match="documents">
		<xsl:variable name="editor" select="editor"/>
		<form name="f_doc" action="{@server}" method="POST">
		<input type="hidden" name="action" value="{@defaultaction}"/>
		<xsl:for-each select="hidden">
			<input type="hidden" name="{@name}" value="{@value}"/>
		</xsl:for-each>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr id="hlhead">
				<xsl:apply-templates select="fields"/>
			</tr>
			<xsl:for-each select="item">
				<xsl:variable name="icon">
					<xsl:choose>
						<xsl:when test="@type"><xsl:value-of select="@type"/></xsl:when>
						<xsl:otherwise><xsl:value-of select="../@type"/></xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<tr id="r_{@id}" class="hlc{(position()-1) mod 2}">
					<td class="fc">
						<input type="checkbox" name="ids[]" value="{@id}" onclick="chk(this)" id="ch_{@id}"/>
						<img src="{$img}/f/{$icon}.gif" align="absmiddle"/>
					</td>
					<td width="100%" class="n">
						<a href="{$editor/@href}&amp;id={@id}" onclick="wxyopen(this.href,{$editor/@width},{$editor/@height});return false;">
							<xsl:if test="@dsId &gt; 0">
								<xsl:attribute name="href"><xsl:value-of select="concat($editor/@href,'&amp;ds_id=',@dsId,'&amp;id=',@id)"/></xsl:attribute>
							</xsl:if>
							<xsl:if test="@published = 'no'">
								<xsl:attribute name="class">dis</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="name" disable-output-escaping="yes"/>
						</a>
						<xsl:if test="meta"><br/><xsl:value-of select="meta" disable-output-escaping="yes"/></xsl:if>
					</td>
					<xsl:for-each select="f">
						<td nowrap=""><xsl:value-of select="." disable-output-escaping="yes"/></td>
					</xsl:for-each>
					<td>&#160;</td>
				</tr>
			</xsl:for-each>
		</table>
		</form>
		<xsl:apply-templates select="pages"/>
	</xsl:template>
	
	<xsl:template match="fields">
		<xsl:for-each select="field[@visible = 'yes']">
			<xsl:choose>
				<xsl:when test="position() = 1">
					<td width="100%" colspan="2">
						<input type="checkbox" onclick="doall(this.checked)" title="{$msg[@id='SELECT_ALL']}" name="checkall" align="absmiddle"/>
						<xsl:value-of select="@name"/>
					</td>
				</xsl:when>
				<xsl:otherwise>
					<td>
						<xsl:choose>
							<xsl:when test="@name != ''"><img src="{$img}/f/border.gif" align="absmiddle"/></xsl:when>
							<xsl:otherwise>&#160;</xsl:otherwise>
						</xsl:choose>
						<xsl:value-of select="@name" disable-output-escaping="yes"/>
					</td>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:for-each>
		<td width="13">
			<img src="{$wchome}/i/1x1.gif" width="13" height="1"/>
		</td>
	</xsl:template>
	
	<xsl:template match="pages">
		<xsl:variable name="ref" select="@href"/>
		<xsl:variable name="current" select="@current"/>
		<xsl:variable name="first" select="@first"/>
		
		<center>
		<xsl:if test="@count &gt; 1">
			<xsl:choose>
				<xsl:when test="$current = 1">
					<img src="{$img}/p/first.gif" border="0" align="absmiddle"/>&#160;&#160;
				</xsl:when>
				<xsl:otherwise>
					<a href="{$ref}1" title="{$msg[@id='GOTO_FIRST_PAGE']}"><img src="{$img}/p/first.gif" border="0" align="absmiddle"/></a>&#160;&#160;
				</xsl:otherwise>
			</xsl:choose>
			<xsl:choose>
				<xsl:when test="$current &gt; 1">
					<a href="{concat($ref,number($current)-1)}'" title="{$msg[@id='GOTO_PREVIOUS_PAGE']}"><img src="{$img}/p/prev.gif" border="0" align="absmiddle"/></a>&#160;&#160;
				</xsl:when>
				<xsl:otherwise>
					<img src="{$img}/p/prev.gif" border="0" align="absmiddle"/>&#160;&#160;
				</xsl:otherwise>
			</xsl:choose>
	
			<xsl:for-each select="page">
				<xsl:choose>
					<xsl:when test="position() + $first - 1 = $current">
						<b style="border:solid 1 #ccc;padding:4px;margin:0px -4px;"><xsl:value-of select="position()+number($first)-1"/></b>
					</xsl:when>
					<xsl:otherwise>
						<a href="{concat($ref,position()+number($first)-1)}"><xsl:value-of select="position()+number($first)-1"/></a>
					</xsl:otherwise>
				</xsl:choose>
				&#160;&#160;
			</xsl:for-each>
			
			<xsl:choose>
				<xsl:when test="$current &lt; @count">
					<a href="{concat($ref,number($current)+1)}" title="{$msg[@id='GOTO_NEXT_PAGE']}"><img src="{$img}/p/next.gif" border="0" align="absmiddle"/></a>&#160;
					<a href="{concat($ref,@count)}" title="{$msg[@id='GOTO_LAST_PAGE']}"><img src="{$img}/p/last.gif" border="0" align="absmiddle"/></a>
				</xsl:when>
				<xsl:otherwise>
					<img src="{$img}/p/next.gif" border="0" align="absmiddle"/>&#160;
					<img src="{$img}/p/last.gif" border="0" align="absmiddle"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:if>

		<p><xsl:value-of select="concat($msg[@id = 'TOTAL'],' ',@items)"/></p>
		</center>
	</xsl:template>
	
</xsl:stylesheet>	