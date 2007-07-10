<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html" indent="yes" standalone="yes"/>

	<xsl:variable name="wchome" select="/interface/session/site/@insight"/>
	<xsl:variable name="msg" select="/interface/messages/msg"/>
	<xsl:variable name="skin" select="'metallic'"/>
	<xsl:variable name="skinhome" select="concat($wchome,'/skins/',$skin)"/>
	<xsl:variable name="img" select="concat($wchome,'/i/',$skin)"/>

	<xsl:template match="/">
		<html>
			<head>
				<meta name="Content-type" content="text/html; charset=utf-8"/>
				<title><xsl:value-of select="/interface/title"/></title>
				<link href="{concat($wchome,'/',$skin,'.css')}" type="text/css" rel="stylesheet"/>
				<xsl:apply-templates select="interface/script"/>
				<xsl:apply-templates select="interface/style"/>
			</head>
			<body style="border-style:groove;padding:0 20 20" onkeypress="if(window.event.keyCode==28) viewSource()">
				<xsl:apply-templates select="interface"/>
			</body>
		</html>
	</xsl:template>

	<xsl:template match="script|style">
		<xsl:copy-of select="."/>
	</xsl:template>

	<xsl:template match="interface">
		<h3 style="margin:15 20"><xsl:value-of select="header"/></h3>
		<xsl:apply-templates select="form"/>
		<xsl:apply-templates select="postscript"/>
	</xsl:template>
	
	<xsl:template match="form">
		<xsl:copy>
			<xsl:for-each select="@*">
				<xsl:attribute name="{local-name(.)}"><xsl:value-of select="."/></xsl:attribute>
			</xsl:for-each>
			<xsl:apply-templates/>
		</xsl:copy>
	</xsl:template>
	
	<xsl:template match="objects">
		<xsl:variable name="count" select="7"/>
		<xsl:variable name="width" select="round(100 div $count)"/>
		<div style="background:white;border:groove 2px;padding:3px 5px;cursor:default;" onselectstart="return false;">
			<xsl:for-each select="datasource">
				<h3 class="l1"><xsl:value-of select="@type"/></h3>
				<div style="padding:0 15 15">
				<xsl:for-each select="type">
					<h3 class="l2"><xsl:value-of select="@name"/></h3>
					<xsl:for-each select="item">
						<div obj_type="{../@id}" obj_id="{@id}" class="item" onclick="choose(this);" ondblclick="choose(this);f.submit();" title="{.}">
							<img src="{$img}/tool/addobject.gif"/><br/>
							<span><xsl:value-of select="@name"/></span>
						</div>
					</xsl:for-each>
					<xsl:if test="not(position() = last())">
						<hr size="1"/>
					</xsl:if>
				</xsl:for-each>
				</div>
			</xsl:for-each>
		</div>
	</xsl:template>
	
	<xsl:template match="input">
		<xsl:copy-of select="."/>
	</xsl:template>

	<xsl:template match="br">
		<xsl:copy-of select="."/>
	</xsl:template>

	<xsl:template match="div">
		<xsl:copy-of select="."/>
	</xsl:template>
</xsl:stylesheet>
