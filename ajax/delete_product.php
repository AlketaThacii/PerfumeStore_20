<?php
session_start();
include("../includes/config.php");

header("Content-Type: application/json");

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["success" => false]);
    exit;
}

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

if ($id <= 0) {
    echo json_encode(["success" => false]);
    exit;
}



echo json_encode(["success" => true]);

?>