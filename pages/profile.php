<?php
include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    header("Location: /PerfumeStore_20/index.php");
    exit;
}

$userId  = (int)$_SESSION["user_id"];
$errors  = [];
$success = "";

// Merr te dhenat e userit
$stmt = $conn->prepare("SELECT username, email, profile_image FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// ── UPLOAD FOTO ───────────────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["upload_image"])) {
    if (!empty($_FILES["profile_image"]["name"])) {
        $ext     = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($ext, $allowed)) {
            $errors[] = "File type not allowed. Allowed: jpg, jpeg, png, webp.";
        } else {
            // Fshi foton e vjetër nëse ekziston
            if (!empty($user["profile_image"])) {
                $oldFile = "../assets/images/" . $user["profile_image"];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $safeName = uniqid("profile_", true) . "." . $ext;
            $target   = "../assets/images/" . $safeName;

            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target)) {
                $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                $stmt->bind_param("si", $safeName, $userId);
                $stmt->execute();
                $user["profile_image"] = $safeName;
                $success = "Profile photo updated successfully.";
            } else {
                $errors[] = "Upload failed. Please try again.";
            }
        }
    } else {
        $errors[] = "No file selected.";
    }
}

// ── FSHI FOTON ────────────────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_image"])) {
    if (!empty($user["profile_image"])) {
        $oldFile = "../assets/images/" . $user["profile_image"];
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
        $stmt = $conn->prepare("UPDATE users SET profile_image = NULL WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $user["profile_image"] = null;
        $success = "Profile photo deleted successfully.";
    }
}

// ── NDRYSHO PASSWORDIN ────────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["change_password"])) {
    $currentPassword = $_POST["current_password"] ?? "";
    $newPassword     = $_POST["new_password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!password_verify($currentPassword, $row["password"])) {
        $errors[] = "Current password is incorrect.";
    } elseif (strlen($newPassword) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    } elseif (!preg_match('/[A-Z]/', $newPassword)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[0-9]/', $newPassword)) {
        $errors[] = "Password must contain at least one number.";
    } elseif (!preg_match('/[\W_]/', $newPassword)) {
        $errors[] = "Password must contain at least one special character (@, !, # etc.).";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "New passwords do not match.";
    } else {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $userId);
        $stmt->execute();
        $success = "Password changed successfully.";
    }
}

// ── FSHI FEEDBACK-UN ─────────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_feedback"])) {
    $feedbackId = (int)$_POST["feedback_id"];
    $stmt = $conn->prepare("DELETE FROM feedbacks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $feedbackId, $userId);
    $stmt->execute();
    header("Location: profile.php");
    exit;
}

