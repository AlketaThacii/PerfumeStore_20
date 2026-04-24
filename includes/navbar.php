<?php
$count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['qty'] ?? 1;
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

            <div class="dropdown">
                <span class="dropbtn">Register ▾</span>
                <div class="dropdown-content">
                    <a href="/PerfumeStore_20/login.php">Log In</a>
                    <a href="#">Sign Up</a>
                </div>
            </div>
            <a href="cart.php">🛒 Cart (<span id="cart-count"><?php echo $count; ?></span>)</a>
        </nav>

    </div>
</header>