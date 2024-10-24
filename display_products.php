<?php
// Функция для загрузки XML
function loadXML($file) {
    if (!file_exists($file)) {
        die('XML файл не найден.');
    }
    return simplexml_load_file($file);
}

// Функция для преобразования XML в HTML с помощью xsltproc
function transformXML($xmlFile, $xslFile) {
    // Команда для выполнения xsltproc
    $command = "xsltproc $xslFile $xmlFile";
    $output = [];
    $returnVar = 0;

    // Выполнение команды
    exec($command, $output, $returnVar);

    // Проверка на ошибки выполнения
    if ($returnVar !== 0) {
        die('Ошибка при преобразовании XML в HTML.');
    }

    return implode("\n", $output);
}

// Функция для вывода результата
function outputResult($html) {
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
}

// Основной код
$xmlFile = 'Data.xml';
$xslFile = 'Data.xsl';

$xml = loadXML($xmlFile);
$html = transformXML($xmlFile, $xslFile);
outputResult($html);
?>
