<?php
function displayXMLAsHTML($xmlFilePath) {
    if (!file_exists($xmlFilePath)) {
        echo "Файл XML не найден.";
        return;
    }

    $xmlContent = simplexml_load_file($xmlFilePath);

    if ($xmlContent === false) {
        echo "Ошибка загрузки XML.";
        return;
    }

    echo "<div class='xml-content'>";

    function displayNode($node) {
        echo "<ul>";
        foreach ($node as $key => $value) {
            echo "<li><strong>$key:</strong> ";
            if ($value->count()) {
                displayNode($value);
            } else {
                echo htmlspecialchars($value);
            }
            echo "</li>";
        }
        echo "</ul>";
    }
    displayNode($xmlContent);

    echo "</div>";
}

displayXMLAsHTML('SmartStock/data.xml');
?>
