<?php
include "../db.php";

$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($conn, $search);

$query = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '%$search%'");

while ($row = mysqli_fetch_assoc($query)) {
    echo '
    <div class="product-card">
        <img src="images/' . $row['image'] . '" width="150">
        <h3>' . $row['name'] . '</h3>
        <p>' . $row['price'] . ' €</p>
        <button class="add-to-cart" data-id="' . $row['id'] . '">Add to Cart</button>
    </div>
    ';
}
?>