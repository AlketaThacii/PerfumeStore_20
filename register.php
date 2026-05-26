<?php
session_start();

require_once 'includes/db.php';
require_once 'includes/send_verification_email.php';
require_once 'includes/security.php';

$errors = [];
$success = "";

$username = "";
$email = "";

if (isset($_SESSION["register_error"])) {
    $errors[] = $_SESSION["register_error"];
    unset($_SESSION["register_error"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    $waitSeconds = rate_limit_remaining_seconds("register", 5, 600);

    if (!csrf_is_valid()) {
        $errors[] = "Your session expired. Please refresh the page and try again.";
    } elseif ($waitSeconds > 0) {
        $errors[] = "Too many registration attempts. Please try again in " . ceil($waitSeconds / 60) . " minute(s).";
    } else {
        if ($username === "" || $email === "" || $password === "" || $confirmPassword === "") {
            rate_limit_hit("register");
            $errors[] = "Please fill in all fields.";
        }

        if (!preg_match('/^[a-zA-Z0-9_.]{8,30}$/', $username)) {
            rate_limit_hit("register");
            $errors[] = "Username must be 8–30 characters and can only contain letters, numbers, underscores (_) and dots (.). Example: john_doe or john.doe";
        }

        if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            rate_limit_hit("register");
            $errors[] = "Please enter a valid email address.";
        }

        if ($password !== "" && strlen($password) < 8) {
            rate_limit_hit("register");
            $errors[] = "Password must be at least 8 characters.";
        }

        if ($password !== "" && !preg_match('/[A-Z]/', $password)) {
            rate_limit_hit("register");
            $errors[] = "Password must contain at least one uppercase letter.";
        }

        if ($password !== "" && !preg_match('/[0-9]/', $password)) {
            rate_limit_hit("register");
            $errors[] = "Password must contain at least one number.";
        }

        if ($password !== "" && !preg_match('/[\W_]/', $password)) {
            rate_limit_hit("register");
            $errors[] = "Password must contain at least one special character (@, !, # etc.).";
        }

        if ($password !== $confirmPassword) {
            rate_limit_hit("register");
            $errors[] = "Passwords do not match.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $existingUsername = $stmt->get_result()->fetch_assoc();

        if ($existingUsername) {
            rate_limit_hit("register");
            $errors[] = "Username already exists.";
        }

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $existingEmail = $stmt->get_result()->fetch_assoc();

        if ($existingEmail) {
            rate_limit_hit("register");
            $errors[] = "Email already exists.";
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $verificationCode = (string) random_int(100000, 999999);
        $verificationCodeHash = password_hash($verificationCode, PASSWORD_DEFAULT);
        $verificationExpiresAt = date("Y-m-d H:i:s", time() + 300);
        $emailVerified = 0;
        $finalAdminCode = null;

        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password, role, admin_code, email_verified, verification_code_hash, verification_expires_at)
             VALUES (?, ?, ?, 'user', ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssiss",
            $username,
            $email,
            $hashedPassword,
            $finalAdminCode,
            $emailVerified,
            $verificationCodeHash,
            $verificationExpiresAt
        );

        if ($stmt->execute()) {
            $_SESSION["pending_user_id"] = $stmt->insert_id;
            $_SESSION["pending_email"] = $email;

            $sent = sendVerificationCode($email, $username, $verificationCode);

            if ($sent) {
                rate_limit_reset("register");
                header("Location: verify-register.php");
                exit();
            }

            $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ? AND email_verified = 0");
            $deleteStmt->bind_param("i", $_SESSION["pending_user_id"]);
            $deleteStmt->execute();

            unset($_SESSION["pending_user_id"], $_SESSION["pending_email"]);
            rate_limit_hit("register");
            $errors[] = "Verification email could not be sent. Please try again.";
        } else {
            rate_limit_hit("register");
            $errors[] = "Registration failed. Please try again.";
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="/PerfumeStore_20/assets/css/login.css">

<main class="login-page">
    <div class="login-box">
        <span class="auth-eyebrow">Create Account</span>
        <h1>Register</h1>
        <p>User accounts must verify their email before logging in.</p>

        <?php foreach ($errors as $error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endforeach; ?>

        <?php if ($success !== ""): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <?php echo csrf_field(); ?>

            <label>Username</label>
            <input
                type="text"
                name="username"
                value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"
                required>

            <label>Email</label>
            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                required>

            <label>Password</label>
            <div class="password-box">
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" onclick="togglePassword('password', this)">Show</span>
            </div>

            <label>Confirm Password</label>
            <div class="password-box">
                <input type="password" id="confirmPassword" name="confirm_password" required>
                <span class="toggle-password" onclick="togglePassword('confirmPassword', this)">Show</span>
            </div>

            <button type="submit">Register</button>
        </form>

        <p class="auth-link">
            Already have an account?
            <a href="/PerfumeStore_20/login.php">Log in</a>
        </p>
    </div>
</main>

<script>
function togglePassword(inputId, icon) {
    let input = document.getElementById(inputId);

    if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = "Hide";
    } else {
        input.type = "password";
        icon.innerHTML = "Show";
    }
}
</script>

<?php include 'includes/footer.php'; ?>