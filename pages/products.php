<?php
include("../includes/config.php");

// GET VALUES
$order = $_GET['sort'] ?? null;
$category = $_GET['category'] ?? "all";

// FILTER PRODUCTS
$filteredProducts = filterProducts($products, $category);

// SORT PRODUCTS
if ($order) {
    $filteredProducts = sortProducts($filteredProducts, $order);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Maison de Sucre</title>

  <link rel ="stylesheet" href="../assets/css/products.css">
</head>

<body>
<h1>Maison de Sucre</h1>
<div class="controls">

    <strong>Category:</strong>
    <a href="?category=all&sort=<?= $order ?>">All</a>
    <a href="?category=men&sort=<?= $order ?>">Men</a>
    <a href="?category=women&sort=<?= $order ?>">Women</a>
    <a href="?category=unisex&sort=<?= $order ?>">Unisex</a>

    <br><br>

    <strong>Sort:</strong>
    <a href="?category=<?= $category ?>&sort=asc">Price ↑</a>
    <a href="?category=<?= $category ?>&sort=desc">Price ↓</a>

</div>

<div class="container3">

<?php if (!empty($filteredProducts)): ?>

    <?php foreach ($filteredProducts as $product): ?>

        <div class="card">
            <h3><?= $product->getName(); ?></h3>

            <p class="price">
                $<?= number_format((float)$product->getPrice(), 2); ?>
            </p>

            <p class="category">
                <?= strtoupper($product->getCategory()); ?>
            </p>
   
            <?php if ((float)$product->getPrice() > 150): ?>
                <p style="color:red;">Premium</p>
            <?php else: ?>
                <p style="color:lightgreen;">Standard</p>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p style="text-align:center;">No products found</p>

<?php endif; ?>

</div>

</body>
</html>