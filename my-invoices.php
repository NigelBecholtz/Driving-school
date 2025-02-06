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

// Fetch user's invoices with order and package details
$sql = "SELECT i.*, po.created_at as order_date, p.name as package_name, p.price, p.type 
        FROM invoices i 
        JOIN package_orders po ON i.order_id = po.id 
        JOIN packages p ON po.package_id = p.id 
        WHERE po.user_id = :user_id 
        ORDER BY i.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":user_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$invoices = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Facturen - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Mijn Facturen</h1>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Factuurnummer</th>
                        <th>Datum</th>
                        <th>Pakket</th>
                        <th>Bedrag</th>
                        <th>Status</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($invoices as $invoice): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($invoice['created_at'])); ?></td>
                            <td>
                                <?php echo htmlspecialchars($invoice['package_name']); ?>
                                <br>
                                <span class="text-sm text-gray-500">
                                    <?php echo ucfirst($invoice['type']); ?>
                                </span>
                            </td>
                            <td>€<?php echo number_format($invoice['amount'], 2); ?></td>
                            <td>
                                <div class="badge <?php 
                                    echo $invoice['status'] === 'paid' ? 'badge-success' : 
                                         ($invoice['status'] === 'cancelled' ? 'badge-error' : 'badge-warning'); 
                                ?>">
                                    <?php echo ucfirst($invoice['status']); ?>
                                </div>
                            </td>
                            <td>
                                <a href="view-invoice.php?id=<?php echo $invoice['id']; ?>" 
                                   class="btn btn-primary btn-sm">
                                    Bekijk Factuur
                                </a>
                                <?php if($invoice['status'] === 'unpaid'): ?>
                                    <a href="pay-invoice.php?id=<?php echo $invoice['id']; ?>" 
                                       class="btn btn-success btn-sm ml-2">
                                        Betalen
                                    </a>
                                <?php endif; ?>
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