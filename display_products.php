<?php
// Helper function to find or create a nested structure in the JSON array
function &findOrCreate(&$array, $key, $value) {
    foreach ($array as &$item) {
        if (isset($item['@attributes'][$key]) && $item['@attributes'][$key] === $value) {
            return $item;
        }
    }
    // If not found, create a new entry
    $array[] = ['@attributes' => [$key => $value]];
    return $array[count($array) - 1];
}

// Check if form is submitted to save data in JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $newProduct = [
        "toodenimi" => $_POST['toodenimi'],
        "kogus" => $_POST['kogus'],
        "tellija" => $_POST['tellija'],
        "kuupaev" => $_POST['kuupaev'],
        "lisainfo" => $_POST['lisainfo']
    ];

    $categoryName = $_POST['category'];
    $countryName = $_POST['country'];

    $jsonFilePath = 'Data.json';

    // Load the existing JSON file
    if (file_exists($jsonFilePath)) {
        $jsonData = json_decode(file_get_contents($jsonFilePath), true);
    } else {
        $jsonData = ["warehouse" => []];
    }

    // Ensure 'warehouse' is an array, not an object with numeric keys
    if (!isset($jsonData['warehouse']) || !is_array($jsonData['warehouse'])) {
        $jsonData['warehouse'] = [];
    }

    // Find or create the category and country structure without adding warehouse number
    $category = &findOrCreate($jsonData['warehouse'][0]['category'], 'name', $categoryName);

    // Ensure the country level is an array and find or create the country entry
    if (!isset($category['country']) || !is_array($category['country'])) {
        $category['country'] = [];
    }
    $country = &findOrCreate($category['country'], 'name', $countryName);

    // Ensure the product level is an array and append the new product
    if (!isset($country['product']) || !is_array($country['product'])) {
        $country['product'] = [];
    }
    $country['product'][] = $newProduct;

    // Save back to JSON
    file_put_contents($jsonFilePath, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "<p>Toode on edukalt lisatud JSON-faili.</p>";
    header("Location: $_SERVER[PHP_SELF]");
    exit;
}

// Load products from JSON
$jsonFilePath = 'Data.json';
$foundProducts = [];

if (file_exists($jsonFilePath)) {
    $jsonData = json_decode(file_get_contents($jsonFilePath), true);
    if (isset($jsonData['warehouse']) && is_array($jsonData['warehouse'])) {
        foreach ($jsonData['warehouse'] as $warehouse) {
            // Check if categories exist and are in an array format
            if (isset($warehouse['category']) && is_array($warehouse['category'])) {
                foreach ($warehouse['category'] as $category) {
                    $categoryName = $category['@attributes']['name'] ?? '';

                    // Check if countries exist and are in an array format
                    if (isset($category['country'])) {
                        $countries = is_array($category['country']) && isset($category['country'][0]) ? $category['country'] : [$category['country']];
                        foreach ($countries as $country) {
                            $countryName = $country['@attributes']['name'] ?? '';

                            // Check if products exist and are in an array format
                            if (isset($country['product'])) {
                                $products = is_array($country['product']) && isset($country['product'][0]) ? $country['product'] : [$country['product']];
                                foreach ($products as $product) {
                                    $foundProducts[] = [
                                        'category' => $categoryName,
                                        'country' => $countryName,
                                        'toodenimi' => $product['toodenimi'] ?? '',
                                        'kogus' => $product['kogus'] ?? '',
                                        'tellija' => $product['tellija'] ?? '',
                                        'kuupaev' => $product['kuupaev'] ?? '',
                                        'lisainfo' => $product['lisainfo'] ?? ''
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lao Tooted</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Lao tooted</h1>

<!-- Display products from JSON file -->
<div class="table-container">
    <table>
        <tr>
            <th>Kategooria</th>
            <th>Riik</th>
            <th>Toode</th>
            <th>Kogus</th>
            <th>Klient</th>
            <th>Kuupäev</th>
            <th>Lisa teave</th>
        </tr>
        <?php if (!empty($foundProducts)): ?>
            <?php foreach ($foundProducts as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td><?php echo htmlspecialchars($product['country']); ?></td>
                    <td><?php echo htmlspecialchars($product['toodenimi']); ?></td>
                    <td><?php echo htmlspecialchars($product['kogus']); ?></td>
                    <td><?php echo htmlspecialchars($product['tellija']); ?></td>
                    <td><?php echo htmlspecialchars($product['kuupaev']); ?></td>
                    <td><?php echo htmlspecialchars($product['lisainfo']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10">Toodet ei leitud.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<!-- Product entry form -->
<h2>Toote sisestamine</h2>
<form action="" method="post">
    <table>
        <tr>
            <td><label for="category">Kategooria:</label></td>
            <td><input type="text" name="category" id="category" required></td>
        </tr>
        <tr>
            <td><label for="country">Riik:</label></td>
            <td><input type="text" name="country" id="country" required></td>
        </tr>
        <tr>
            <td><label for="toodenimi">Toote nimi:</label></td>
            <td><input type="text" name="toodenimi" id="toodenimi" required></td>
        </tr>
        <tr>
            <td><label for="kogus">Kogus:</label></td>
            <td><input type="text" name="kogus" id="kogus" required></td>
        </tr>
        <tr>
            <td><label for="tellija">Klient:</label></td>
            <td><input type="text" name="tellija" id="tellija" required></td>
        </tr>
        <tr>
            <td><label for="kuupaev">Kuupäev:</label></td>
            <td><input type="date" name="kuupaev" id="kuupaev" required></td>
        </tr>
        <tr>
            <td><label for="lisainfo">Lisa teave:</label></td>
            <td><input type="text" name="lisainfo" id="lisainfo"></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit" name="submit">Lisa toode</button></td>
        </tr>
    </table>
</form>
<p><a href="info.php" target="_blank">JSON fail</p>
</body>
</html>
