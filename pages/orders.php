<?php
include '../includes/header.php';
include '../includes/navbar.php';
require_once '../includes/db.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /PerfumeStore_20/index.php");
    exit();
}

// Ndrysho statusin nese eshte kerkuar
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["order_id"], $_POST["status"])) {
    $allowedStatuses = ["pending", "completed", "cancelled"];
    $newStatus = $_POST["status"];
    $orderId   = (int)$_POST["order_id"];

    if (in_array($newStatus, $allowedStatuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
    }

    header("Location: orders.php");
    exit();
}

// Lexo te gjitha orders nga db
$result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $result->fetch_all(MYSQLI_ASSOC);

$total     = count($orders);
$pending   = count(array_filter($orders, fn($o) => $o["status"] === "pending"));
$completed = count(array_filter($orders, fn($o) => $o["status"] === "completed"));
?>

<link rel="stylesheet" href="../assets/css/orders.css">

<main class="orders-page">
    <div class="orders-container">

        <h1>Orders</h1>
        <p>Manage customer orders from Maison De Parfum.</p>

        <div class="orders-stats">
            <div class="order-stat-card">
                <h2><?php echo $total; ?></h2>
                <p>Total Orders</p>
            </div>
            <div class="order-stat-card">
                <h2><?php echo $pending; ?></h2>
                <p>Pending</p>
            </div>
            <div class="order-stat-card">
                <h2><?php echo $completed; ?></h2>
                <p>Completed</p>
            </div>
        </div>

        <table class="order-table">
            <tr>
                <th>#</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Items</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="8" style="text-align:center; padding: 20px; color: #aaa;">
                        No orders yet.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <?php $items = json_decode($order["items"], true); ?>
                    <tr>
                        <td>#<?php echo $order["id"]; ?></td>
                        <td><?php echo htmlspecialchars($order["email"]); ?></td>
                        <td><?php echo htmlspecialchars($order["phone"]); ?></td>
                        <td><?php echo htmlspecialchars($order["address"]); ?></td>
                        <td>
                            <?php foreach ($items as $item): ?>
                                <small>
                                    <?php echo htmlspecialchars($item["name"]); ?>
                                    x<?php echo (int)$item["qty"]; ?>
                                </small><br>
                            <?php endforeach; ?>
                        </td>
                        <td>$<?php echo number_format($order["total"], 2); ?></td>
                        <td><?php echo date("d M Y, H:i", strtotime($order["created_at"])); ?></td>
                        <td>
                            <form method="POST" action="orders.php">
                                <input type="hidden" name="order_id" value="<?php echo $order["id"]; ?>">
                                <select name="status" onchange="this.form.submit()" class="status-select status-<?php echo $order['status']; ?>">
                                    <option value="pending"   <?php echo $order["status"] === "pending"   ? "selected" : ""; ?>>Pending</option>
                                    <option value="completed" <?php echo $order["status"] === "completed" ? "selected" : ""; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $order["status"] === "cancelled" ? "selected" : ""; ?>>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

    </div>
</main>

<?php include '../includes/footer.php'; ?>