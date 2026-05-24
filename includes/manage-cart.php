<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['action']) && $data['action'] === 'delete') {
    $product_name = $data['name'];

    if (isset($_SESSION['cart'][$product_name])) {
        unset($_SESSION['cart'][$product_name]);
        
        // Rikalkulojmë totalin e ri pas fshirjes
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        echo json_encode([
            'status' => 'success',
            'total' => number_format($total, 2),
            'cart_empty' => empty($_SESSION['cart'])
        ]);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Produkti nuk u gjet.']);
?>