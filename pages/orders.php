<?php
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /PerfumeStore_20/index.php");
    exit();
}

$orderEmail = $_SESSION['order_email'] ?? 'No order yet';
$orderPhone = $_SESSION['order_phone'] ?? 'No order yet';
$orderAddress = $_SESSION['order_address'] ?? 'No address saved';

echo '<main class="orders-page">';
    echo '<div class="orders-container">';

        echo '<h1>Orders</h1>';
        echo '<p>Manage customer orders from Maison De Parfum.</p>';

        echo '<div class="orders-stats">';
            echo '<div class="order-stat-card">';
                echo '<h2>1</h2>';
                echo '<p>Total Orders</p>';
            echo '</div>';

            echo '<div class="order-stat-card">';
                echo '<h2>1</h2>';
                echo '<p>Pending</p>';
            echo '</div>';

            echo '<div class="order-stat-card">';
                echo '<h2>0</h2>';
                echo '<p>Completed</p>';
            echo '</div>';
        echo '</div>';

        echo '<table class="order-table">';
            echo '<tr>';
                echo '<th>Email</th>';
                echo '<th>Phone</th>';
                echo '<th>Address</th>';
                echo '<th>Status</th>';
            echo '</tr>';

            echo '<tr>';
                echo '<td>' . htmlspecialchars($orderEmail) . '</td>';
                echo '<td>' . htmlspecialchars($orderPhone) . '</td>';
                echo '<td>' . htmlspecialchars($orderAddress) . '</td>';
                echo '<td><span class="status">Pending</span></td>';
            echo '</tr>';
        echo '</table>';

    echo '</div>';
echo '</main>';

include '../includes/footer.php';
?>