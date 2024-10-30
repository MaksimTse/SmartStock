<?php
$jsonData = file_get_contents('Data.json');

$dataArray = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Decoding Error JSON: " . json_last_error_msg();
    exit;
}
echo "<pre>";
print_r($dataArray);
echo "</pre>";
?>


//$xmlFilePath = 'SmartStock/Data.xml';
//$xmlContent = simplexml_load_file($xmlFilePath);
//
//$jsonContent = json_encode($xmlContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
//
//file_put_contents('Data.json', $jsonContent);
//
//echo "Saved Data to Data.json";
