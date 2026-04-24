<?php
include("includes/header.php");
include("includes/navbar.php");

$products = [
    ["name"=>"Dior Sauvage","price"=>120,"type"=>"Men","img"=>"dior.jpg"],
    ["name"=>"Tom Ford Noir","price"=>160,"type"=>"Women","img"=>"chanel.jpg"],
    ["name"=>"Bleu de Chanel","price"=>140,"type"=>"Men","img"=>"armani.jpg"],
    ["name"=>"YSL Libre","price"=>130,"type"=>"Women","img"=>"ysl.jpg"],
    ["name"=>"Versace Eros","price"=>100,"type"=>"Men","img"=>"versace.jpg"],
    ["name"=>"Gucci Bloom","price"=>125,"type"=>"Women","img"=>"gucci.jpg"]
];

function formatPrice($price){
    return number_format($price, 2) . " €";
}

function getBadge($price){
    if($price >= 150) return "Premium";
    if($price <= 115) return "Sale";
    return "";
}

$type = $_GET['type'] ?? "All";

$filtered = array_filter($products, function($p) use ($type){
    return $type === "All" || $p['type'] === $type;
});
?>

<main class="shop">

    <h1>Maison De Parfum</h1>
    <p class="subtitle">Luxury fragrances for every style</p>

    <div class="filter">
        <a href="?type=All">All</a>
        <a href="?type=Men">Men</a>
        <a href="?type=Women">Women</a>
    </div>
    
    <div class="grid">

        <?php foreach($filtered as $p): ?>
            <div class="card">

                <img src="/PerfumeStore_20/assets/images/<?php echo $p['img']; ?>" alt="">

                <h3><?php echo $p['name']; ?></h3>
                <p class="type"><?php echo $p['type']; ?></p>
                <p class="price"><?php echo formatPrice($p['price']); ?></p>

                <?php $badge = getBadge($p['price']); ?>
                <?php if($badge): ?>
                    <span class="badge"><?php echo $badge; ?></span>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    </div>

</main>

<?php include("includes/footer.php"); ?>