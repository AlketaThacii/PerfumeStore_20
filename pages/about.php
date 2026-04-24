<?php 
include '../includes/header.php'; 
include '../includes/navbar.php'; 

$aboutTitle = "About Maison De Parfum";

$aboutText = "  Welcome to Maison De Parfum, your destination for luxury fragrances.
            We offer a curated collection of premium perfumes from the world's
            most prestigious brands. Our mission is to bring elegance, identity,
            and confidence through scent.";

$aboutCards = [
    [
        "title" => "Our Mission",
        "description" => "To provide high-quality perfumes that express personality, style, and sophistication."
    ],
    [
        "title" => "Our Vision",
        "description" => "To become a leading online perfume store known for luxury, authenticity, and customer satisfaction."
    ],
    [
        "title" => "Why Choose Us",
        "description" => "Authentic products, fast delivery, and carefully selected fragrances for every occasion."
    ]
];
?>
<main>
<section class="about">
    <div class="about-container">

      <h1><?php echo $aboutTitle; ?></h1>
      <p><?php echo $aboutText; ?></p>

        <div class="about-grid">
            <?php foreach ($aboutCards as $card): ?>

            <div class="about-card">
             <h3><?php echo $card["title"]; ?></h3>
             <p><?php echo $card["description"]; ?></p>
            </div>
            <?php endforeach; ?>

     </div>
 </div>
</section>
</main>

<?php include '../includes/footer.php'; ?>