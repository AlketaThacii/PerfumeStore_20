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
// ── MERR TE DHENAT ───────────────────────────────────────────────────────────

// Statistika
$totalUsers     = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetch_row()[0];
$totalOrders    = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$totalRevenue   = $conn->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status = 'completed'")->fetch_row()[0];
$totalFeedbacks = $conn->query("SELECT COUNT(*) FROM feedbacks")->fetch_row()[0];



<style>
.dash {
    padding: 40px 5%;
    background: #050505;
    min-height: 100vh;
    color: #fff;
}
.dash h1 {
    color: #d4af37;
    font-size: 2rem;
    margin-bottom: 8px;
}
.dash-subtitle {
    color: #777;
    margin-bottom: 36px;
    font-size: 0.95rem;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 48px;
}
.stat-card {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,0.25);
    border-radius: 12px;
    padding: 24px 20px;
    text-align: center;
}
.stat-card .stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #d4af37;
    display: block;
}
.stat-card .stat-label {
    color: #888;
    font-size: 0.85rem;
    margin-top: 4px;
}

/* Section titles */
.section-title {
    color: #d4af37;
    font-size: 1.3rem;
    border-bottom: 1px solid rgba(212,175,55,0.2);
    padding-bottom: 10px;
    margin: 48px 0 20px;
}

/* Tables */
.dash-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
    background: #0f0f0f;
    border-radius: 12px;
    overflow: hidden;
}
.dash-table th {
    background: #1a1a1a;
    color: #d4af37;
    padding: 12px 14px;
    text-align: left;
    font-weight: 600;
    white-space: nowrap;
}
.dash-table td {
    padding: 12px 14px;
    border-top: 1px solid #1e1e1e;
    color: #ccc;
    vertical-align: middle;
}
.dash-table tr:hover td {
    background: #141414;
}

/* Badges */
.badge-verified {
    background: rgba(46,204,113,0.15);
    color: #2ecc71;
    border: 1px solid #2ecc71;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.75rem;
}
.badge-unverified {
    background: rgba(231,76,60,0.15);
    color: #e74c3c;
    border: 1px solid #e74c3c;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.75rem;
}
.badge-pending   { background:rgba(212,175,55,0.15); color:#d4af37; border:1px solid #d4af37; padding:2px 8px; border-radius:20px; font-size:0.75rem; }
.badge-completed { background:rgba(46,204,113,0.15); color:#2ecc71; border:1px solid #2ecc71; padding:2px 8px; border-radius:20px; font-size:0.75rem; }
.badge-cancelled { background:rgba(231,76,60,0.15);  color:#e74c3c; border:1px solid #e74c3c; padding:2px 8px; border-radius:20px; font-size:0.75rem; }

/* Avatar */
.avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #d4af37;
    vertical-align: middle;
    margin-right: 8px;
}
.avatar-placeholder {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #1e1e1e;
    border: 2px solid #333;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    color: #d4af37;
    font-weight: bold;
    vertical-align: middle;
    margin-right: 8px;
}

/* Stars */
.stars { color: #d4af37; letter-spacing: 2px; }

/* Buttons */
.btn-delete {
    background: transparent;
    border: 1px solid #e74c3c;
    color: #e74c3c;
    padding: 4px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.8rem;
    white-space: nowrap;
}
.btn-delete:hover { background: rgba(231,76,60,0.1); }

/* Status select */
.status-select {
    background: #1a1a1a;
    color: #fff;
    border: 1px solid #333;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 0.8rem;
    cursor: pointer;
}

/* Nav tabs */
.dash-nav {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}
.dash-nav a {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,0.3);
    color: #d4af37;
    padding: 8px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background 0.2s;
}
.dash-nav a:hover { background: rgba(212,175,55,0.1); }

@media (max-width: 768px) {
    .dash-table { font-size: 0.78rem; }
    .dash-table th, .dash-table td { padding: 8px; }
}
</style>

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

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-number"><?php echo $totalUsers; ?></span>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $totalOrders; ?></span>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-card">
        <span class="stat-number">$<?php echo number_format($totalRevenue, 2); ?></span>
        <div class="stat-label">Revenue (Completed)</div>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $totalFeedbacks; ?></span>
        <div class="stat-label">Feedbacks</div>
    </div>
</div>

<main class="dash">
    <h1>Admin Dashboard</h1>
    <p class="dash-subtitle">Overview of all users, orders and feedback</p>

    <!-- Quick nav -->
    <div class="dash-nav">
        <a href="#users">👥 Users</a>
        <a href="#orders">📦 Orders</a>
        <a href="#feedbacks">💬 Feedback</a>
        <a href="orders.php">⚙️ Orders Manager</a>
        <a href="add-product.php">➕ Add Product</a>
    </div>

    <!-- ── STATS ─────────────────────────────────────────────────── -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?php echo $totalUsers; ?></span>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo $totalOrders; ?></span>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-card">
            <span class="stat-number">$<?php echo number_format($totalRevenue, 2); ?></span>
            <div class="stat-label">Revenue (Completed)</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo $totalFeedbacks; ?></span>
            <div class="stat-label">Feedbacks</div>
        </div>
    </div>