<?php

include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: products.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, category_id, name, price, image
     FROM products
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$product =
    $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$product) {
    header("Location: products.php");
    exit;
}

$message = "";

$categories = mysqli_query(
    $conn,
    "SELECT id, name
     FROM categories
     ORDER BY name ASC"
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name =
        trim($_POST["name"] ?? "");

    $price =
        (float)($_POST["price"] ?? 0);

    $categoryId =
        (int)($_POST["category_id"] ?? 0);
    $imagePath =
        $product["image"];

    if (!empty($_FILES["image"]["name"])) {

        $allowedExtensions = [
            "jpg",
            "jpeg",
            "png",
            "webp",
            "avif"
        ];

        $extension = strtolower(
            pathinfo(
                $_FILES["image"]["name"],
                PATHINFO_EXTENSION
            )
        );

        if (
            in_array(
                $extension,
                $allowedExtensions,
                true
            )
        ) {
            $safeName =
                uniqid(
                    "product_",
                    true
                ) . "." . $extension;
            $target =
                "../assets/images/" .
                $safeName;
            if (
                move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    $target
                )
            ) {

                $imagePath =
                    $target;
            }
        }
    }

    if (
        $name !== "" &&
        $price >= 0 &&
        $categoryId > 0
    ) {
        $update =
            $conn->prepare(
                "UPDATE products
         SET category_id = ?,
         name = ?,
         price = ?,
         image = ?
         WHERE id = ?"
            );

        $update->bind_param(
            "isdsi",
            $categoryId,
            $name,
            $price,
            $imagePath,
            $id
        );
        if ($update->execute()) {

            $update->close();

            header("Location: products.php");
            exit;
        }

        $message =
            "Product could not be updated.";
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

        <h1>Edit Product</h1>

        <form method="POST"
            enctype="multipart/form-data"
            class="product-form">
            <label>
                Product name
                <input
                    type="text"
                    name="name"
                    value="<?php echo htmlspecialchars($product["name"]); ?>"
                    required>
            </label>

            <label>
                Price
                <input
                    type="number"
                    name="price"
                    step="0.01"
                    min="0"
                    value="<?php echo htmlspecialchars($product["price"]); ?>"
                    required>
            </label>
            <label>
                Category
                <select
                    name="category_id"
                    required>

                    <?php while ($category = mysqli_fetch_assoc($categories)): ?>

                        <option
                            value="<?php echo (int)$category["id"]; ?>"

                            <?php echo ((int)$product["category_id"] === (int)$category["id"]) ? "selected" : ""; ?>>

                            <?php echo htmlspecialchars($category["name"]); ?>

                        </option>

                    <?php endwhile; ?>

                </select>
            </label>
            <label>
                Product image

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp,.avif">
            </label>

            <?php if (!empty($product["image"])): ?>

                <img
                    class="form-preview"
                    src="<?php echo htmlspecialchars($product["image"]); ?>"
                    alt="<?php echo htmlspecialchars($product["name"]); ?>">

            <?php endif; ?>

            <button type="submit">
                Save Changes
            </button>

        </form>

    </section>
</main>

<?php include("../includes/footer.php"); ?>