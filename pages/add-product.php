<?php

include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$message = "";

include("../includes/header.php");
include("../includes/navbar.php");
?>

<main>
    <h1>Add Product</h1>
</main>

<?php include("../includes/footer.php"); ?>