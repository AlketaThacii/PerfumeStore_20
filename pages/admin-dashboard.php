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