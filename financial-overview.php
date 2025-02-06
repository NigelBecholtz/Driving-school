<?php
session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin"){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Haal totale omzet op
$sql = "SELECT 
            SUM(CASE WHEN i.status = 'paid' THEN i.amount ELSE 0 END) as total_revenue,
            SUM(CASE WHEN i.status = 'unpaid' THEN i.amount ELSE 0 END) as pending_revenue,
            COUNT(CASE WHEN i.status = 'paid' THEN 1 END) as paid_invoices,
            COUNT(CASE WHEN i.status = 'unpaid' THEN 1 END) as unpaid_invoices
        FROM invoices i";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$revenue_stats = $stmt->fetch();

// Haal omzet per pakket op
$sql = "SELECT 
            p.name as package_name,
            COUNT(*) as total_sold,
            SUM(CASE WHEN i.status = 'paid' THEN i.amount ELSE 0 END) as revenue
        FROM packages p
        JOIN package_orders po ON p.id = po.package_id
        JOIN invoices i ON po.id = i.order_id
        GROUP BY p.id, p.name
        ORDER BY revenue DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$package_stats = $stmt->fetchAll();

// Haal recente transacties op
$sql = "SELECT 
            i.invoice_number,
            i.amount,
            i.status,
            i.paid_at,
            u.first_name,
            u.last_name,
            p.name as package_name
        FROM invoices i
        JOIN package_orders po ON i.order_id = po.id
        JOIN users u ON po.user_id = u.id
        JOIN packages p ON po.package_id = p.id
        ORDER BY i.created_at DESC
        LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$recent_transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financieel Overzicht - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Financieel Overzicht</h1>

        <!-- Statistieken -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat bg-base-100 rounded-lg shadow">
                <div class="stat-title">Totale Omzet</div>
                <div class="stat-value text-primary">€<?php echo number_format($revenue_stats['total_revenue'], 2); ?></div>
                <div class="stat-desc"><?php echo $revenue_stats['paid_invoices']; ?> betaalde facturen</div>
            </div>
            
            <div class="stat bg-base-100 rounded-lg shadow">
                <div class="stat-title">Openstaand Bedrag</div>
                <div class="stat-value text-secondary">€<?php echo number_format($revenue_stats['pending_revenue'], 2); ?></div>
                <div class="stat-desc"><?php echo $revenue_stats['unpaid_invoices']; ?> openstaande facturen</div>
            </div>
            
            <div class="stat bg-base-100 rounded-lg shadow">
                <div class="stat-title">Gemiddelde Orderwaarde</div>
                <div class="stat-value">€<?php 
                    $total_invoices = $revenue_stats['paid_invoices'] + $revenue_stats['unpaid_invoices'];
                    $avg_value = $total_invoices > 0 ? 
                        ($revenue_stats['total_revenue'] + $revenue_stats['pending_revenue']) / $total_invoices : 0;
                    echo number_format($avg_value, 2); 
                ?></div>
            </div>
            
            <div class="stat bg-base-100 rounded-lg shadow">
                <div class="stat-title">Conversieratio</div>
                <div class="stat-value"><?php 
                    $total_invoices = $revenue_stats['paid_invoices'] + $revenue_stats['unpaid_invoices'];
                    $conversion = $total_invoices > 0 ? 
                        ($revenue_stats['paid_invoices'] / $total_invoices) * 100 : 0;
                    echo number_format($conversion, 1); 
                ?>%</div>
                <div class="stat-desc">van facturen betaald</div>
            </div>
        </div>

        <!-- Omzet per Pakket -->
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <h2 class="text-2xl font-bold mb-6">Omzet per Pakket</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Pakket</th>
                                <th>Aantal Verkocht</th>
                                <th>Omzet</th>
                                <th>Gemiddelde Prijs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($package_stats as $stat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($stat['package_name']); ?></td>
                                    <td><?php echo $stat['total_sold']; ?></td>
                                    <td>€<?php echo number_format($stat['revenue'], 2); ?></td>
                                    <td>€<?php echo $stat['total_sold'] > 0 ? 
                                        number_format($stat['revenue'] / $stat['total_sold'], 2) : '0.00'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recente Transacties -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="text-2xl font-bold mb-6">Recente Transacties</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Factuurnummer</th>
                                <th>Student</th>
                                <th>Pakket</th>
                                <th>Bedrag</th>
                                <th>Status</th>
                                <th>Betaaldatum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['invoice_number']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['first_name'] . ' ' . $transaction['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['package_name']); ?></td>
                                    <td>€<?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td>
                                        <span class="badge <?php echo $transaction['status'] === 'paid' ? 
                                            'badge-success' : 'badge-warning'; ?>">
                                            <?php echo $transaction['status'] === 'paid' ? 'Betaald' : 'Open'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $transaction['paid_at'] ? 
                                        date('d-m-Y H:i', strtotime($transaction['paid_at'])) : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 