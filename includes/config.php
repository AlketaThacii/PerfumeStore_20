<?php
require_once("../classes/Products.php");

$products = [
    new Product(1, "Dior Sauvage", 120, "men", "../assets/images/image12.jpg"),
    new Product(2, "Bleu de Chanel", 140, "men", "../assets/images/image14.avif"),
    new Product(3, "Acqua di Gio", 110, "men", "../assets/images/image4.webp"),
    new Product(4, "Versace Eros", 100, "men", "../assets/images/image7.jpg"),
    new Product(5, "Tom Ford Noir", 160, "men", "../assets/images/image2.avif"),

    new Product(6, "Versace", 150, "women", "../assets/images/image14.avif"),
    new Product(7, "YSL Libre", 130, "women", "../assets/images/image44.avif"),
    new Product(8, "Gucci Bloom", 125, "women", "../assets/images/image1.jpg"),
    new Product(9, "Dior J'adore", 145, "women", "../assets/images/perfumee.jpg"),
    new Product(10, "Armani My Way", 135, "women", "../assets/images/perfume.webp"),

    new Product(11, "CK One", 90, "unisex", "../assets/images/img-12.jpg"),
    new Product(12, "Maison Margiela Replica", 170, "unisex", "../assets/images/image12.jpg"),
    new Product(13, "Le Labo Santal 33", 200, "unisex", "../assets/images/image2.avif"),
    new Product(14, "Byredo Gypsy Water", 180, "unisex", "../assets/images/image4.webp"),
    new Product(15, "Tom Ford Black Orchid", 155, "unisex", "../assets/images/image7.jpg")
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