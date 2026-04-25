<?php
include("includes/header.php");
include("includes/navbar.php");

$products = [
    ["name" => "Dior Sauvage", "price" => 120, "type" => "Men", "img" => "dior.jpg"],
    ["name" => "Tom Ford Noir", "price" => 160, "type" => "Women", "img" => "chanel.jpg"],
    ["name" => "Bleu de Chanel", "price" => 140, "type" => "Men", "img" => "armani.jpg"],
    ["name" => "YSL Libre", "price" => 130, "type" => "Women", "img" => "ysl.jpg"],
    ["name" => "Versace Eros", "price" => 100, "type" => "Men", "img" => "versace.jpg"],
    ["name" => "Gucci Bloom", "price" => 125, "type" => "Women", "img" => "gucci.jpg"]
];

function formatPrice($price)
{
    return number_format($price, 2) . " €";
}

function getBadge($price)
{
    if ($price >= 150) return "Premium";
    if ($price <= 115) return "Sale";
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

<main class="shop">
    <section class="hero">
        <h1>Maison De Parfum</h1>
        <p>Discover luxury fragrances for every personality</p>
        <div class="hero-buttons">
    <a href="pages/products.php" class="btn primary">Shop Now</a>
    <a href="pages/visitus.php" class="btn primary">Discover</a>
</div>
    </section>

    <section class="featured">
        <?php renderSectionTitle("Featured Products"); ?>
        <div class="grid">
            <?php foreach ($featured as $p) renderProductCard($p, false); ?>
        </div>
    </section>

    <section id="products">
        <?php renderSectionTitle("Our Collection"); ?>

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
                "img" => "user1.jpg",
                "rating" => 5
            ],
            [
                "name" => "Besnik Gashi",
                "text" => "Amazing perfumes! I will definitely shop again.",
                "img" => "user2.jpg",
                "rating" => 4
            ],
            [
                "name" => "Aferdita Berisha",
                "text" => "Best perfume store! Highly recommended.",
                "img" => "user3.jpg",
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

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email">
            <button type="submit">Subscribe</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST['email'] ?? '';
            if ($email) {
                echo "<p style='color:green;'>Subscribed successfully!</p>";
            }
        }
        ?>
    </section>
    <section class="about">
        <?php renderSectionTitle("About Us"); ?>
        <p>
            Maison De Parfum is your destination for premium fragrances.
        </p>
    </section>



</main>

<?php include("includes/footer.php"); ?>