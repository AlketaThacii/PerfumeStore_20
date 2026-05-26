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
    $_SESSION["product_message"] = "Product ID is not valid.";
    header("Location: products.php");
    exit;
}

$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    $_SESSION["product_message"] = "Product was not found in database.";
    header("Location: products.php");
    exit;
}

$delete = $conn->prepare("DELETE FROM products WHERE id = ?");
$delete->bind_param("i", $id);

if (!$delete->execute()) {
    $_SESSION["product_message"] = "Product was not deleted: " . $delete->error;
    $delete->close();
    header("Location: products.php");
    exit;
}

if ($delete->affected_rows < 1) {
    $_SESSION["product_message"] = "Product was not deleted because no row was affected.";
    $delete->close();
    header("Location: products.php");
    exit;
}

$delete->close();

if (!empty($product["image"])) {
    $imagePath = $product["image"];

    if (strpos($imagePath, "../") === 0) {
        $imagePath = __DIR__ . "/" . $imagePath;
    }

    $realImagePath = realpath($imagePath);
    $imagesDir = realpath(__DIR__ . "/../assets/images");

    if ($realImagePath && $imagesDir && strpos($realImagePath, $imagesDir) === 0) {
        unlink($realImagePath);
    }
}

$_SESSION["product_message"] = "Product was deleted successfully.";
header("Location: products.php");
exit;