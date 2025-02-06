<?php
// Initialize the session
session_start();

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Fetch invoice details
$sql = "SELECT i.*, po.created_at as order_date, p.name as package_name, p.price, p.type,
        u.first_name, u.last_name, u.address, u.postal_code, u.city
        FROM invoices i 
        JOIN package_orders po ON i.order_id = po.id 
        JOIN packages p ON po.package_id = p.id 
        JOIN users u ON po.user_id = u.id
        WHERE i.id = :invoice_id AND po.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":invoice_id", $_GET['id'], PDO::PARAM_INT);
$stmt->bindParam(":user_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$invoice = $stmt->fetch();

if(!$invoice) {
    header("location: my-invoices.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factuur <?php echo htmlspecialchars($invoice['invoice_number']); ?> - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @media print {
            @page { size: A4; margin: 20mm; }
            body { background-color: white !important; }
            .btn, .no-print { display: none !important; }
            .container { padding: 0 !important; }
            .shadow-lg { box-shadow: none !important; }
        }
        .invoice-table th, .invoice-table td {
            padding: 12px 16px;
        }
        .invoice-table th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-base-200">
    <div class="container mx-auto p-4 md:p-8">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
            <!-- Logo en Factuur Header -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-12 border-b pb-6">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-primary mb-2">FACTUUR</h1>
                    <div class="space-y-1 text-lg">
                        <p><span class="font-semibold">Factuurnummer:</span> <?php echo htmlspecialchars($invoice['invoice_number']); ?></p>
                        <p><span class="font-semibold">Datum:</span> <?php echo date('d-m-Y', strtotime($invoice['created_at'])); ?></p>
                    </div>
                </div>
                <div class="text-right space-y-1 text-lg">
                    <h2 class="text-2xl font-bold text-primary mb-2">RijVaardig Academy</h2>
                    <p>Voorbeeldstraat 123</p>
                    <p>1234 AB Amsterdam</p>
                    <p>info@rijvaardig.nl</p>
                    <p>KVK: 12345678</p>
                    <p>BTW: NL123456789B01</p>
                </div>
            </div>

            <!-- Klant Informatie -->
            <div class="mb-12">
                <h3 class="text-xl font-bold mb-4 text-primary">Factuur voor</h3>
                <div class="space-y-1 text-lg">
                    <p class="font-semibold"><?php echo htmlspecialchars($invoice['first_name'] . ' ' . $invoice['last_name']); ?></p>
                    <p><?php echo htmlspecialchars($invoice['address']); ?></p>
                    <p><?php echo htmlspecialchars($invoice['postal_code'] . ' ' . $invoice['city']); ?></p>
                </div>
            </div>

            <!-- Factuur Details -->
            <div class="mb-12">
                <table class="invoice-table table table-zebra w-full">
                    <thead>
                        <tr>
                            <th class="text-left rounded-tl-lg">Omschrijving</th>
                            <th class="text-center">Aantal</th>
                            <th class="text-right rounded-tr-lg">Bedrag</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-lg">
                            <td>
                                <p class="font-semibold"><?php echo htmlspecialchars($invoice['package_name']); ?></p>
                                <p class="text-gray-600">
                                    <?php echo ucfirst($invoice['type']); ?>
                                </p>
                            </td>
                            <td class="text-center">1</td>
                            <td class="text-right">€<?php echo number_format($invoice['amount'], 2); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="text-lg">
                            <td colspan="2" class="text-right font-bold">Totaal:</td>
                            <td class="text-right font-bold">€<?php echo number_format($invoice['amount'], 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Betaalstatus -->
            <div class="mb-8 p-4 border rounded-lg">
                <div class="flex items-center gap-3 text-lg">
                    <span class="font-bold">Status:</span>
                    <div class="badge badge-lg <?php 
                        echo $invoice['status'] === 'paid' ? 'badge-success' : 
                             ($invoice['status'] === 'cancelled' ? 'badge-error' : 'badge-warning'); 
                    ?>">
                        <?php 
                        $status_text = [
                            'paid' => 'Betaald',
                            'unpaid' => 'Nog te betalen',
                            'cancelled' => 'Geannuleerd'
                        ];
                        echo $status_text[$invoice['status']] ?? ucfirst($invoice['status']); 
                        ?>
                    </div>
                </div>
            </div>

            <!-- Betaalinstructies -->
            <?php if($invoice['status'] === 'unpaid'): ?>
            <div class="bg-base-200 p-6 rounded-lg mb-8">
                <h3 class="text-xl font-bold mb-4 text-primary">Betaalinstructies</h3>
                <p class="mb-4 text-lg">Gelieve het verschuldigde bedrag over te maken naar:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-lg">
                    <div>
                        <p><span class="font-semibold">Bank:</span> RijVaardig Academy</p>
                        <p><span class="font-semibold">IBAN:</span> NL12 RABO 0123 4567 89</p>
                    </div>
                    <div>
                        <p><span class="font-semibold">Onder vermelding van:</span></p>
                        <p class="font-mono bg-base-100 p-2 rounded">
                            <?php echo htmlspecialchars($invoice['invoice_number']); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Knoppen -->
            <div class="flex justify-between mt-12 no-print">
                <button onclick="window.print()" class="btn btn-primary btn-lg gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Factuur
                </button>
                <a href="my-invoices.php" class="btn btn-outline btn-lg">Terug naar Overzicht</a>
            </div>
        </div>
    </div>
</body>
</html> 