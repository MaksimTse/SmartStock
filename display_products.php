<?php
function &findOrCreate(&$array, $key, $value) {
    foreach ($array as &$item) {
        if (isset($item['@attributes'][$key]) && $item['@attributes'][$key] === $value) {
            return $item;
        }
    }
    $array[] = ['@attributes' => [$key => $value]];
    return $array[count($array) - 1];
}

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

    if (file_exists($jsonFilePath)) {
        $jsonData = json_decode(file_get_contents($jsonFilePath), true);
    } else {
        $jsonData = ["warehouse" => []];
    }

    if (!isset($jsonData['warehouse']) || !is_array($jsonData['warehouse'])) {
        $jsonData['warehouse'] = [];
    }

    $category = &findOrCreate($jsonData['warehouse'][0]['category'], 'name', $categoryName);

    if (!isset($category['country']) || !is_array($category['country'])) {
        $category['country'] = [];
    }
    $country = &findOrCreate($category['country'], 'name', $countryName);

    if (!isset($country['product']) || !is_array($country['product'])) {
        $country['product'] = [];
    }
    $country['product'][] = $newProduct;

    file_put_contents($jsonFilePath, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "<p>Toode on edukalt lisatud JSON-faili.</p>";
    header("Location: display_products.php");
    exit;
}

// Load products from JSON
$jsonFilePath = 'Data.json';
$foundProducts = [];

if (file_exists($jsonFilePath)) {
    $jsonData = json_decode(file_get_contents($jsonFilePath), true);
    if (isset($jsonData['warehouse']) && is_array($jsonData['warehouse'])) {
        foreach ($jsonData['warehouse'] as $warehouse) {
            if (isset($warehouse['category']) && is_array($warehouse['category'])) {
                foreach ($warehouse['category'] as $category) {
                    $categoryName = $category['@attributes']['name'] ?? '';

                    if (isset($category['country'])) {
                        $countries = is_array($category['country']) && isset($category['country'][0]) ? $category['country'] : [$category['country']];
                        foreach ($countries as $country) {
                            $countryName = $country['@attributes']['name'] ?? '';

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

// Handle search query
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    if ($searchTerm !== '') {
        // Filter found products based on the search term
        $foundProducts = array_filter($foundProducts, function ($product) use ($searchTerm) {
            return stripos($product['toodenimi'], $searchTerm) !== false;
        });
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

<!-- Search form -->
<form method="get">
    <label for="search">Otsi toode nime järgi:</label>
    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Otsi</button>
</form>

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
            <tr>
                <td colspan="3"><strong>Kokku</strong></td>
                <td><strong><?php echo array_sum(array_column($foundProducts, 'kogus')); ?></strong></td>
                <td colspan="3"></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="7">Toodet ei leitud.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<h2>Kokku tooteid: <?php echo count($foundProducts); ?></h2>

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
            <td><input type="number" name="kogus" id="kogus" required></td>
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
