<?php
session_start();
include("db.php"); // Lidhja me databazën që ka bërë Anëtari 1

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sigurohemi që shporta nuk është e zbrazët
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: ../cart.php");
        exit;
    }

    // Marrim të dhënat nga forma dhe i pastrojmë (Mbrojtje nga SQL Injection bazë)
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $address   = mysqli_real_escape_string($conn, $_POST['address']);
    $city      = mysqli_real_escape_string($conn, $_POST['city']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Llogarisim totalin
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['qty'];
    }

    // 1. Ruajmë porosinë kryesore në tabelën `orders`
    // Përdorim Prepared Statement sepse kërkohet patjetër te Faza II për siguri
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, address, city, phone, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $full_name, $address, $city, $phone, $total);
    
    if ($stmt->execute()) {
        // Porosia u krye me sukses!
        
        // 2. ZBRAZIM SHPORTËN pas blerjes së suksesshme
        $_SESSION['cart'] = [];

        // Shfaqim një mesazh falënderimi
        echo "<script>
                alert('Porosia juaj u pranua me sukses! Faleminderit.');
                window.location.href = '../index.php';
              </script>";
        exit;
    } else {
        echo "Ka ndodhur një gabim gjatë procesimit të porosisë: " . $conn->error;
    }
    
    $stmt->close();
} else {
    header("Location: ../cart.php");
    exit;
}
?>