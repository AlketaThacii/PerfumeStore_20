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
echo '<link rel="stylesheet" href="../assets/css/style.css">';
include("../includes/navbar.php");
?>

<style>
.checkout-page {
    min-height: 75vh;
    padding: 70px 9%;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 45px;
    background: #050505;
    color: #ffffff;
}

.checkout-box,
.summary-box {
    background: #0f0f0f;
    border: 1px solid rgba(212, 175, 55, 0.35);
    border-radius: 12px;
    padding: 34px;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.45);
}

.checkout-box h2,
.summary-box h3 {
    color: #d4af37;
    margin-bottom: 12px;
    font-size: 2rem;
}

.checkout-subtitle {
    color: #cfcfcf;
    margin-bottom: 28px;
    line-height: 1.5;
}

.checkout-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group label {
    display: block;
    color: #d4af37;
    font-weight: bold;
    margin-bottom: 8px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 14px 15px;
    border: 1px solid #3a3a3a;
    border-radius: 8px;
    background: #ffffff;
    color: #111111;
    font-size: 1rem;
    outline: none;
}

.form-group textarea {
    min-height: 115px;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
}

.checkout-btn {
    margin-top: 8px;
    background: #d4af37;
    color: #000000;
    padding: 15px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 1rem;
    cursor: pointer;
}

.checkout-btn:hover {
    background: #f0cf5a;
}

.summary-box {
    height: fit-content;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    padding: 12px 0;
    color: #ffffff;
}

.summary-item-name {
    color: #ffffff;
}

.summary-item-name strong {
    color: #d4af37;
}

.summary-price {
    color: #d4af37;
    font-weight: bold;
    white-space: nowrap;
}

.summary-line {
    border: 0;
    border-top: 1px solid rgba(212, 175, 55, 0.25);
    margin: 18px 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: bold;
}

.summary-total-price {
    color: #d4af37;
    font-size: 1.4rem;
}

@media (max-width: 850px) {
    .checkout-page {
        grid-template-columns: 1fr;
        padding: 45px 6%;
    }
}
</style>

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