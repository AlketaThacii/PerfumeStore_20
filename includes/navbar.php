<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$count = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $count += (int)$qty;
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

            <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                <a href="/PerfumeStore_20/pages/orders.php">Orders</a>
            <?php endif; ?>

            <?php if (isset($_SESSION["username"])): ?>
                <?php if ($_SESSION["role"] !== "admin"): ?>
                    <a href="/PerfumeStore_20/pages/profile.php">Profile</a>
                <?php endif; ?>
                <a href="/PerfumeStore_20/logout.php">LogOut</a>
            <?php else: ?>
                <a href="/PerfumeStore_20/login.php">Log In</a>
            <?php endif; ?>
        </nav>

    </div>
</header>