<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['qty'];
    }
}
?>

<header class="header">
    <div class="container">

        <h2 class="logo">Maison De Parfum</h2>

        <nav class="navigation">
            <a href="/PerfumeStore_20/index.php">Home</a>
            <a href="/PerfumeStore_20/pages/products.php">Shop</a>
            <a href="/PerfumeStore_20/pages/about.php">About Us</a>
            <a href="/PerfumeStore_20/pages/visitus.php">Visit Us</a>

            <?php if (isset($_SESSION["username"])): ?>
                <a href="/PerfumeStore_20/logout.php">LogOut</a>
            <?php else: ?>
                <a href="/PerfumeStore_20/login.php">Log In</a>
            <?php endif; ?>

            <a href="/PerfumeStore_20/pages/cart.php">
                🛒 Cart (<span id="cart-count"><?php echo $count; ?></span>)
            </a>
        </nav>

    </div>
</header>
