<?php
session_start();
include("../includes/config.php");

$search = strtolower($_GET["search"] ?? "");

foreach ($products as $product) {
    if ($search !== "" && strpos(strtolower($product->getName()), $search) === false) {
        continue;
    }

    $id = $product->getId();
    $name = htmlspecialchars($product->getName());
    $price = number_format((float)$product->getPrice(), 2);
    $cat = strtoupper($product->getCategory());
    $image = $product->getImage();
    $currentQty = $_SESSION["cart"][$id] ?? 0;

    echo '<div class="card">';
    echo "<img src='$image' alt='$name' class='product-img'>";
    echo "<h3>$name</h3>";
    echo "<p class='price'>$$price</p>";
    echo "<p style='font-size: 0.8rem; opacity: 0.6; margin-top: 5px;'>$cat</p>";

    if (isset($_SESSION["role"]) && $_SESSION["role"] === "user") {
        echo '<div class="qty-control">';
        echo '<button type="button" onclick="changeQty(' . $id . ', -1)">-</button>';
        echo '<span id="qty-' . $id . '">' . $currentQty . '</span>';
        echo '<button type="button" onclick="changeQty(' . $id . ', 1)">+</button>';
        echo '</div>';
    }

    echo '</div>';
}