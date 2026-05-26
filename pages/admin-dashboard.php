<?php
include('../includes/header.php');
include('../includes/navbar.php');
require_once('../includes/db.php');

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /PerfumeStore_20/index.php");
    exit();
}
// ── FSHI FEEDBACK (nga dashboard) ────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_feedback"])) {
    $fid = (int)$_POST["feedback_id"];
    $stmt = $conn->prepare("DELETE FROM feedbacks WHERE id = ?");
    $stmt->bind_param("i", $fid);
    $stmt->execute();
    header("Location: admin-dashboard.php");
    exit;
}
// ── FSHI USER ─────────────────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_user"])) {
    $uid = (int)$_POST["user_id"];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    header("Location: admin-dashboard.php");
    exit;
}
// ── NDRYSHO STATUS POROSIE ────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["order_id"], $_POST["status"])) {
    $allowedStatuses = ["pending", "completed", "cancelled"];
    $newStatus = $_POST["status"];
    $orderId   = (int)$_POST["order_id"];
    if (in_array($newStatus, $allowedStatuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
    }
    header("Location: admin-dashboard.php#orders");
    exit;
}






<form method="POST"
      onsubmit="return confirm('Delete this feedback?')">
    <input type="hidden" name="delete_feedback" value="1">
    <input type="hidden" name="feedback_id" value="<?php echo $fb['id']; ?>">
    <button type="submit" class="btn-delete">✕ Delete</button>
</form>
<form method="POST"
      onsubmit="return confirm('Delete user <?php echo htmlspecialchars($u['username']); ?>? This cannot be undone.')">
    <input type="hidden" name="delete_user" value="1">
    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
    <button type="submit" class="btn-delete">✕ Delete</button>
</form>
<form method="POST" action="admin-dashboard.php#orders">
    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
    <select name="status" onchange="this.form.submit()" class="status-select">
        <option value="pending"   <?php echo $order['status']==='pending'   ? 'selected':''; ?>>Pending</option>
        <option value="completed" <?php echo $order['status']==='completed' ? 'selected':''; ?>>Completed</option>
        <option value="cancelled" <?php echo $order['status']==='cancelled' ? 'selected':''; ?>>Cancelled</option>
    </select>
</form>
<span class="badge-<?php echo $order['status']; ?>" style="margin-top:4px;display:inline-block;">
    <?php echo ucfirst($order['status']); ?>
</span>