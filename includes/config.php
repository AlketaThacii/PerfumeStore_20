<?php
require_once("../classes/Products.php");

$products = [
    new Product(1, "Dior Sauvage", 120, "men", "../assets/images/image13.jpg"),
    new Product(2, "Bleu de Chanel", 140, "men", "../assets/images/image12.jpg"),
    new Product(3, "Acqua di Gio", 110, "men", "../assets/images/image4.webp"),
    new Product(4, "Versace Eros", 100, "men", "../assets/images/image14.avif"),
    new Product(5, "Tom Ford Noir", 160, "men", "../assets/images/image2.avif"),

    new Product(6, "Gisada", 150, "women","../assets/images/image9.jpg"),
    new Product(7, "YSL Libre", 130, "women","../assets/images/image44.avif"),
    new Product(8, "Valentino", 125, "women","../assets/images/image23.jpg"),
    new Product(9, "Angel's share", 145, "women","../assets/images/image3.jpg"),
    new Product(10, "Armani My Way", 135, "women","../assets/images/image1.jpg"),

    new Product(16, "Jasmin Noir", 190, "unisex","../assets/images/image5.jpg"),
  new Product(18, "Maison Fragrance", 160, "unisex","../assets/images/image15.jpg"),
     new Product(3, "Amuage", 110, "men","../assets/images/image10.jpg"),
new Product(14, "Versace", 180, "unisex","../assets/images/image14.jpg"),
    new Product(17, "Montale Paris", 210, "unisex","../assets/images/image4.jpg"),
];

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