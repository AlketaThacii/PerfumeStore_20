<?php
session_start();
 
require_once 'includes/db.php';
require_once 'includes/security.php';
 
// Nese admini eshte tashme i kyçur, ridrejto
if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
   header("Location: index.php");
    exit();
}
 
$error = "";
$email = "";
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email      = strtolower(trim($_POST["email"] ?? ""));
    $password   = $_POST["password"] ?? "";
    $adminCode  = trim($_POST["admin_code"] ?? "");
 
    $rateLimitName = "admin_login_" . ($email !== "" ? $email : "empty");
    $waitSeconds   = rate_limit_remaining_seconds($rateLimitName, 5, 300);
 
    if (!csrf_is_valid()) {
        $error = "Your session expired. Please refresh the page and try again.";
    } elseif ($waitSeconds > 0) {
        $error = "Too many login attempts. Please try again in " . ceil($waitSeconds / 60) . " minute(s).";
    } elseif ($email === "" || $password === "" || $adminCode === "") {
        rate_limit_hit($rateLimitName);
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        rate_limit_hit($rateLimitName);
        $error = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id, username, email, password, role
             FROM users
             WHERE email = ? AND role = 'admin' AND admin_code = ?
             LIMIT 1"
        );
 
        $stmt->bind_param("ss", $email, $adminCode);
        $stmt->execute();
 
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
 
        if ($user && password_verify($password, $user["password"])) {
            rate_limit_reset($rateLimitName);
            session_regenerate_id(true);
 
            $_SESSION["user_id"]  = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"]    = $user["email"];
            $_SESSION["role"]     = $user["role"];
 
            header("Location: pages/products.php");
            exit();
        } else {
            rate_limit_hit($rateLimitName);
            $error = "Credentials are incorrect.";
        }
    }
}
 
include 'includes/header.php';
include 'includes/navbar.php';
?>
 
<link rel="stylesheet" href="/PerfumeStore_20/assets/css/login.css">
 
<main class="login-page">
    <div class="login-box">
        <span class="auth-eyebrow">Admin Access</span>
        <h1>Admin Login</h1>
        <p>This page is for administrators only.</p>
 
        <?php if ($error !== ""): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
 
        <form method="POST" action="">
            <?php echo csrf_field(); ?>
 
            <label>Email</label>
            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="Admin email"
                required>
 
            <label>Password</label>
            <div class="password-box">
                <input type="password" id="loginPassword" name="password" placeholder="Admin password" required>
                <span class="toggle-password" onclick="togglePassword('loginPassword', this)">Show</span>
            </div>
 
            <label>Admin Code</label>
            <div class="password-box">
                <input type="password" id="adminCode" name="admin_code" placeholder="Admin code" required>
                <span class="toggle-password" onclick="togglePassword('adminCode', this)">Show</span>
            </div>
 
            <button type="submit">Login</button>
        </form>
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