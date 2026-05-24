<?php
include("../includes/config.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$order = $_GET['sort'] ?? null;
$category = $_GET['category'] ?? "all";
$filteredProducts = filterProducts($products, $category);

if ($order) {
    $filteredProducts = sortProducts($filteredProducts, $order);
}

include("../includes/header.php");
echo '<link rel="stylesheet" href="../assets/css/style.css" >';
include("../includes/navbar.php");

echo '<main class="shop">';
echo '<div class="left-side">';

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
        $rawPrice = (float)$product->getPrice();
        $price = number_format($rawPrice, 2);
        $cat = strtoupper($product->getCategory());
        $isPremium = ($rawPrice > 150);
        $cleanId = str_replace([' ', "'"], '', $name);

        echo '<div class="card">';

        if ($isPremium) {
            echo '<span class="badge">PREMIUM</span>';
        }

        $image = $product->getImage();

        echo "<img src='$image' alt='$name' class='product-img'>";
        echo "<h3>$name</h3>";
        echo "<p class='price'>$$price</p>";
        echo "<p style='font-size: 0.8rem; opacity: 0.6; margin-top: 5px;'>$cat</p>";

        if (
            isset($_SESSION["role"]) &&
            $_SESSION["role"] === "admin"
        ) {

            echo '<div style="margin-top:10px;">';

            echo '<a href="edit-product.php?id=' .
                $product->getId() .
                '">Edit</a> | ';

            echo '<a href="delete-product.php?id=' .
                $product->getId() .
                '" onclick="return confirm(\'Are you sure?\')">Delete</a>';

            echo '</div>';
        }

        if (isset($_SESSION["role"]) && $_SESSION["role"] === "user") {
            echo '<div class="qty-control">';
          $productId = $product->getId();
          $currentQty = $_SESSION['cart'][$productId] ?? 0;

        echo '<button type="button" onclick="changeQty(' . $productId . ', -1)">-</button>';
        echo '<span id="qty-' . $productId . '">' . $currentQty . '</span>';
        echo '<button type="button" onclick="changeQty(' . $productId . ', 1)">+</button>';
            echo '</div>';
        }

        echo '</div>';
    }
} 
if (
    isset($_SESSION["role"]) &&
    $_SESSION["role"] === "admin"
) {

    echo '<a href="add-product.php" class="add-card-link">';
    echo '<div class="card add-card">';

    echo '<div class="plus-icon">+</div>';
    echo '<h3>Add Product</h3>';

    echo '</div>';
    echo '</a>';
}

if (empty($filteredProducts)) {
    echo '<p style="grid-column: 1 / -1;">No products found.</p>';
}


echo '</div>';
echo '</div>';

if (isset($_SESSION["role"]) && $_SESSION["role"] === "user") {
    $cartTotal = 0;

if (isset($_SESSION["cart"])) {
    foreach ($_SESSION["cart"] as $cartProductId => $cartQty) {
        foreach ($products as $product) {
            if ($product->getId() == $cartProductId) {
                $cartTotal += $product->getPrice() * $cartQty;
            }
        }
    }
}
    echo '<aside class="order-sidebar">';
    echo '<div class="order-card">';
    echo '<h2>Order Online</h2>';

    echo '<div style="margin: 15px 0; font-weight: bold; color: #d4af37; font-size: 1.2rem;">';
    echo 'Total: $<span id="grand-total">' . number_format($cartTotal, 2) . '</span>';
    echo '</div>';

    echo '<hr style="border: 0.5px solid #444; margin: 15px 0;">';
    echo '<p style="font-size: 0.9rem; margin-bottom: 10px;">Please provide your details to complete the purchase.</p>';

    echo '<form action="process_order.php" method="POST">';
    echo '<div class="input-group">';
    echo '<label>Email Address</label>';
    echo '<input type="email" name="email" placeholder="email@example.com" required>';
    echo '</div>';

    echo '<div class="input-group">';
    echo '<label>Phone Number</label>';
    echo '<input type="tel" name="phone" placeholder="06XXXXXXXX" required>';
    echo '</div>';

    echo '<div class="input-group">';
    echo '<label>Delivery Address</label>';
    echo '<textarea name="address" placeholder="Street, City, Zip Code" required></textarea>';
    echo '</div>';

    echo '<hr style="border: 0.5px solid #444; margin: 15px 0;">';
    echo '<button type="submit" class="submit-order-btn">Complete Purchase</button>';
    echo '</form>';

    echo '</div>';
    echo '</aside>';
}

echo '</main>';

include("../includes/footer.php");
?>

<script>
function changeQty(productId, delta) {
    let action = delta > 0 ? "add" : "remove";

    fetch("../ajax/cart_action.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "action=" + action + "&product_id=" + productId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let qtyElement = document.getElementById("qty-" + productId);
            let totalElement = document.getElementById("grand-total");

            if (qtyElement) {
                qtyElement.innerText = data.quantity;
            }

            if (totalElement) {
                totalElement.innerText = data.total;
            }
        }
    });
}
</script>