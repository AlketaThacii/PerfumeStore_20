<?php
session_start();
include("includes/header.php");
include("includes/navbar.php");
 



if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $product_name = $_GET['name'];
    if (isset($_SESSION['cart'][$product_name])) {
        unset($_SESSION['cart'][$product_name]);
    }
    header("Location: cart.php");
    exit;
}

$total = 0;
?>

<main class="cart-page" style="padding: 50px 10%; min-height: 60vh;">
    <h2>Shporta Juaj</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Shporta juaj është e zbrazët. <a href="index.php">Kthehu te produktet</a></p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: #f4f4f4; text-align: left;">
                    <th style="padding: 10px;">Produkti</th>
                    <th>Çmimi</th>
                    <th>Sasia</th>
                    <th>Subtotali</th>
                    <th>Veprimi</th>
                </tr>
            </thead>
            <tbody>
            
                <?php foreach ($_SESSION['cart'] as $item): 
                    $subtotal = $item['price'] * $item['qty'];
                    $total += $subtotal;
                ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 15px 10px; display: flex; align-items: center; gap: 15px;">
                          
                            <img src="/PerfumeStore_20/<?php echo htmlspecialchars($item['img']); ?>" width="50" alt="">
                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                        </td>
                        <td><?php echo number_format($item['price'], 2); ?> €</td>
                        <td><?php echo $item['qty']; ?></td>
                        <td><?php echo number_format($subtotal, 2); ?> €</td>
                        <td>
                            <a href="cart.php?action=delete&name=<?php echo urlencode($item['name']); ?>" style="color: red; text-decoration: none; font-weight: bold;">Remove</a>
                        </td>
                    </tr>
                    
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 30px;">
            <h3>Total: <span style="color: green;"><?php echo number_format($total, 2); ?> €</span></h3>
            <br>
            <a href="checkout.php" class="btn" style="background: black; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; font-weight: bold;">Vazhdo te Pagesa</a>
        </div>
    <?php endif; ?>
</main>

<?php include("includes/footer.php"); ?>