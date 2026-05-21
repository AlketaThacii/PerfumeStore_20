<?php

include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: products.php");
    exit;
}

include("../includes/header.php");
include("../includes/navbar.php");
?>

<main>
    <h1>Edit Product</h1>
</main>

<?php include("../includes/footer.php"); ?>