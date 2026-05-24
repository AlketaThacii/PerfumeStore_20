<?php
include("db.php");

// Marrim tekstin që po shkruan përdoruesi
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

// Bëjmë query-në në databazë duke filtruar sipas emrit të produktit ose kategorisë
$query = mysqli_query($conn, "
    SELECT products.id, products.name, products.price, products.image, categories.name AS category
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    WHERE products.name LIKE '%$search%' OR categories.name LIKE '%$search%'
    LIMIT 6
");

$products = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Nëse nuk gjendet asnjë produkt
if (empty($products)) {
    echo "<p style='grid-column: 1/-1; text-align: center; padding: 20px;'>Nuk u gjet asnjë parfum me këtë emër.</p>";
    exit;
}

// Gjenerojmë të njëjtat karta produkti (HTML) që të shfaqen live te faqja
foreach ($products as $p) {
    $priceFormatted = number_format($p['price'], 2) . " €";
    $imagePath = str_replace('../', '', htmlspecialchars($p['image']));
    $category = htmlspecialchars(ucfirst($p['category'] ?? 'Unknown'));
    $name = htmlspecialchars($p['name']);
    
    // Përcaktojmë badge-in
    $badge = "";
    if ($p['price'] >= 150) $badge = "Premium";
    elseif ($p['price'] < 110) $badge = "Sale";

    echo "
    <div class='card'>
        <img src='$imagePath' alt=''>
        <h3>$name</h3>
        <p class='type'>$category</p>
        <p class='price'>$priceFormatted</p>
        
        <button class='add-to-cart-btn' 
                data-name='$name' 
                data-price='{$p['price']}' 
                data-img='{$p['image']}'>
            Add to Cart
        </button>";
        
        if ($badge) {
            echo "<span class='badge'>$badge</span>";
        }
    echo "</div>";
}
?>