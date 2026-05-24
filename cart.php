<?php
session_start();
include "db.php";

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<h2>Your Cart</h2>

<table border="1">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
    </tr>

    <?php foreach ($cart as $id => $qty): ?>
        <?php
        $id = (int)$id;
        $query = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
        $product = mysqli_fetch_assoc($query);

        if (!$product) continue;

        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
        ?>

        <tr>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $product['price']; ?> €</td>
            <td>
                <input type="number" class="qty-input" data-id="<?php echo $id; ?>" value="<?php echo $qty; ?>" min="1">
            </td>
            <td><?php echo $subtotal; ?> €</td>
            <td>
                <button class="remove-item" data-id="<?php echo $id; ?>">Remove</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h3>Total: <?php echo $total; ?> €</h3>

<a href="checkout.php">Checkout</a>

<script>
document.querySelectorAll(".qty-input").forEach(input => {
    input.addEventListener("change", function () {
        fetch("ajax/cart_action.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "action=update&id=" + this.dataset.id + "&quantity=" + this.value
        })
        .then(response => response.json())
        .then(() => location.reload());
    });
});

document.querySelectorAll(".remove-item").forEach(button => {
    button.addEventListener("click", function () {
        fetch("ajax/cart_action.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "action=remove&id=" + this.dataset.id
        })
        .then(response => response.json())
        .then(() => location.reload());
    });
});
</script>