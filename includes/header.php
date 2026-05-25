<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maison De Parfum</title>
    <link rel="stylesheet" href="/PerfumeStore_20/assets/css/style.css?v=1">
    <link rel="stylesheet" href="../assets/css/infoPages.css">
</head>

<body>

    <button id="theme-toggle" class="theme-btn">🌙</button>