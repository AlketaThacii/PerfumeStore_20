<?php
$newsletterEmail = trim($_POST['email'] ?? '');
$newsletterError = "";
$newsletterSuccess = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!preg_match("/^[\w\.-]+@[\w\.-]+\.\w+$/", $newsletterEmail)) {
        $newsletterError = "Please enter a valid email address.";
    } else {
        $newsletterSuccess = "Subscribed successfully!";
    }
}
include("../includes/header.php");
include("../includes/navbar.php");
?>

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/process_order.css">

<main class="order-result-page">
    <div class="order-result-card">

        <?php if ($_SERVER["REQUEST_METHOD"] !== "POST"): ?>
            <h2>Invalid Request</h2>
            <p>Please return and submit the form again.</p>
            <a href="/PerfumeStore_20/index.php" class="btn">Go Back</a>

        <?php elseif ($newsletterError !== ""): ?>
            <h2>Validation Error</h2>
            <p class="error">
                <?php echo htmlspecialchars($newsletterError, ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <a href="/PerfumeStore_20/index.php" class="btn">Go Back</a>

        <?php else: ?>
            <h2>Subscription Successful</h2>
            <p><?php echo htmlspecialchars($newsletterSuccess, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($newsletterEmail, ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="/PerfumeStore_20/index.php" class="btn">Back to Home</a>
        <?php endif; ?>

    </div>
</main>

<?php include("../includes/footer.php"); ?>
