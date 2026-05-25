<?php
include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];
$message = trim($_POST["feedback"] ?? "");
$rating = (int)($_POST["rating"] ?? 5);

if ($rating < 1 || $rating > 5) {
    $rating = 5;
}

if ($message !== "") {
    $stmt = $conn->prepare("INSERT INTO feedbacks (user_id, message, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $userId, $message, $rating);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../index.php?feedback=success#feedback-section");
exit;