<?php
include("../includes/db.php");

$type = strtolower($_GET["type"] ?? "all");

$query = mysqli_query(
    $conn,
    "SELECT
        products.id,
        products.name,
        products.price,
        products.image,
        categories.name AS category
     FROM products
     LEFT JOIN categories
     ON products.category_id = categories.id
     ORDER BY RAND()
     LIMIT 6"
);

$products = mysqli_fetch_all($query, MYSQLI_ASSOC);

function formatPriceAjax($price)
{
    return number_format($price, 2) . " €";
}

function getBadgeAjax($price)
{
    if ($price >= 150) return "Premium";
    if ($price < 110) return "Sale";
    return "";
}

foreach ($products as $p) {
    $category = strtolower($p["category"] ?? "");

    if ($type !== "all" && $category !== $type) {
        continue;
    }

    $badge = getBadgeAjax($p["price"]);
    $image = str_replace("../", "", htmlspecialchars($p["image"]));
    $name = htmlspecialchars($p["name"]);
    $cat = htmlspecialchars(ucfirst($p["category"] ?? "Unknown"));
    $price = formatPriceAjax($p["price"]);

    echo '<div class="card">';
    echo '<img src="' . $image . '" alt="">';
    echo '<h3>' . $name . '</h3>';
    echo '<p class="type">' . $cat . '</p>';
    echo '<p class="price">' . $price . '</p>';

    if ($badge) {
        echo '<span class="badge">' . $badge . '</span>';
    }

    echo '</div>';
}