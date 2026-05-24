<?php
session_start();
include("includes/header.php");
include("includes/navbar.php");

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total = 0;
?>
a
<main class="cart-page" style="padding: 50px 10%; min-height: 60vh;">
    <h2>Shporta Juaj</h2>

    <!-- Këtu u shtua një ID për të kontrolluar të gjithë përmbajtjen me AJAX -->
    <div id="cart-container-wrapper">
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Shporta juaj është e zbrazët. <a href="index.php">Kthehu te produktet</a></p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background: #f4f4f4; text-align: left;">
                        <th style="padding: 10px;">Produkti</th>
                        <th style="padding: 10px;">Çmimi</th>
                        <th style="padding: 10px;">Sasia</th>
                        <th style="padding: 10px;">Subtotali</th>
                        <th style="padding: 10px;">Veprimi</th>
                    </tr>
                </thead>
                <tbody>
                
                    <?php foreach ($_SESSION['cart'] as $key => $item): 
                        $subtotal = $item['price'] * $item['qty'];
                        $total += $subtotal;
                    ?>
                        <!-- Shtohet një ID unike për çdo rresht që ta fshijmë live me JS -->
                        <tr id="row-<?php echo md5($key); ?>" style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 15px 10px; display: flex; align-items: center; gap: 15px;">
                                <img src="/PerfumeStore_20/<?php echo htmlspecialchars($item['img']); ?>" width="50" alt="">
                                <span><?php echo htmlspecialchars($item['name']); ?></span>
                            </td>
                            <td style="padding: 10px;"><?php echo number_format($item['price'], 2); ?> €</td>
                            <td style="padding: 10px;"><?php echo $item['qty']; ?></td>
                            <td style="padding: 10px;"><?php echo number_format($subtotal, 2); ?> €</td>
                            <td style="padding: 10px;">
                                <!-- Ndryshohet linku në një buton AJAX me klasë dhe data-attribute -->
                                <button class="remove-from-cart-btn" 
                                        data-name="<?php echo htmlspecialchars($item['name']); ?>" 
                                        data-rowid="row-<?php echo md5($key); ?>"
                                        style="color: red; background: none; border: none; font-weight: bold; cursor: pointer; padding: 0;">
                                    Remove
                                </button>
                            </td>
                        </tr>
                        
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: right; margin-top: 30px;">
                <!-- Shtohet një ID te totali që ta përditësojmë vlerën live -->
                <h3>Total: <span id="cart-total-value" style="color: green;"><?php echo number_format($total, 2); ?> €</span></h3>
                <br>
                <a href="checkout.php" class="btn" style="background: black; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; font-weight: bold;">Vazhdo te Pagesa</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- KODI AJAX PËR FSHIRJEN PA REFRESH -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productName = this.getAttribute('data-name');
            const rowId = this.getAttribute('data-rowid');

            // Thërrasim skedarin e backend-it në mënyrë asinkrone
            fetch('includes/manage-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', name: productName })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // 1. Largojmë rreshtin nga tabela menjëherë pa bërë refresh
                    document.getElementById(rowId).remove();
                    
                    // 2. Përditësojmë totalin e ri në ekran
                    document.getElementById('cart-total-value').innerText = data.total + ' €';

                    // 3. Nëse shporta mbetet krejtësisht e zbrazët, shfaqim mesazhin dinamik
                    if(data.cart_empty === true) {
                        document.getElementById('cart-container-wrapper').innerHTML = '<p>Shporta juaj është e zbrazët. <a href="index.php">Kthehu te produktet</a></p>';
                    }
                } else {
                    alert('Gabim gjatë fshirjes: ' + data.message);
                }
            })
            .catch(err => console.error('Error:', err));
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>