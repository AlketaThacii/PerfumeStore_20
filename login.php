<?php 
session_start(); 
include 'includes/header.php'; 
include 'includes/navbar.php'; 

$users = [
    [
        "username" => "admin",
        "password" => "123",
        "role" => "admin"
    ],
    [
        "username" => "user",
        "password" => "123",
        "role" => "user"
    ]
];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    foreach ($users as $user) {
        if ($username == $user["username"] && $password == $user["password"]) {
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            header("Location: index.php");
            exit();
        }
    }

    $error = "Username ose password gabim!";
}
?>

<link rel="stylesheet" href="/PERFUMESTORE_20/assets/css/login.css">
<main class="login-page">
    <div class="login-box">

        <h1>Login</h1>
        <p>Welcome back to Maison De Parfum</p>

        <?php if ($error != ""): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

    </div>
</main>

<?php include 'includes/footer.php'; ?>