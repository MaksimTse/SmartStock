﻿<%@ Page Title="Home Page" Language="C#" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="SmartStock._Default" %>

<!DOCTYPE html>
<html>
    <head>
        <title>
            xml ja xslt andmete kuvamine
        </title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script src="script.js" defer></script>
    </head>
    <body>
        <div>
            <asp:Xml runat="server" DocumentSource="~/Data.xml" TransformSource="~/Data.xslt"/>
        </div>
    </body>
</html>