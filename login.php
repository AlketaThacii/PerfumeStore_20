<?php
session_start();
require_once 'includes/db.php';

$error = "";
$login = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($login === "" || $password === "") {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ? LIMIT 1"
        );
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            header("Location: index.php");
            exit();
        }

        $error = "Username/email or password is incorrect!";
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="/PERFUMESTORE_20/assets/css/login.css">
<main class="login-page">
    <div class="login-box">

        <h1>Login</h1>
        <p>Welcome back to Maison De Parfum</p>

        <?php if ($error !== ""): ?>
            <div class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <label>Username or Email</label>
            <input
                type="text"
                name="login"
                value="<?php echo htmlspecialchars($login, ENT_QUOTES, 'UTF-8'); ?>"
                required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p class="auth-link">
            Don't have an account?
            <a href="/PerfumeStore_20/register.php">Register</a>
        </p>

    </div>
</main>

<?php include 'includes/footer.php'; ?>