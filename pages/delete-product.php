<?php

include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "admin"
) {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: products.php");
    exit;
}
$stmt = $conn->prepare(
    "SELECT image
     FROM products
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$product =
    $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$product) {
    header("Location: products.php");
    exit;
}
$delete =
    $conn->prepare(
        "DELETE FROM products
         WHERE id = ?"
    );

$delete->bind_param("i", $id);

if ($delete->execute()) {

    $delete->close();

    header("Location: products.php");
    exit;
}

include("../includes/header.php");
include("../includes/navbar.php");
?>

<main>
    <h1>Delete Product</h1>
</main>

<?php include("../includes/footer.php"); ?>