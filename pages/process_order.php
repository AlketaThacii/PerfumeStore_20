<?php
session_start();
include("../includes/header.php");
echo '<link rel="stylesheet" href="../assets/css/style.css">';
echo '<link rel="stylesheet" href="../assets/css/process_order.css">';
include("../includes/navbar.php");

$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

$emailError = "";
$phoneError = "";
$success = false;

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
        $success = true;
    }
}
?>
<main class="order-result-page">
    <div class="order-result-card">

        <?php if ($_SERVER["REQUEST_METHOD"] !== "POST"): ?>
            <h2>Invalid Request</h2>
            <p>Please return to the shop and submit the form again.</p>
            <a href="/PerfumeStore_20/pages/products.php" class="btn">Go Back</a>

        <?php elseif ($success): ?>
            <h2>Order completed successfully!</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="/PerfumeStore_20/pages/products.php" class="btn">Back to Shop</a>

        <?php else: ?>
            <h2>Validation Error</h2>

            <?php if ($emailError !== ""): ?>
                <p class="error"><?php echo htmlspecialchars($emailError, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <?php if ($phoneError !== ""): ?>
                <p class="error"><?php echo htmlspecialchars($phoneError, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <a href="/PerfumeStore_20/pages/products.php" class="btn">Go Back</a>
        <?php endif; ?>

    </div>
</main>
<?php include("../includes/footer.php"); ?>
