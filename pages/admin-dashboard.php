<?php
include('../includes/header.php');
include('../includes/navbar.php');
require_once('../includes/db.php');

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /PerfumeStore_20/index.php");
    exit();
}