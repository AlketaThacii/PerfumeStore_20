<?php
session_start();


$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name']) && isset($data['price']) && isset($data['img'])) {
    $name = $data['name'];
    $price = $data['price'];
    $img = $data['img'];

    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    
    if (isset($_SESSION['cart'][$name])) {
        $_SESSION['cart'][$name]['qty'] += 1;
    } else {
        // Përndryshe e shtojmë si produkt të ri në array
        $_SESSION['cart'][$name] = [
            "name" => $name,
            "price" => $price,
            "img" => $img,
            "qty" => 1
        ];
    }

    
    echo json_encode([
        "status" => "success",
        "message" => "U shtua në shportë me sukses!"
    ]);
    exit;
}

echo json_encode(["status" => "error", "message" => "Të dhëna të pasakta"]);