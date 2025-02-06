<?php
// Initialize the session
session_start();

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "includes/config.php";

// Fetch user's orders
$sql = "SELECT po.*, p.name as package_name, p.price, p.type 
        FROM package_orders po 
        JOIN packages p ON po.package_id = p.id 
        WHERE po.user_id = :user_id 
        ORDER BY po.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":user_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Bestellingen - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Mijn Bestellingen</h1>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Je bestelling is succesvol geplaatst!</span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Besteldatum</th>
                        <th>Pakket</th>
                        <th>Type</th>
                        <th>Prijs</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                        <tr>
                            <td><?php echo date('d-m-Y H:i', strtotime($order['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($order['package_name']); ?></td>
                            <td><?php echo ucfirst($order['type']); ?></td>
                            <td>€<?php echo number_format($order['price'], 2); ?></td>
                            <td>
                                <div class="badge <?php 
                                    echo $order['status'] === 'confirmed' ? 'badge-success' : 
                                         ($order['status'] === 'cancelled' ? 'badge-error' : 'badge-warning'); 
                                ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 