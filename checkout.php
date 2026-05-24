<?php
session_start();
include("includes/header.php");
include("includes/navbar.php");

// Nëse shporta është e zbrazët, mos e lejo të hyjë në checkout
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['qty'];
}
?>

<main class="checkout-page" style="padding: 50px 10%; min-height: 60vh; display: flex; gap: 50px; flex-wrap: wrap;">
    
    <!-- Forma e Porosisë -->
    <div style="flex: 1; min-width: 300px;">
        <h2>Të dhënat e Dërgesës</h2>
        <br>
        <form action="includes/process-checkout.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Emri dhe Mbiemri:</label>
                <input type="text" name="full_name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Adresa e Banimit:</label>
                <input type="text" name="address" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Qyteti:</label>
                <input type="text" name="city" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Numri i Telefonit:</label>
                <input type="text" name="phone" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="background: black; color: white; padding: 12px; border: none; font-weight: bold; cursor: pointer; border-radius: 4px; margin-top: 10px;">
                Përfundo Porosinë (<?php echo number_format($total, 2); ?> €)
            </button>
        </form>
    </div>

    <!-- Përmbledhja e Shportës -->
    <div style="flex: 1; min-width: 300px; background: #f9f9f9; padding: 25px; border-radius: 8px; height: fit-content;">
        <h3>Përmbledhja e Porosisë</h3>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">
        
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span><?php echo htmlspecialchars($item['name']); ?> <strong>x<?php echo $item['qty']; ?></strong></span>
                <span><?php echo number_format($item['price'] * $item['qty'], 2); ?> €</span>
            </div>
        <?php endforeach; ?>
        
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">
        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem;">
            <span>TOTALI:</span>
            <span style="color: green;"><?php echo number_format($total, 2); ?> €</span>
        </div>
    </div>

</main>

<?php include("includes/footer.php"); ?>