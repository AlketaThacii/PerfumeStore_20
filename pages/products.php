<?php
include("../includes/config.php");


$order = $_GET['sort'] ?? null;
$category = $_GET['category'] ?? "all";
$filteredProducts = filterProducts($products, $category);

if ($order) {
    $filteredProducts = sortProducts($filteredProducts, $order);
}

include("../includes/header.php"); 
echo '<link rel="stylesheet" href="../assets/css/style.css" >';
include("../includes/navbar.php");

echo '<main class="shop">';

    echo '<div class="filter" style="margin-bottom: 30px;">';
        echo '<strong>Category:</strong> ';
        echo "<a href='?category=all&sort=$order'>All</a> | ";
        echo "<a href='?category=men&sort=$order'>Men</a> | ";
        echo "<a href='?category=women&sort=$order'>Women</a> | ";
        echo "<a href='?category=unisex&sort=$order'>Unisex</a>";
        echo '<br><br>';
        echo '<strong>Sort Price:</strong> ';
        echo " <a href='?category=$category&sort=asc'>Price ↑</a> | ";
        echo " <a href='?category=$category&sort=desc'>Price ↓</a>";
    echo '</div>';

    echo '<div class="grid">';
        
        if (!empty($filteredProducts)) {
            foreach ($filteredProducts as $product) {
                $name = htmlspecialchars($product->getName());
                $rawPrice = (float)$product->getPrice(); 
                $price = number_format($rawPrice, 2);
                $cat = strtoupper($product->getCategory());
                $isPremium = ($rawPrice > 150);
                
                
                $cleanId = str_replace([' ', "'"], '', $name);

                echo '<div class="card">';
                    if ($isPremium) {
                        echo '<span class="badge">PREMIUM</span>';
                    }

                    echo "<h3>$name</h3>";
                    echo "<p class='price'>$$price</p>";
                    echo "<p style='font-size: 0.8rem; opacity: 0.6; margin-top: 5px;'>$cat</p>";

                    echo '<div class="qty-control">'; 
                        echo '<button type="button" onclick="changeQty(\''.$cleanId.'\', -1, '.$rawPrice.')">-</button>';
                        echo '<span id="qty-'.$cleanId.'">0</span>'; 
                        echo '<button type="button" onclick="changeQty(\''.$cleanId.'\', 1, '.$rawPrice.')">+</button>';
                    echo '</div>';

                    
                echo '</div>';
            }
        } else {
            echo '<p style="grid-column: 1 / -1;">No products found.</p>';
        }

        echo '<aside class="order-sidebar">';
            echo '<div class="order-card">';
                echo '<h2>Order Online</h2>';
                
                
                echo '<div style="margin: 15px 0; font-weight: bold; color: #d4af37; font-size: 1.2rem;">';
                    echo 'Total: $<span id="grand-total">0.00</span>';
                echo '</div>';

                echo '<form action="process_order.php" method="POST">';
                    echo '<div class="input-group">';
                        echo '<label>Email Address</label>';
                        echo '<input type="email" name="email" placeholder="email@example.com" required>';
                    echo '</div>';
                    
                    echo '<div class="input-group">';
                        echo '<label>Phone Number</label>';
                        echo '<input type="tel" name="phone" placeholder="06XXXXXXXX" required>';
                    echo '</div>';
                    
                    echo '<div class="input-group">';
                        echo '<label>Delivery Address</label>';
                        echo '<textarea name="address" placeholder="Street, City, Zip Code" required></textarea>';
                    echo '</div>';
                    
                    echo '<hr style="border: 0.5px solid #444; margin: 15px 0;">';
                    echo '<button type="submit" class="submit-order-btn">Complete Purchase</button>';
                echo '</form>';
            echo '</div>';
        echo '</aside>';
    echo '</div>'; 
echo '</main>';

include("../includes/footer.php");
?>

<script>

function changeQty(id, delta, price) {
    let qtyElement = document.getElementById('qty-' + id);
    let totalElement = document.getElementById('grand-total');
    
    let currentQty = parseInt(qtyElement.innerText);
    let newQty = currentQty + delta;
    
    if (newQty >= 0) {
        qtyElement.innerText = newQty;
        
        
        let currentTotal = parseFloat(totalElement.innerText);
        let newTotal = currentTotal + (delta * price);
        
        
        totalElement.innerText = Math.max(0, newTotal).toFixed(2);
    }
}
</script>