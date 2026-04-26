<?php 
$userEmail = $_COOKIE['user_email'] ?? '';
include '../includes/header.php'; 
include '../includes/navbar.php'; 

$aboutTitle = "About Maison De Parfum";

 $aboutText = "Welcome to Maison De Parfum, your destination for luxury fragrances.
            We offer a curated collection of premium perfumes from the world's
            most prestigious brands. Our mission is to bring elegance, identity,
            and confidence through scent.";

$aboutCards = [
    [
        "title" => "Our Mission",
        "description" => "To provide high-quality perfumes that express personality, style, and sophistication."
    ],
    [
        "title" => "Why Choose Us",
        "description" => "Authentic products, fast delivery, and carefully selected fragrances for every occasion."
    ]
];
?>

<link rel="stylesheet" href="../assets/css/about.css">

<main>
    <section class="about">
        <div class="about-container">

            <h1><?php echo $aboutTitle; ?></h1>

      <?php if (!empty($userEmail)): ?>
    <p class="welcome-message">
        Welcome back, <?php echo htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8'); ?>.
    </p>
<?php endif; ?>

      <p><?php echo $aboutText; ?></p>

            <div class="about-grid">
                <?php foreach ($aboutCards as $card): ?>
                    <div class="about-card">
                        <h3><?php echo $card["title"]; ?></h3>
                        <p><?php echo $card["description"]; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="about-divider"></div>

            <div class="about-stats">
                <div class="about-stat-card">
                    <h2 class="counter" data-target="250">0</h2>
                    <p>Produkte të Disponueshme</p>
                </div>

                <div class="about-stat-card">
                    <h2 class="counter" data-target="300">0</h2>
                    <p>Klientë të Kënaqur</p>
                </div>

                <div class="about-stat-card">
                    <h2 class="counter" data-target="25">0</h2>
                    <p>Brende Luksoze</p>
                </div>
            </div>

        </div>
    </section>
</main>


<script src="../assets/js/about.js"></script>
<?php include '../includes/footer.php'; ?>