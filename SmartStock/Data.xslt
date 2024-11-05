<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/Data">
        <html>
            <head>
                <title>Lao inventar</title>
            </head>
            <body>
                <div class="table-container">
                    <h1>Lao inventar</h1>
                    <table id="inventoryTable" data-order="asc">
                        <tr>
                            <th class="sortable" onclick="sortTable(0)">Kategooria</th>
                            <th class="sortable" onclick="sortTable(1)">Riik</th>
                            <th>Toode</th>
                            <th>Kogus</th>
                            <th>Klient</th>
                            <th>Kuupäev</th>
                            <th>Lisa teave</th>
                        </tr>
                        <xsl:for-each select="warehouse">
                            <xsl:for-each select="category">
                                <xsl:for-each select="country">
                                    <xsl:for-each select="product">
                                        <tr>
                                            <td>
                                                <xsl:value-of select="../../@name"/>
                                            </td>
                                            <td>
                                                <xsl:value-of select="../@name"/>
                                            </td>
                                            <td>
                                                <xsl:value-of select="toodenimi"/>
                                            </td>
                                            <td>
                                                <xsl:value-of select="kogus"/>
                                            </td>
                                            <td>
                                                <xsl:value-of select="tellija"/>
                                            </td>
                                            <td>
                                                <xsl:value-of select="kuupaev"/>
                                            </td>
                                            <td>
                                                <xsl:value-of select="lisainfo"/>
                                            </td>
                                        </tr>
                                    </xsl:for-each>
                                </xsl:for-each>
                            </xsl:for-each>
                        </xsl:for-each>
                    </table>

                    <h2>
                        Kokku tooteid: <xsl:value-of select="count(//product)"/>
                    </h2>
                    <h2>
                        Kokku kogus: <xsl:value-of select="sum(//product/kogus)"/>
                    </h2> 
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
