<?php
session_start();
require_once 'includes/db.php';

$errors = [];
$success = "";
$username = "";
$email = "";
$role = "user";
$adminCode = "";
$validRoles = ["user", "admin"];
$adminRegisterCode = "MAISON-ADMIN-2026";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
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

    if ($role === "admin" && $adminCode !== $adminRegisterCode) {
        $errors[] = "Admin registration code is not valid.";
    }

    if ($username !== "" && !preg_match("/^[A-Za-z0-9_]{3,30}$/", $username)) {
        $errors[] = "Username must be 3-30 characters and can contain letters, numbers and underscore.";
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

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $existingUser = $stmt->get_result()->fetch_assoc();

        if ($existingUser) {
            $errors[] = "Username or email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                $success = "Registration completed successfully as " . ucfirst($role) . ". You can now log in.";
                $username = "";
                $email = "";
                $role = "user";
                $adminCode = "";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="/PERFUMESTORE_20/assets/css/login.css">

<main class="login-page">
    <div class="login-box">
        <span class="auth-eyebrow">Create Account</span>
        <h1>Register</h1>
        <p>Create your account with a real email and choose the correct access type.</p>

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
            <div class="role-selector" role="radiogroup" aria-label="Choose account type">
                <label class="role-option <?php echo $role === 'user' ? 'active' : ''; ?>">
                    <input
                        type="radio"
                        name="role"
                        value="user"
                        <?php echo $role === 'user' ? 'checked' : ''; ?>>
                    <span>User</span>
                </label>

                <label class="role-option <?php echo $role === 'admin' ? 'active' : ''; ?>">
                    <input
                        type="radio"
                        name="role"
                        value="admin"
                        <?php echo $role === 'admin' ? 'checked' : ''; ?>>
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
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>

        <p class="auth-link">
            Already have an account?
            <a href="/PerfumeStore_20/login.php">Log in</a>
        </p>
    </div>
</main>

<?php include 'includes/footer.php'; ?>