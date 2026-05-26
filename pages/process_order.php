<?php
session_start();
include("../includes/config.php");
require_once "../includes/db.php";

$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

$emailError = "";
$phoneError = "";
$cartError  = "";
$success    = false;

$cart         = $_SESSION["cart"] ?? [];
$total        = 0;
$orderedItems = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!preg_match("/^[\w\.-]+@[\w\.-]+\.\w+$/", $email)) {
        $emailError = "Email is not valid.";
    }

    if (!preg_match("/^[0-9]{9,12}$/", $phone)) {
        $phoneError = "Phone number must contain 9 to 12 digits.";
    }

    if (empty($cart)) {
        $cartError = "Your cart is empty.";
    }

    foreach ($cart as $productId => $qty) {
        foreach ($products as $product) {
            if ($product->getId() == $productId) {
                $subtotal = $product->getPrice() * $qty;
                $total   += $subtotal;

                $orderedItems[] = [
                    "name"     => $product->getName(),
                    "price"    => $product->getPrice(),
                    "qty"      => $qty,
                    "subtotal" => $subtotal
                ];
            }
        }
    }

    if ($emailError === "" && $phoneError === "" && $cartError === "") {

        // Ruaj ne databaze
        $userId     = $_SESSION["user_id"] ?? null;
        $itemsJson  = json_encode($orderedItems);

        $stmt = $conn->prepare(
            "INSERT INTO orders (user_id, email, phone, address, total, items, status)
             VALUES (?, ?, ?, ?, ?, ?, 'pending')"
        );
        $stmt->bind_param("isssds", $userId, $email, $phone, $address, $total, $itemsJson);

        if ($stmt->execute()) {
            // Pastro cart-in
            unset($_SESSION["cart"]);
            setcookie("user_email", $email, time() + (86400 * 7), "/");
            $success = true;
        } else {
            $cartError = "Something went wrong. Please try again.";
        }
    }
}

include("../includes/header.php");
echo '<link rel="stylesheet" href="../assets/css/style.css">';
echo '<link rel="stylesheet" href="../assets/css/process_order.css">';
include("../includes/navbar.php");
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

            <h3>Ordered Products</h3>

            <?php foreach ($orderedItems as $item): ?>
                <p>
                    <?php echo htmlspecialchars($item["name"], ENT_QUOTES, 'UTF-8'); ?>
                    -
                    <?php echo (int)$item["qty"]; ?> x
                    $<?php echo number_format($item["price"], 2); ?>
                    =
                    $<?php echo number_format($item["subtotal"], 2); ?>
                </p>
            <?php endforeach; ?>

            <h3>Total: $<?php echo number_format($total, 2); ?></h3>

            <a href="/PerfumeStore_20/pages/products.php" class="btn">Back to Shop</a>

        <?php else: ?>
            <h2>Validation Error</h2>

            <?php if ($emailError !== ""): ?>
                <p class="error"><?php echo htmlspecialchars($emailError, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <?php if ($phoneError !== ""): ?>
                <p class="error"><?php echo htmlspecialchars($phoneError, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <?php if ($cartError !== ""): ?>
                <p class="error"><?php echo htmlspecialchars($cartError, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <a href="/PerfumeStore_20/pages/products.php" class="btn">Go Back</a>
        <?php endif; ?>

    </div>
</main>

<?php include("../includes/footer.php"); ?>