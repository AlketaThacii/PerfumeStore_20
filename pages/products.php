<?php
include("../includes/config.php");


// 1. DATA LOGIC
$order = $_GET['sort'] ?? null;
$category = $_GET['category'] ?? "all";
$filteredProducts = filterProducts($products, $category);

if ($order) {
    $filteredProducts = sortProducts($filteredProducts, $order);
}

include("../includes/header.php"); 
echo '<link rel="stylesheet" href="../assets/css/style.css" >';
include("../includes/navbar.php");


echo '<link rel="stylesheet" href="../assets/css/style.css" >';

echo '<main class="shop">';


    echo '<div class="filter" style="margin-bottom: 30px;">';
        echo '<strong>Category:</strong> ';
        echo "<a href='?category=all&sort=$order'>All</a> | ";
        echo "<a href='?category=men&sort=$order'>Men</a> | ";
        echo "<a href='?category=women&sort=$order'>Women</a> | ";
        echo "<a href='?category=unisex&sort=$order'>Unisex</a>";
        echo '<br><br>';
        echo '<strong>Sort Price:</strong> ';
        echo " <a href='?category=$category&sort=asc'>Price ↑</a> | ";
        echo " <a href='?category=$category&sort=desc'>Price ↓</a>";
    echo '</div>';

    echo '<div class="grid">';
        
        if (!empty($filteredProducts)) {
            foreach ($filteredProducts as $product) {
                $name = htmlspecialchars($product->getName());
                $price = number_format((float)$product->getPrice(), 2);
                $cat = strtoupper($product->getCategory());
                $isPremium = ((float)$product->getPrice() > 150);
                
                echo '<div class="card">';
                
                    if ($isPremium) {
                        echo '<span class="badge">PREMIUM</span>';
                    }

                    echo "<h3>$name</h3>";
                    echo "<p class='price'>$$price</p>";
                    echo "<p style='font-size: 0.8rem; opacity: 0.6; margin-top: 5px;'>$cat</p>";
                echo '</div>';
            }
        } else {
            echo '<p style="grid-column: 1 / -1;">No products found.</p>';
        }

    echo '</div>'; 
echo '</main>';

include("../includes/footer.php");
?>