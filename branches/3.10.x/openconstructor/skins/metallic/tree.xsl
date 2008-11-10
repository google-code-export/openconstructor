<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html" indent="yes" standalone="yes" encoding="utf-8"/>

	<xsl:variable name="wchome" select="/selectnode/@insight"/>
	<xsl:variable name="img" select="concat($wchome,'/i/metallic')"/>
	
	<xsl:template match="/selectnode">
		<html>
			<head>
				<title>Select Document</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<meta http-equiv="Pragma" content="no-cache"/>
				<link href="../../metallic.css" type="text/css" rel="stylesheet"/>
				<script language="JavaScript" src="{$wchome}/catalog/local.js"></script>
				<script language="JavaScript" type="text/JavaScript">
					var tree = new Array();
					function node(id) { window.parent.expandNode(document.getElementById("tn" + id)); }
					function handle(obj) {
						try {
							if(obj.tagName == 'A') obj.parentNode.previousSibling.firstChild.onclick();
						} catch(e){}
						return false;
					}
					single = <xsl:value-of select="/selectnode/@single"/>
				</script>
			</head>
			<body style="background:#f2f2f2;padding:0;border:none;" onclick="return handle(event.srcElement);" ondrag="return false;">
				<xsl:for-each select="tree">
					<table class="tree" cellpadding="0" cellspacing="0" style="margin-top:15px;margin-left:-10px;">
						<xsl:apply-templates select="."/>
					</table>
				</xsl:for-each>
				<xsl:for-each select="//postscript">
					<script><xsl:value-of select="." disable-output-escaping="yes"/></script>
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template match="tree">
		<xsl:call-template name="treenode">
			<xsl:with-param name="type" select="@type"/>
		</xsl:call-template>
	</xsl:template>
	
	<xsl:template match="node">
		<xsl:choose>
			<xsl:when test="@type">
				<xsl:call-template name="node"><xsl:with-param name="type" select="@type"/></xsl:call-template>
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="node"><xsl:with-param name="type" select="../@default"/></xsl:call-template>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template name="node">
		<xsl:param name="type"/>

		<tr>
			<td class="co">
				<xsl:if test="position() = last()"><xsl:attribute name="style">background-image:none;</xsl:attribute></xsl:if>
				<xsl:choose>
					<xsl:when test="local-name(..) != 'tree'"><span style="display:block;width:16px;">&#160;</span></xsl:when>
					<xsl:otherwise><img src="{$img}/t/c.gif"/></xsl:otherwise>
				</xsl:choose>
			</td>
			<td class="fc">
				<xsl:if test="../@multiple = 'yes'">
					<img src="{$img}/t/box.gif" class="checkbox0" index="{@index}" id="mst{@id}" onclick="nodeChecked(this)"/>
					<script>tree[<xsl:value-of select="@index"/>] = {id:<xsl:value-of select="@id"/>, children:0, next:<xsl:value-of select="@next"/>, state:0, filled:0};</script>
				</xsl:if>
				<img src="{$img}/t/{$type}.gif" class="fc"/>
			</td>
			<td class="nv">
				<xsl:choose>
					<xsl:when test="@clickable = 'yes'">
						<span class="cur"><a href="{@action}" title="{@title}" oncontextmenu="return context(this);" style="text-decoration:underline" id="nh{@id}"><xsl:value-of select="@name"/></a></span>
					</xsl:when>
					<xsl:when test="@current = 'yes'">
						<span class="cur"><xsl:value-of select="./@name"/></span>
					</xsl:when>
					<xsl:otherwise>
						<a href="{@action}" title="{@title}" oncontextmenu="return context(this);" id="nh{@id}"><xsl:value-of select="@name"/></a>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</xsl:template>	

	<xsl:template name="treenode">
		<xsl:param name="type"/>
	
		<tr>
			<td onclick="node('{@id}')" id="tn{@id}">
				<xsl:if test="position() = last()"><xsl:attribute name="style">background-image:none;</xsl:attribute></xsl:if>
				<xsl:choose>
					<xsl:when test="local-name(..) != 'tree'">
						<xsl:attribute name="class">co</xsl:attribute>
						&#032;
					</xsl:when>
					<xsl:otherwise>
						<xsl:choose>
							<xsl:when test="@opened = 'yes'"><xsl:attribute name="class">co</xsl:attribute></xsl:when>
							<xsl:otherwise><xsl:attribute name="class">cc</xsl:attribute></xsl:otherwise>
						</xsl:choose>
						<img src="{$img}/t/plus.gif" id="st{@id}" class="nsc"/>
						<img src="{$img}/t/minus.gif" id="st{@id}" class="nso"/>
					</xsl:otherwise>
				</xsl:choose>
			</td>
			<td colspan="2">
				<table class="tree" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td>
								<xsl:choose>
									<xsl:when test="local-name(..) != 'tree'">
										<xsl:attribute name="class">fo</xsl:attribute>
										<img src="{$img}/t/{$type}.gif" noswap="1" id="mst{@id}" index="{@index}"/>
										<xsl:if test="@multiple = 'yes'">
											<script>
												tree.root = <xsl:value-of select="@index + 0"/>;
												tree[<xsl:value-of select="@index"/>] = {id:<xsl:value-of select="@id"/>, children:<xsl:value-of select="@children"/>, next:<xsl:value-of select="@next"/>, state:0, filled:0, isRoot: true};
											</script>
										</xsl:if>
									</xsl:when>
									<xsl:otherwise>
										<xsl:choose>
											<xsl:when test="@opened = 'yes'"><xsl:attribute name="class">fo</xsl:attribute></xsl:when>
											<xsl:otherwise><xsl:attribute name="class">fc</xsl:attribute></xsl:otherwise>
										</xsl:choose>
										<xsl:if test="@multiple = 'yes'">
											<img src="{$img}/t/box.gif" class="checkbox0" index="{@index}" id="mst{@id}" onclick="nodeChecked(this)"/>
											<script>tree[<xsl:value-of select="@index"/>] = {id:<xsl:value-of select="@id"/>, children:<xsl:value-of select="@children"/>, next:<xsl:value-of select="@next"/>, state:0, filled:0};</script>
										</xsl:if>
										<img src="{$img}/t/{$type}.gif" class="nfc"/>
										<img src="{$img}/t/{$type}_.gif" class="nfo"/>
									</xsl:otherwise>
								</xsl:choose>
							</td>
							<td colspan="2" class="nv">
								<xsl:choose>
									<xsl:when test="@clickable = 'yes'">
										<span class="cur"><a href="{@action}" title="{@title}" oncontextmenu="return context(this);" style="text-decoration:underline" id="nh{@id}"><xsl:value-of select="@name"/></a></span>
									</xsl:when>
									<xsl:when test="@current = 'yes'">
										<span class="cur"><xsl:value-of select="./@name"/></span>
									</xsl:when>
									<xsl:otherwise>
										<a href="{@action}" oncontextmenu="return context(this);" title="{@title}" id="nh{@id}"><xsl:value-of select="@name"/></a>
									</xsl:otherwise>
								</xsl:choose>
							</td>
						</tr>
					</thead>
					<tbody class="closed">
						<xsl:if test="@opened = 'yes' or local-name(..) != 'tree'"><xsl:attribute name="class"></xsl:attribute></xsl:if>
						<xsl:apply-templates/>
					</tbody>
				</table>
			</td>
		</tr>
	</xsl:template>	
	
</xsl:stylesheet>	