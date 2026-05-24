<?php
session_start();

require_once 'includes/db.php';

$error = "";
$code = "";

if (!isset($_SESSION["pending_user_id"])) {
    header("Location: register.php");
    exit();
}

$pendingUserId = (int) $_SESSION["pending_user_id"];

$stmt = $conn->prepare(
    "SELECT id, email, verification_code_hash, verification_expires_at, email_verified
     FROM users
     WHERE id = ? AND role = 'user'
     LIMIT 1"
);
$stmt->bind_param("i", $pendingUserId);
$stmt->execute();
$pendingUser = $stmt->get_result()->fetch_assoc();

if (!$pendingUser || (int) $pendingUser["email_verified"] === 1) {
    unset($_SESSION["pending_user_id"], $_SESSION["pending_email"]);
    header("Location: register.php");
    exit();
}

if (strtotime($pendingUser["verification_expires_at"]) < time()) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND email_verified = 0");
    $stmt->bind_param("i", $pendingUserId);
    $stmt->execute();

    unset($_SESSION["pending_user_id"], $_SESSION["pending_email"]);

    $_SESSION["register_error"] = "Verification code expired. Please register again.";

    header("Location: register.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = trim($_POST["code"] ?? "");

    if ($code === "") {
        $error = "Please enter the verification code.";
    } elseif (!preg_match('/^[0-9]{6}$/', $code)) {
        $error = "Verification code must contain 6 digits.";
    } elseif (!password_verify($code, $pendingUser["verification_code_hash"])) {
        $error = "Verification code is incorrect.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE users
             SET email_verified = 1,
                 verification_code_hash = NULL,
                 verification_expires_at = NULL
             WHERE id = ?"
        );
        $stmt->bind_param("i", $pendingUserId);

        if ($stmt->execute()) {
            unset($_SESSION["pending_user_id"], $_SESSION["pending_email"]);
            $_SESSION["register_success"] = "Email verified successfully. You can now log in.";

            header("Location: login.php");
            exit();
        } else {
            $error = "Account could not be created. Please try again.";
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="/PerfumeStore_20/assets/css/login.css">

<main class="login-page">
    <div class="login-box">
        <span class="auth-eyebrow">Email Verification</span>
        <h1>Verify Email</h1>
        <p>We sent a 6-digit code to your email. Enter it to complete registration.</p>

        <?php if ($error !== ""): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Verification Code</label>
            <input
                type="text"
                name="code"
                maxlength="6"
                placeholder="Enter 6-digit code"
                value="<?php echo htmlspecialchars($code, ENT_QUOTES, 'UTF-8'); ?>"
                required>

            <button type="submit">Verify Email</button>
        </form>

        <p class="auth-link">
            Code expires after 5 minutes.
        </p>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
