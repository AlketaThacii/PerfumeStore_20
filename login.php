<?php
session_start();

require_once 'includes/db.php';

$error = "";
$success = "";
$login = "";
$loginType = "user";

if (isset($_SESSION["register_success"])) {
    $success = $_SESSION["register_success"];
    unset($_SESSION["register_success"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginType = $_POST["login_type"] ?? "user";
    $login = trim($_POST["login"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($login === "" || $password === "") {
        $error = "Please fill in all fields.";
    } else {
        if ($loginType === "admin") {
            $stmt = $conn->prepare(
                "SELECT id, username, email, password, role 
                 FROM users 
                 WHERE admin_code = ? AND role = 'admin' 
                 LIMIT 1"
            );
        } else {
            $stmt = $conn->prepare(
                "SELECT id, username, email, password, role 
                 FROM users 
                 WHERE email = ? AND role = 'user' 
                 LIMIT 1"
            );
        }

        $stmt->bind_param("s", $login);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: pages/products.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Login data or password is incorrect.";
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="/PerfumeStore_20/assets/css/login.css">

<main class="login-page">
    <div class="login-box">
        <span class="auth-eyebrow">Secure Access</span>
        <h1>Login</h1>
        <p>User logs in with verified email. Admin logs in with admin code.</p>

        <?php if ($success !== ""): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Login Type</label>

            <div class="role-selector">
                <label class="role-option">
                    <input
                        type="radio"
                        name="login_type"
                        value="user"
                        <?php echo $loginType === "user" ? "checked" : ""; ?>>
                    <span>User</span>
                </label>

                <label class="role-option">
                    <input
                        type="radio"
                        name="login_type"
                        value="admin"
                        <?php echo $loginType === "admin" ? "checked" : ""; ?>>
                    <span>Admin</span>
                </label>
            </div>

            <label>Email or Admin Code</label>
            <input
                type="text"
                name="login"
                value="<?php echo htmlspecialchars($login, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="User email / Admin code"
                required>

            <label>Password</label>
            <div class="password-box">
                <input type="password" id="loginPassword" name="password" required>
                <span class="toggle-password" onclick="togglePassword('loginPassword', this)">👁</span>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="demo-accounts">
            <p>Access Info</p>
            <div>
                <span>User accounts log in only after email verification.</span>
                <span>Admin accounts log in with admin code and password.</span>
            </div>
        </div>

        <p class="auth-link">
            Don't have an account?
            <a href="/PerfumeStore_20/register.php">Register</a>
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