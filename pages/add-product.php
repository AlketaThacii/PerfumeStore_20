<?php

include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$message = "";

$categories = mysqli_query(
    $conn,
    "SELECT id, name FROM categories ORDER BY name ASC"
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $price = (float)($_POST["price"] ?? 0);
    $categoryId = (int)($_POST["category_id"] ?? 0);

    if ($name !== "" && $price > 0 && $categoryId > 0) {

        $stmt = $conn->prepare(
            "INSERT INTO products
            (category_id, name, price)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "isd",
            $categoryId,
            $name,
            $price
        );

        if ($stmt->execute()) {

            header("Location: products.php");
            exit;
        }

        $message =
            "Product could not be added.";

        $stmt->close();

    } else {

        $message =
            "Please fill all required fields.";
    }
}

include("../includes/header.php");
include("../includes/navbar.php");
?>

<main class="product-admin-page">
    <section class="product-form-panel">

        <h1>Add Product</h1>

        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">

            <label>
                Product name
                <input
                    type="text"
                    name="name"
                    required>
            </label>

            <label>
                Price
                <input
                    type="number"
                    name="price"
                    step="0.01"
                    min="0"
                    required>
            </label>

            <label>
                Category
                <select
                    name="category_id"
                    required>

                    <option value="">
                        Choose category
                    </option>

                    <?php while ($category = mysqli_fetch_assoc($categories)): ?>

                        <option value="<?php echo (int)$category["id"]; ?>">
                            <?php echo htmlspecialchars($category["name"]); ?>
                        </option>

                    <?php endwhile; ?>

                </select>
            </label>

            <button type="submit">
                Add Product
            </button>

        </form>

    </section>
</main>

<?php include("../includes/footer.php"); ?>