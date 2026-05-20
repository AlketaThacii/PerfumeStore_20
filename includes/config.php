<?php
require_once("../classes/Products.php");
require_once("db.php");

$products = [];

$query = "
SELECT products.*, categories.name AS category_name
FROM products
LEFT JOIN categories
ON products.category_id = categories.id
";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {

   $products[] = new Product(
    $row['id'],
    $row['name'],
    $row['price'],
    strtolower($row['category_name']),
    $row['image']
);
}



function sortProducts($products, $order) {
    usort($products, function($a, $b) use ($order) {
        $priceA = (float)$a->getPrice();
        $priceB = (float)$b->getPrice();

        return ($order === "asc")
            ? $priceA <=> $priceB
            : $priceB <=> $priceA;
    });

    return $products;
}

function filterProducts($products, $category) {
    if ($category == "all") return $products;

    return array_filter($products, function($p) use ($category) {
        return $p->getCategory() == $category;
    });
}