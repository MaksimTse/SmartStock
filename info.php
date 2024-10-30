<?php
$jsonData = file_get_contents('Data.json');

$dataArray = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Ошибка декодирования JSON: " . json_last_error_msg();
    exit;
}
echo "<pre>";
print_r($dataArray);
echo "</pre>";
?>
