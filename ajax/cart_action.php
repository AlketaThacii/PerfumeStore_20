<?php
session_start();
include("../includes/config.php");

header("Content-Type: application/json");

$action = $_POST["action"] ?? "";
$productId = isset($_POST["product_id"]) ? (int)$_POST["product_id"] : 0;

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if ($productId <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid product"
    ]);
    exit;
}

if ($action === "add") {
    if (!isset($_SESSION["cart"][$productId])) {
        $_SESSION["cart"][$productId] = 0;
    }

    $_SESSION["cart"][$productId]++;
}

if ($action === "remove") {
    if (isset($_SESSION["cart"][$productId])) {
        $_SESSION["cart"][$productId]--;

        if ($_SESSION["cart"][$productId] <= 0) {
            unset($_SESSION["cart"][$productId]);
        }
    }
}

$total = 0;
$quantity = $_SESSION["cart"][$productId] ?? 0;

foreach ($_SESSION["cart"] as $id => $qty) {
    foreach ($products as $product) {
        if ($product->getId() == $id) {
            $total += $product->getPrice() * $qty;
        }
    }
}

echo json_encode([
    "success" => true,
    "quantity" => $quantity,
    "total" => number_format($total, 2, ".", "")
]);