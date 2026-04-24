<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (isset($_POST['add_to_cart'])) {

    $found = false;

    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['name'] === $_POST['name']) {
            $cartItem['qty']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            "name" => $_POST['name'],
            "price" => $_POST['price'],
            "qty" => 1
        ];
    }
}
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
if (isset($_GET['increase'])) {
    $index = $_GET['increase'];
    $_SESSION['cart'][$index]['qty']++;
}
if (isset($_GET['decrease'])) {
    $index = $_GET['decrease'];

    if ($_SESSION['cart'][$index]['qty'] > 1) {
        $_SESSION['cart'][$index]['qty']--;
    } else {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/css/cart.css">
</head>

<body>

<h1>🛒 Your Cart</h1>

<?php if (empty($_SESSION['cart'])): ?>
    <p class="empty">Your cart is empty</p>
<?php else: ?>

<table class="cart-table">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Action</th>
    </tr>

    <?php 
    $total = 0;

    foreach ($_SESSION['cart'] as $index => $item): 
        $itemTotal = $item['price'] * $item['qty'];
        $total += $itemTotal;
    ?>

    <tr>
        <td><?php echo $item['name']; ?></td>

        <td><?php echo number_format($item['price'], 2); ?> €</td>

        <td>
            <a href="?decrease=<?php echo $index; ?>" class="qty-btn">-</a>
            <span class="qty-number"><?php echo $item['qty']; ?></span>
            <a href="?increase=<?php echo $index; ?>" class="qty-btn">+</a>
        </td>

        <td><?php echo number_format($itemTotal, 2); ?> €</td>

        <td>
            <a class="remove" href="?remove=<?php echo $index; ?>">Remove</a>
        </td>
    </tr>

    <?php endforeach; ?>

</table>

<div class="total">
    Total: <?php echo number_format($total, 2); ?> €
</div>

<?php endif; ?>

<a href="index.php" class="btn">← Continue Shopping</a>

</body>
</html>