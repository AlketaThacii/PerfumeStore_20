<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: /PerfumeStore_20/login.php");
    exit();
}

$userId = (int) $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: /PerfumeStore_20/login.php");
    exit();
}

$_SESSION["username"] = $user["username"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];

include 'includes/header.php';
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="/PERFUMESTORE_20/assets/css/login.css">

<main class="profile-page">
    <section class="profile-card">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($user["username"] ?: $user["email"], 0, 1)); ?>
        </div>

        <div class="profile-header">
            <p>My Account</p>
            <h1><?php echo htmlspecialchars($user["username"], ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>

        <div class="profile-info">
            <div class="profile-row">
                <span>Username</span>
                <strong><?php echo htmlspecialchars($user["username"], ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>

            <div class="profile-row">
                <span>Email</span>
                <strong><?php echo htmlspecialchars($user["email"], ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>

            <div class="profile-row">
                <span>Role</span>
                <strong><?php echo htmlspecialchars(ucfirst($user["role"]), ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>
        </div>

        <div class="profile-actions">
            <a href="/PerfumeStore_20/pages/products.php">Shop</a>
            <a href="/PerfumeStore_20/logout.php">Log out</a>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>