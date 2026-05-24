<?php
session_start();

require_once 'includes/db.php';

$error = "";
$code = "";

if (
    !isset($_SESSION["pending_username"]) ||
    !isset($_SESSION["pending_email"]) ||
    !isset($_SESSION["pending_password"]) ||
    !isset($_SESSION["pending_role"]) ||
    !isset($_SESSION["register_code"]) ||
    !isset($_SESSION["register_code_expires"])
) {
    header("Location: register.php");
    exit();
}

if (time() > $_SESSION["register_code_expires"]) {
    session_unset();
    session_destroy();

    session_start();
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
    } elseif (!password_verify($code, $_SESSION["register_code"])) {
        $error = "Verification code is incorrect.";
    } else {
        $username = $_SESSION["pending_username"];
        $email = $_SESSION["pending_email"];
        $password = $_SESSION["pending_password"];
        $role = $_SESSION["pending_role"];
        $adminCode = $_SESSION["pending_admin_code"];

        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password, role, admin_code)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("sssss", $username, $email, $password, $role, $adminCode);

        if ($stmt->execute()) {
            session_unset();
            session_destroy();

            session_start();
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