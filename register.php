<?php
session_start();

require_once 'includes/db.php';
require_once 'includes/send_verification_email.php';

$errors = [];
$success = "";

$username = "";
$email = "";
$role = "user";
$adminCode = "";

$validRoles = ["user", "admin"];
$adminRegisterCode = "MAISON-ADMIN-2026";

if (isset($_SESSION["register_error"])) {
    $errors[] = $_SESSION["register_error"];
    unset($_SESSION["register_error"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";
    $role = $_POST["role"] ?? "user";
    $adminCode = trim($_POST["admin_code"] ?? "");

    if ($username === "" || $email === "" || $password === "" || $confirmPassword === "") {
        $errors[] = "Please fill in all fields.";
    }

    if (!in_array($role, $validRoles, true)) {
        $errors[] = "Please choose a valid account type.";
    }

    if (!preg_match('/^[a-zA-Z0-9_]{8,30}$/', $username)) {
        $errors[] = "Username must be 8–30 characters long and can contain letters, numbers, and underscores (_).";
    }

    if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password !== "" && strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if ($role === "admin" && $adminCode !== $adminRegisterCode) {
        $errors[] = "Admin registration code is not valid.";
    }

    if (empty($errors)) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $existingUsername = $stmt->get_result()->fetch_assoc();

    if ($existingUsername) {
        $errors[] = "Username already exists.";
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $existingEmail = $stmt->get_result()->fetch_assoc();

    if ($existingEmail) {
        $errors[] = "Email already exists.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($role === "admin") {
            $finalAdminCode = null;
            $emailVerified = 1;
            $verificationCodeHash = null;
            $verificationExpiresAt = null;

            $stmt = $conn->prepare(
                "INSERT INTO users (username, email, password, role, admin_code, email_verified, verification_code_hash, verification_expires_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssssiss",
                $username,
                $email,
                $hashedPassword,
                $role,
                $finalAdminCode,
                $emailVerified,
                $verificationCodeHash,
                $verificationExpiresAt
            );

            if ($stmt->execute()) {
                $success = "Admin account created successfully. You can now log in.";
                $username = "";
                $email = "";
                $role = "user";
                $adminCode = "";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }

        } else {
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
                    header("Location: verify-register.php");
                    exit();
                }

                $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ? AND email_verified = 0");
                $deleteStmt->bind_param("i", $_SESSION["pending_user_id"]);
                $deleteStmt->execute();

                unset($_SESSION["pending_user_id"], $_SESSION["pending_email"]);
                $errors[] = "Verification email could not be sent. Please try again.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}}

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

            <label>Account Type</label>
            <div class="role-selector">
                <label class="role-option">
                    <input
                        type="radio"
                        name="role"
                        value="user"
                        <?php echo $role === "user" ? "checked" : ""; ?>>
                    <span>User</span>
                </label>

                <label class="role-option">
                    <input
                        type="radio"
                        name="role"
                        value="admin"
                        <?php echo $role === "admin" ? "checked" : ""; ?>>
                    <span>Admin</span>
                </label>
            </div>

            <label>Admin Code</label>
            <input
                type="password"
                name="admin_code"
                value="<?php echo htmlspecialchars($adminCode, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="Required only for admin accounts">

            <label>Password</label>
            <div class="password-box">
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" onclick="togglePassword('password', this)">👁</span>
            </div>

            <label>Confirm Password</label>
            <div class="password-box">
                <input type="password" id="confirmPassword" name="confirm_password" required>
                <span class="toggle-password" onclick="togglePassword('confirmPassword', this)">👁</span>
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
        icon.innerHTML = "🙈";
    } else {
        input.type = "password";
        icon.innerHTML = "👁";
    }
}
</script>

<?php include 'includes/footer.php'; ?>
