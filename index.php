<?php
include("includes/header.php");
include("includes/navbar.php");

$products = [
    ["name" => "cqua di Gio", "price" => 110, "type" => "Men", "img" => "image4.webp"],
    ["name" => "Tom Ford Noir", "price" => 160, "type" => "Women", "img" => "image2.avif"],
    ["name" => "Bleu de Chanel", "price" => 140, "type" => "Men", "img" => "image12.jpg"],
    ["name" => "YSL Libre", "price" => 130, "type" => "Women", "img" => "image44.avif"],
    ["name" => "Versace Eros", "price" => 100, "type" => "Men", "img" => "image14.avif"],
    ["name" => "Gucci Bloom", "price" => 125, "type" => "Women", "img" => "image7.jpg"]
];

function formatPrice($price)
{
    return number_format($price, 2) . " €";
}

function getBadge($price)
{
    if ($price >= 150) return "Premium";
    if ($price < 110) return "Sale";
    return "";
}

function renderProductCard($p)
{
    $badge = getBadge($p['price']);
?>
    <div class="card">
        <img src="/PerfumeStore_20/assets/images/<?php echo $p['img']; ?>" alt="">
        <h3><?php echo $p['name']; ?></h3>
        <p class="type"><?php echo $p['type']; ?></p>
        <p class="price"><?php echo formatPrice($p['price']); ?></p>

        <?php if ($badge): ?>
            <span class="badge"><?php echo $badge; ?></span>
        <?php endif; ?>
    </div>
<?php
}

function renderSectionTitle($title)
{
    echo "<h2>$title</h2>";
}
$type = $_GET['type'] ?? "All";

$filtered = array_filter($products, function ($p) use ($type) {
    return $type === "All" || $p['type'] === $type;
});

$featured = array_slice($products, 0, 3);
?>

<main class="main1">
    <section class="hero">

        <video autoplay muted loop class="hero-video">
            <source src="assets/video/video1.mp4" type="video/mp4">
        </video>

        <div class="hero-content">
            <h1>The Art of Fragrance</h1>
            <p class="hero-subtitle">Inspired by elegance. Created with passion.</p>
            <p class="hero-desc">Each fragrance tells a story of character, depth, and modern luxury.</p>


            <div class="hero-buttons">
                <a href="pages/products.php" class="btn primary">Shop Now</a>
                <a href="pages/visitus.php" class="btn primary">Discover</a>
            </div>
        </div>

    </section>



    <section id="products">
        <?php renderSectionTitle("Top Picks"); ?>

        <div class="filter">
            <?php
            $types = ["All", "Men", "Women"];
            foreach ($types as $t) {
                echo "<a href='?type=$t'>$t</a>";
            }
            ?>
        </div>

        <div class="grid-index">
            <?php foreach ($filtered as $p) renderProductCard($p); ?>
        </div>
    </section>
    <?php
    $title = "CREATED WITH PURPOSE";

    $paragraphs = [
        "A refined luxury experience crafted with passion and elegance. Maison de Parfum is a contemporary fragrance house dedicated to refinement, authenticity, and the art of modern perfumery.",

    ];

    $images = [
        ["src" => "assets/images/perfume.webp", "class" => "img-top"],
        ["src" => "assets/images/img-12.jpg", "class" => "img-middle"],
        ["src" => "assets/images/perfumee.jpg", "class" => "img-bottom"]
    ];
    ?>

    <section class="about-home">
        <div class="about-home-container">

            <!-- LEFT -->
            <div class="about-home-text">
                <h2><?= $title ?></h2>

                <?php foreach ($paragraphs as $i => $p): ?>
                    <p class="<?= $i == 0 ? 'about-home-lead' : '' ?>">
                        <?= $p ?>
                    </p>
                <?php endforeach; ?>

                <p class="about-home-quote">
                    "Perfume is the art that makes memory speak."
                </p>

                <a href="pages/about.php" class="about-home-btn">Read More</a>
            </div>

            <!-- RIGHT -->
            <div class="about-home-images">
                <?php foreach ($images as $img): ?>
                    <img src="<?= $img['src']; ?>" class="about-home-img <?= $img['class']; ?>">
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <section class="why">
        <?php renderSectionTitle("Why Choose Us"); ?>

        <?php
        $whyItems = [
            ["title" => "Original Products", "desc" => "100% authentic perfumes."],
            ["title" => "Fast Delivery", "desc" => "Quick worldwide shipping."],
            ["title" => "Best Prices", "desc" => "Luxury at good prices."]
        ];
        ?>

        <div class="why-grid">
            <?php foreach ($whyItems as $item): ?>
                <div class="why-box">
                    <h3><?php echo $item['title']; ?></h3>
                    <p><?php echo $item['desc']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="testimonials">
        <?php renderSectionTitle("What Our Customers Say"); ?>

        <?php
        $reviews = [
            [
                "name" => "Shqipe Shala",
                "text" => "Fast delivery and quality products, value for money.",
                "img" => "person7.jpg",
                "rating" => 5
            ],
            [
                "name" => "Besnik Gashi",
                "text" => "Amazing perfumes! I will definitely shop again.",
                "img" => "person1.jpg",
                "rating" => 4
            ],
            [
                "name" => "Aferdita Berisha",
                "text" => "Best perfume store! Highly recommended.",
                "img" => "person9.jpg",
                "rating" => 5
            ]
        ];

        function renderStars($rating)
        {
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    echo "★";
                } else {
                    echo "☆";
                }
            }
        }
        ?>

        <div class="testimonials-grid">
            <?php foreach ($reviews as $r): ?>
                <div class="testimonial-card">

                    <img src="/PerfumeStore_20/assets/images/<?php echo $r['img']; ?>" alt="">

                    <h3><?php echo $r['name']; ?></h3>
                    <p class="review-text"><?php echo $r['text']; ?></p>

                    <div class="stars">
                        <?php renderStars($r['rating']); ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </section>
    <section class="newsletter">
        <?php renderSectionTitle("Join Our Newsletter"); ?>
        <p>Get updates about new perfumes</p>
    <form method="POST" action="pages/newsletter.php">
    <input 
        type="email" 
        name="email" 
        placeholder="Enter your email"
    >
    <button type="submit">Subscribe</button>
</form>
    </section>



</main>

<?php include("includes/footer.php"); ?>