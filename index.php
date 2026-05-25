<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/db.php");

echo '<link rel="stylesheet" href="/PERFUMESTORE_20/assets/css/style.css">';

$query = mysqli_query(
    $conn,
    "SELECT
        products.id,
        products.name,
        products.price,
        products.image,
        categories.name AS category
     FROM products
     LEFT JOIN categories
     ON products.category_id = categories.id
     ORDER BY RAND()"
);
$products = mysqli_fetch_all(
    $query,
    MYSQLI_ASSOC
);

function renderTopPicksSection($products, $type = "all")
{
    $filtered = array_filter($products, function ($p) use ($type) {
        return $type === "all" ||
            strtolower($p["category"] ?? "") === strtolower($type);
    });

    shuffle($filtered);
    $filtered = array_slice($filtered, 0, 6);
?>
    <section id="products">
        <h2>Top Picks</h2>

        <div class="filter" id="top-picks-filter">
            <?php
            $types = ["All", "Men", "Women", "Unisex"];

            foreach ($types as $t) {
                echo "<button type='button' class='top-filter-btn' data-type='$t'>$t</button> ";
            }
            ?>
        </div>

        <div class="grid-index" id="top-picks-list">
            <?php foreach ($filtered as $p) renderProductCard($p); ?>
        </div>
    </section>
<?php
}
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
        <img src="<?php echo str_replace('../', '', htmlspecialchars($p['image'])); ?>" alt="">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <p class="type"><?php echo htmlspecialchars(ucfirst($p['category'] ?? 'Unknown')); ?></p>
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
$type = strtolower(
    $_GET['type'] ?? "all"
);

$filtered = array_filter(
    $products,
    function ($p) use ($type) {
        return
            $type === "all" ||
            strtolower(
                $p["category"] ?? ""
            ) === $type;
    }
);
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


    <?php renderTopPicksSection($products, $type); ?>
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
        $query = mysqli_query(
            $conn,
            "SELECT
        users.username,
        feedbacks.message,
        feedbacks.rating
     FROM feedbacks
     INNER JOIN users
     ON feedbacks.user_id = users.id
     ORDER BY feedbacks.created_at DESC
     LIMIT 3"
        );

        $reviews = mysqli_fetch_all(
            $query,
            MYSQLI_ASSOC
        );

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

                    <h3>
                        <?php
                        echo htmlspecialchars(
                            $r['username']
                        );
                        ?>
                    </h3>

                    <p class="review-text">
                        <?php
                        echo htmlspecialchars(
                            $r['message']
                        );
                        ?>
                    </p>

                    <div class="stars">
                        <?php
                        renderStars(
                            $r['rating']
                        );
                        ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </section>
    <?php if (!isset($_SESSION["role"]) || $_SESSION["role"] == "user"): ?>
        <section class="newsletter">
            <?php renderSectionTitle("Join Our Newsletter"); ?>
            <p>Get updates about new perfumes</p>
            <form method="POST" action="pages/newsletter.php">
                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email">
                <button type="submit">Subscribe</button>
            </form>
        </section>
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "user"): ?>
        <section class="feedback-section" id="feedback-section">
            <h2>Give Feedback</h2>

            <form action="pages/save-feedback.php" method="POST" class="feedback-form">
                <textarea name="feedback" placeholder="Write your feedback..." required></textarea>

                <select name="rating" required>
                    <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
                    <option value="4">&#9733;&#9733;&#9733;&#9733;</option>
                    <option value="3">&#9733;&#9733;&#9733;</option>
                    <option value="2">&#9733;&#9733;</option>
                    <option value="1">&#9733;</option>
                </select>

                <button type="submit">Submit Feedback</button>
            </form>
        </section>
        <?php endif; ?> 
   <?php endif; ?> 


</main>
<script>
    document.querySelectorAll(".top-filter-btn").forEach(button => {
        button.addEventListener("click", function() {
            let type = this.dataset.type;

            fetch("ajax/filter_top_picks.php?type=" + encodeURIComponent(type))
                .then(response => response.text())
                .then(data => {
                    document.getElementById("top-picks-list").innerHTML = data;
                });
        });
    });
</script>

<?php include("includes/footer.php"); ?>