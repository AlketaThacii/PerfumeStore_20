<?php
include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

// Admini nuk ka profil — ridrejto
if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    header("Location: /PerfumeStore_20/index.php");
    exit;
}

$userId   = (int)$_SESSION["user_id"];
$errors   = [];
$success  = "";

// Merr te dhenat e userit
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Ndrysho passwordin
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

// Fshi feedback-un
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_feedback"])) {
    $feedbackId = (int)$_POST["feedback_id"];
    $stmt = $conn->prepare("DELETE FROM feedbacks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $feedbackId, $userId);
    $stmt->execute();
    header("Location: profile.php");
    exit;
}

// Merr feedbacket
$stmt = $conn->prepare(
    "SELECT id, message, rating, created_at FROM feedbacks WHERE user_id = ? ORDER BY created_at DESC"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$feedbacks = $stmt->get_result();

// Merr historine e porosive
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
echo '<link rel="stylesheet" href="/PERFUMESTORE_20/assets/css/profile.css">';
include("../includes/navbar.php");
?>



<main class="profile-page">
    <div class="profile-grid">

        <!-- Informacionet e llogarise -->
        <div class="profile-card">
            <h2>My Account</h2>

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

            <?php if ($success !== ""): ?>
                <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php foreach ($errors as $error): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>

            <form method="POST">
                <input type="hidden" name="change_password" value="1">
                <div class="password-box">
                    <input type="password" id="currentPw" name="current_password" class="profile-input" placeholder="Current Password" required>
                    <span class="toggle-password" onclick="togglePassword('currentPw', this)">Show</span>
                </div>
                <div class="password-box">
                    <input type="password" id="newPw" name="new_password" class="profile-input" placeholder="New Password (min. 8 characters)" required>
                    <span class="toggle-password" onclick="togglePassword('newPw', this)">Show</span>
                </div>
                <div class="password-box">
                    <input type="password" id="confirmPw" name="confirm_password" class="profile-input" placeholder="Confirm New Password" required>
                    <span class="toggle-password" onclick="togglePassword('confirmPw', this)">Show</span>
                </div>
                <button type="submit" class="profile-btn">Update Password</button>
            </form>
        </div>

        <!-- Historia e porosive -->
        <div class="profile-card profile-full-width">
            <h2>My Orders</h2>

            <?php if (empty($orders)): ?>
                <p style="color: #777;">You have no orders yet.</p>
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
                <p style="color: #777;">You have not submitted any feedback yet.</p>
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