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

<form method="POST"
      onsubmit="return confirm('Delete this feedback?')">
    <input type="hidden" name="delete_feedback" value="1">
    <input type="hidden" name="feedback_id" value="<?php echo $fb['id']; ?>">
    <button type="submit" class="btn-delete">✕ Delete</button>
</form>