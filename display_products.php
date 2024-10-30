<?php
function searchAndSortProducts($filePath, $searchTerm = '', $sortBy = 'category', $sortOrder = 'asc') {
    $xml = simplexml_load_file($filePath);
    if ($xml === false) {
        die("Ошибка загрузки XML файла.");
    }

    $results = [];

    foreach ($xml->xpath('//product') as $product) {
        $productName = (string) $product->toodenimi;

        if ($searchTerm === '' || stripos($productName, $searchTerm) !== false) {
            $results[] = [
                'warehouse' => (string) $product->xpath('ancestor::warehouse/@number')[0],
                'category' => (string) $product->xpath('ancestor::category/@name')[0],
                'country' => (string) $product->xpath('ancestor::country/@name')[0],
                'toodenimi' => (string) $product->toodenimi,
                'id' => (string) $product->id,
                'kogus' => (string) $product->kogus,
                'arve' => (string) $product->arve,
                'tellija' => (string) $product->tellija,
                'kuupaev' => (string) $product->kuupaev,
                'lisainfo' => (string) $product->lisainfo
            ];
        }
    }

    usort($results, function($a, $b) use ($sortBy, $sortOrder) {
        if ($sortOrder === 'asc') {
            return strcmp($a[$sortBy], $b[$sortBy]);
        } else {
            return strcmp($b[$sortBy], $a[$sortBy]);
        }
    });

    return $results;
}

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'category';
$sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'desc' ? 'desc' : 'asc';
$foundProducts = searchAndSortProducts('SmartStock/Data.xml', $searchTerm, $sortBy, $sortOrder);

$nextSortOrder = $sortOrder === 'asc' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lao inventar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Lao inventar</h1>

<form method="GET">
    <label for="search">Search for product:</label>
    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Search</button>
</form>

<div class="table-container">
    <table>
        <tr>
            <th>Lao number</th>
            <th><a href="?sortBy=category&sortOrder=<?php echo $nextSortOrder; ?>&search=<?php echo urlencode($searchTerm); ?>">Kategooria</a></th>
            <th><a href="?sortBy=country&sortOrder=<?php echo $nextSortOrder; ?>&search=<?php echo urlencode($searchTerm); ?>">Riik</a></th>
            <th>Toode</th>
            <th>ID</th>
            <th>Kogus</th>
            <th>Konto number</th>
            <th>Klient</th>
            <th>Kuupäev</th>
            <th>Lisa teave</th>
        </tr>
        <?php if (!empty($foundProducts)): ?>
            <?php foreach ($foundProducts as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['warehouse']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td><?php echo htmlspecialchars($product['country']); ?></td>
                    <td><?php echo htmlspecialchars($product['toodenimi']); ?></td>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['kogus']); ?></td>
                    <td><?php echo htmlspecialchars($product['arve']); ?></td>
                    <td><?php echo htmlspecialchars($product['tellija']); ?></td>
                    <td><?php echo htmlspecialchars($product['kuupaev']); ?></td>
                    <td><?php echo htmlspecialchars($product['lisainfo']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10">Product not found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
<h2>Kokku tooteid: <?php echo count($foundProducts); ?></h2>
<h2>Kokku kogus: <?php echo array_sum(array_column($foundProducts, 'kogus')); ?></h2>
</body>
</html>
