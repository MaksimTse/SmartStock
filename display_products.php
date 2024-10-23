<?php
// Create a new DOMDocument for XML
$xml = new DOMDocument;

// Load the XML file
if (!file_exists('Data.xml')) {
    die('Error: Data.xml file does not exist.');
}

if (!$xml->load('Data.xml')) {
    die('Error loading XML file.');
}

// Create a new DOMDocument for XSLT
$xslt = new DOMDocument;

// Load the XSLT file
if (!file_exists('Data.xslt')) {
    die('Error: Data.xslt file does not exist.'); // Updated to xslt
}

if (!$xslt->load('Data.xslt')) {
    die('Error loading XSLT file.'); // Updated to xslt
}

// Create a new XSLTProcessor
$proc = new XSLTProcessor;

// Import the XSLT stylesheet
$proc->importStyleSheet($xslt);

// Transform the XML to HTML
$htmlOutput = $proc->transformToXML($xml);

// Check if the transformation was successful
if ($htmlOutput === false) {
    die('Error during XSLT transformation.');
}

// Output the HTML
header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #9acd32;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<h2>Warehouse Data</h2>
<?php
// Output the transformed HTML
echo $htmlOutput;
?>
</body>
</html>
