<?php
session_start();

$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

$emailError = "";
$phoneError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!preg_match("/^[\w\.-]+@[\w\.-]+\.\w+$/", $email)) {
        $emailError = "Email is not valid.";
    }

    if (!preg_match("/^[0-9]{9,12}$/", $phone)) {
        $phoneError = "Phone number must contain 9 to 12 digits.";
    }

    if ($emailError === "" && $phoneError === "") {
        $_SESSION['order_email'] = $email;
        $_SESSION['order_phone'] = $phone;

        echo "<h2>Order completed successfully!</h2>";
        echo "<p>Email: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p>Phone: " . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p>Address: " . htmlspecialchars($address, ENT_QUOTES, 'UTF-8') . "</p>";
    } else {
        echo "<h2>Validation Error</h2>";

        if ($emailError !== "") {
            echo "<p style='color:red;'>" . htmlspecialchars($emailError, ENT_QUOTES, 'UTF-8') . "</p>";
        }

        if ($phoneError !== "") {
            echo "<p style='color:red;'>" . htmlspecialchars($phoneError, ENT_QUOTES, 'UTF-8') . "</p>";
        }

        echo "<p><a href='products.php'>Go back</a></p>";
    }
}
?>
