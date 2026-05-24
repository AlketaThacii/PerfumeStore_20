<?php
session_start();
include "db.php"; 

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? 0;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($action == "add") {
    $id = (int)$id;

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
}

if ($action == "remove") {
    unset($_SESSION['cart'][$id]);
}

if ($action == "update") {
    $qty = (int)$_POST['quantity'];

    if ($qty <= 0) {
        unset($_SESSION['cart'][$id]);
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
}

$count = array_sum($_SESSION['cart']);

echo json_encode([
    "success" => true,
    "count" => $count
]);
?>