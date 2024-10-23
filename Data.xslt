<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <!-- Шаблон для корневого элемента -->
    <xsl:template match="/warehouse">
        <html>
            <head>
                <title>Warehouse Data</title>
            </head>
            <body>
                <h2>Warehouse Regions and Products</h2>
                <xsl:for-each select="region">
                    <h3>Region: <xsl:value-of select="@name"/></h3>
                    <xsl:for-each select="country">
                        <h4>Country: <xsl:value-of select="@name"/></h4>
                        <xsl:for-each select="category">
                            <h5>Category: <xsl:value-of select="@name"/></h5>
                            <table border="1">
                                <tr bgcolor="#9acd32">
                                    <th>Toodenimi</th>
                                    <th>ID</th>
                                    <th>Kogus</th>
                                    <th>Arve</th>
                                    <th>Tellija</th>
                                    <th>Kuupäev</th>
                                    <th>Lisainfo</th>
                                </tr>
                                <xsl:for-each select="product">
                                    <tr>
                                        <td><xsl:value-of select="toodenimi"/></td>
                                        <td><xsl:value-of select="id"/></td>
                                        <td><xsl:value-of select="kogus"/></td>
                                        <td><xsl:value-of select="arve"/></td>
                                        <td><xsl:value-of select="tellija"/></td>
                                        <td><xsl:value-of select="kuupäev"/></td>
                                        <td><xsl:value-of select="lisainfo"/></td>
                                    </tr>
                                </xsl:for-each>
                            </table>
                        </xsl:for-each>
                    </xsl:for-each>
                </xsl:for-each>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
