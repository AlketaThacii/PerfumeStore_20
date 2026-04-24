<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $price = $_POST['price'];

    $found = false;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $name) {
            $item['qty']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            "name" => $name,
            "price" => $price,
            "qty" => 1
        ];
    }
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['qty'];
    }
    echo json_encode([
        "status" => "success",
        "count" => $count
    ]);
}
