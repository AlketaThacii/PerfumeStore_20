<?php
include("../includes/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart = $_SESSION["cart"] ?? [];

if (empty($cart)) {
    header("Location: products.php");
    exit;
}

$total = 0;
$cartItems = [];

foreach ($cart as $productId => $qty) {
    foreach ($products as $product) {
        if ($product->getId() == $productId) {
            $subtotal = $product->getPrice() * $qty;
            $total += $subtotal;

            $cartItems[] = [
                "name" => $product->getName(),
                "price" => $product->getPrice(),
                "qty" => $qty,
                "subtotal" => $subtotal
            ];
        }
    }
}

include("../includes/header.php");
echo '<link rel="stylesheet" href="../assets/css/checkout.css">';
include("../includes/navbar.php");
?>


<main class="checkout-page">
    <div class="checkout-box">
        <h2>Checkout</h2>
        <p class="checkout-subtitle">
            Please fill in your delivery information to complete your perfume order.
        </p>

        <form action="process_order.php" method="POST" class="checkout-form">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Write your email address" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" placeholder="Write your phone number" required>
            </div>

            <div class="form-group">
                <label>Delivery Address</label>
                <textarea name="address" placeholder="Write your full delivery address" required></textarea>
            </div>

            <button type="submit" class="checkout-btn">
                Complete Purchase ($<?php echo number_format($total, 2); ?>)
            </button>
        </form>
    </div>

    <div class="summary-box">
        <h3>Order Summary</h3>
        <p class="checkout-subtitle">
            Review your selected products before placing the order.
        </p>

        <hr class="summary-line">

        <?php foreach ($cartItems as $item): ?>
            <div class="summary-item">
                <span class="summary-item-name">
                    <?php echo htmlspecialchars($item["name"], ENT_QUOTES, "UTF-8"); ?>
                    <strong>x<?php echo (int)$item["qty"]; ?></strong>
                </span>

                <span class="summary-price">
                    $<?php echo number_format($item["subtotal"], 2); ?>
                </span>
            </div>
        <?php endforeach; ?>

        <hr class="summary-line">

        <div class="summary-total">
            <span>TOTAL:</span>
            <span class="summary-total-price">$<?php echo number_format($total, 2); ?></span>
        </div>
    </div>
</main>

<?php include("../includes/footer.php"); ?>