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

<main class="checkout-page" style="padding: 50px 10%; min-height: 60vh; display: flex; gap: 50px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 300px;">
        <h2>Checkout</h2>
        <br>

        <form action="process_order.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Phone Number</label>
                <input type="tel" name="phone" required style="width: 100%; padding: 10px;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Delivery Address</label>
                <textarea name="address" required style="width: 100%; padding: 10px;"></textarea>
            </div>

            <button type="submit" style="background: black; color: white; padding: 12px; border: none; font-weight: bold; cursor: pointer; border-radius: 4px;">
                Complete Purchase ($<?php echo number_format($total, 2); ?>)
            </button>
        </form>
    </div>

    <div style="flex: 1; min-width: 300px; background: #f9f9f9; padding: 25px; border-radius: 8px; height: fit-content;">
        <h3>Order Summary</h3>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">

        <?php foreach ($cartItems as $item): ?>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span>
                    <?php echo htmlspecialchars($item["name"], ENT_QUOTES, "UTF-8"); ?>
                    <strong>x<?php echo (int)$item["qty"]; ?></strong>
                </span>
                <span>$<?php echo number_format($item["subtotal"], 2); ?></span>
            </div>
        <?php endforeach; ?>

        <hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">

        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem;">
            <span>TOTAL:</span>
            <span style="color: green;">$<?php echo number_format($total, 2); ?></span>
        </div>
    </div>
</main>

<?php include("../includes/footer.php"); ?>