// ── MERR FEEDBACKET ───────────────────────────────────────────────────────────
$stmt = $conn->prepare(
    "SELECT id, message, rating, created_at FROM feedbacks WHERE user_id = ? ORDER BY created_at DESC"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$feedbacks = $stmt->get_result();

// ── MERR HISTORINE E POROSIVE ─────────────────────────────────────────────────
$orderStmt = $conn->prepare(
    "SELECT id, email, phone, address, total, items, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC"
);
$orderStmt->bind_param("i", $userId);
$orderStmt->execute();
$orders = $orderStmt->get_result()->fetch_all(MYSQLI_ASSOC);

function renderProfileStars($rating) {
    for ($i = 1; $i <= 5; $i++) {
        echo $i <= $rating ? "&#9733;" : "&#9734;";
    }
}

include("../includes/header.php");
echo '<link rel="stylesheet" href="../assets/css/style.css">';
echo '<link rel="stylesheet" href="../assets/css/profile.css">';
include("../includes/navbar.php");
?>

<main class="profile-page">
    <div class="profile-grid">

        <!-- Foto profili + informacionet -->
        <div class="profile-card">
            <h2>My Account</h2>

            <?php if ($success !== ""): ?>
                <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php foreach ($errors as $error): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>

            <!-- Foto aktuale -->
            <div style="text-align:center; margin-bottom:16px;">
                <?php
                $hasPhoto = !empty($user["profile_image"]);
                $imgSrc   = $hasPhoto
                    ? "../assets/images/" . htmlspecialchars($user["profile_image"])
                    : "../assets/images/default-profile.png";
                ?>
                <img src="<?php echo $imgSrc; ?>"
                     alt="Profile Photo"
                     style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:2px solid #d4af37; display:block; margin:0 auto 12px;">

                <!-- Butoni FSHI foton — shfaqet vetëm nëse ka foto -->
                <?php if ($hasPhoto): ?>
                    <form method="POST" style="display:inline;"
                          onsubmit="return confirm('Are you sure you want to delete your photo?')">
                        <input type="hidden" name="delete_image" value="1">
                        <button type="submit"
                                style="background:transparent; border:1px solid #e74c3c; color:#e74c3c;
                                       padding:5px 14px; border-radius:6px; cursor:pointer; font-size:0.85rem;">
                            ✕ Delete photo
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Forma NDRYSHO / SHTO foton -->
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="upload_image" value="1">
                <input type="file"
                       name="profile_image"
                       accept=".jpg,.jpeg,.png,.webp"
                       class="profile-input"
                       required>
                <button type="submit" class="profile-btn">
                    <?php echo $hasPhoto ? "Change photo" : "Add photo"; ?>
                </button>
            </form>

            <br>

            <div class="profile-info-row">
                <span class="profile-info-label">Username</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user["username"]); ?></span>
            </div>

            <div class="profile-info-row">
                <span class="profile-info-label">Email</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user["email"]); ?></span>
            </div>
        </div>

        <!-- Ndrysho passwordin -->
        <div class="profile-card">
            <h2>Change Password</h2>

            <form method="POST">
                <input type="hidden" name="change_password" value="1">
                <div class="password-box">
                    <input type="password" id="currentPw" name="current_password"
                           class="profile-input" placeholder="Current Password" required>
                    <span class="toggle-password" onclick="togglePassword('currentPw', this)">Show</span>
                </div>
                <div class="password-box">
                    <input type="password" id="newPw" name="new_password"
                           class="profile-input" placeholder="New Password (min. 8 characters)" required>
                    <span class="toggle-password" onclick="togglePassword('newPw', this)">Show</span>
                </div>
                <div class="password-box">
                    <input type="password" id="confirmPw" name="confirm_password"
                           class="profile-input" placeholder="Confirm New Password" required>
                    <span class="toggle-password" onclick="togglePassword('confirmPw', this)">Show</span>
                </div>
                <button type="submit" class="profile-btn">Update Password</button>
            </form>
        </div>

        <!-- Historia e porosive -->
        <div class="profile-card profile-full-width">
            <h2>My Orders</h2>

            <?php if (empty($orders)): ?>
                <p style="color:#777;">You have no orders yet.</p>
            <?php else: ?>
                <?php foreach ($orders as $order):
                    $items = json_decode($order["items"], true);
                ?>
                    <div class="order-card">
                        <div class="order-card-header">
                            <span class="order-id">Order #<?php echo $order["id"]; ?></span>
                            <span class="order-date"><?php echo date("d M Y, H:i", strtotime($order["created_at"])); ?></span>
                            <span class="order-status status-<?php echo $order["status"]; ?>">
                                <?php echo ucfirst($order["status"]); ?>
                            </span>
                        </div>
                        <div class="order-items">
                            <?php foreach ($items as $item): ?>
                                <?php echo htmlspecialchars($item["name"]); ?> x<?php echo (int)$item["qty"]; ?> —
                                $<?php echo number_format($item["subtotal"], 2); ?><br>
                            <?php endforeach; ?>
                        </div>
                        <div class="order-total">Total: $<?php echo number_format($order["total"], 2); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Feedbacket -->
        <div class="profile-card profile-full-width">
            <h2>My Feedback</h2>

            <?php if ($feedbacks->num_rows > 0): ?>
                <?php while ($feedback = $feedbacks->fetch_assoc()): ?>
                    <div class="profile-feedback-card">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_feedback" value="1">
                            <input type="hidden" name="feedback_id" value="<?php echo $feedback["id"]; ?>">
                            <button type="submit" class="delete-feedback-btn"
                                onclick="return confirm('Delete this feedback?')">✕ Delete</button>
                        </form>
                        <p><?php echo htmlspecialchars($feedback["message"]); ?></p>
                        <div class="stars"><?php renderProfileStars((int)$feedback["rating"]); ?></div>
                        <small><?php echo htmlspecialchars($feedback["created_at"]); ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#777;">You have not submitted any feedback yet.</p>
            <?php endif; ?>
        </div>

    </div>
</main>

<script>
function togglePassword(inputId, icon) {
    let input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = "Hide";
    } else {
        input.type = "password";
        icon.innerHTML = "Show";
    }
}
</script>

<?php include("../includes/footer.php"); ?>