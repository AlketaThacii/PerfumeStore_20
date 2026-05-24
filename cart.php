<?php
include("../includes/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart = $_SESSION["cart"] ?? [];
$total = 0;

include("../includes/header.php");
echo '<link rel="stylesheet" href="../assets/css/style.css">';
include("../includes/navbar.php");
?>

<main class="order-result-page">
    <div class="order-result-card">
        <h2>Your Cart</h2>

        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
            <a href="products.php" class="btn">Back to Shop</a>
        <?php else: ?>
            <table border="1" style="width:100%; margin-bottom:20px;">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($cart as $productId => $qty): ?>
                    <?php
                    $cartProduct = null;

                    foreach ($products as $product) {
                        if ($product->getId() == $productId) {
                            $cartProduct = $product;
                            break;
                        }
                    }

                    if (!$cartProduct) {
                        continue;
                    }

                    $price = $cartProduct->getPrice();
                    $subtotal = $price * $qty;
                    $total += $subtotal;
                    ?>

                    <tr id="cart-row-<?php echo $productId; ?>">
                        <td><?php echo htmlspecialchars($cartProduct->getName()); ?></td>
                        <td>$<?php echo number_format($price, 2); ?></td>
                        <td>
                            <input
                                type="number"
                                class="qty-input"
                                data-id="<?php echo $productId; ?>"
                                value="<?php echo $qty; ?>"
                                min="1"
                            >
                        </td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <button type="button" class="remove-item" data-id="<?php echo $productId; ?>">
                                Remove
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h3>Total: $<span id="cart-total"><?php echo number_format($total, 2); ?></span></h3>

            <a href="products.php" class="btn">Continue Shopping</a>
            <a href="products.php" class="btn">Checkout</a>
        <?php endif; ?>
    </div>
</main>

<script>
document.querySelectorAll(".qty-input").forEach(input => {
    input.addEventListener("change", function () {
        fetch("../ajax/cart_action.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "action=set&product_id=" + this.dataset.id + "&quantity=" + this.value
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});

document.querySelectorAll(".remove-item").forEach(button => {
    button.addEventListener("click", function () {
        fetch("../ajax/cart_action.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "action=delete&product_id=" + this.dataset.id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});
</script>

<?php include("../includes/footer.php"); ?>