<?php
include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT message, rating, created_at
     FROM feedbacks
     WHERE user_id = ?
     ORDER BY created_at DESC"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$feedbacks = $stmt->get_result();

function renderProfileStars($rating)
{
    for ($i = 1; $i <= 5; $i++) {
        echo $i <= $rating ? "&#9733;" : "&#9734;";
    }
}

include("../includes/header.php");
echo '<link rel="stylesheet" href="../assets/css/style.css">';
include("../includes/navbar.php");
?>

<main class="profile-page">
    <section class="profile-panel">
        <h1>My Profile</h1>
        <p class="profile-username">
            Username: <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </p>

        <h2>My Feedback</h2>

        <?php if ($feedbacks->num_rows > 0): ?>
            <div class="profile-feedback-list">
                <?php while ($feedback = $feedbacks->fetch_assoc()): ?>
                    <article class="profile-feedback-card">
                        <p><?php echo htmlspecialchars($feedback["message"]); ?></p>
                        <div class="stars">
                            <?php renderProfileStars((int)$feedback["rating"]); ?>
                        </div>
                        <small><?php echo htmlspecialchars($feedback["created_at"]); ?></small>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>You have not submitted feedback yet.</p>
        <?php endif; ?>
    </section>
</main>

<?php
$stmt->close();
include("../includes/footer.php");
?>