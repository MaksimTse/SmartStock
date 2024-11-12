<?php
$xml = new DOMDocument;

if (!file_exists('SmartStock/Data.xml')) {
    die('Error: Data.xml file does not exist.');
}

if (!$xml->load('SmartStock/Data.xml')) {
    die('Error loading XML file.');
}

function generateHTMLTable($xmlDoc) {
    $html = '<table>';
    $html .= '<thead><tr><th>Category</th><th>Country</th><th>Product</th><th>Quantity</th><th>Orderer</th><th>Date</th><th>Additional Info</th></tr></thead>';
    $html .= '<tbody>';

    $warehouses = $xmlDoc->getElementsByTagName('warehouse');
    foreach ($warehouses as $warehouse) {
        $categories = $warehouse->getElementsByTagName('category');
        foreach ($categories as $category) {
            $categoryName = $category->getAttribute('name');
            $countries = $category->getElementsByTagName('country');
            foreach ($countries as $country) {
                $countryName = $country->getAttribute('name');
                $products = $country->getElementsByTagName('product');
                foreach ($products as $product) {
                    $productName = $product->getElementsByTagName('toodenimi')->item(0)->nodeValue;
                    $quantity = $product->getElementsByTagName('kogus')->item(0)->nodeValue;
                    $orderer = $product->getElementsByTagName('tellija')->item(0)->nodeValue;
                    $date = $product->getElementsByTagName('kuupaev')->item(0)->nodeValue;
                    $additionalInfo = $product->getElementsByTagName('lisainfo')->item(0)->nodeValue;

                    $html .= '<tr>';
                    $html .= "<td>{$categoryName}</td>";
                    $html .= "<td>{$countryName}</td>";
                    $html .= "<td>{$productName}</td>";
                    $html .= "<td>{$quantity}</td>";
                    $html .= "<td>{$orderer}</td>";
                    $html .= "<td>{$date}</td>";
                    $html .= "<td>{$additionalInfo}</td>";
                    $html .= '</tr>';
                }
            }
        }
    }

    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
}

$htmlOutput = generateHTMLTable($xml);

header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XML laoandmed</title>
    <link rel="stylesheet" href="XMLstyle.css">
    </head>
<body>
<h2>XML laoandmed</h2>
<?php
echo $htmlOutput;
?>
</body>
</html>
