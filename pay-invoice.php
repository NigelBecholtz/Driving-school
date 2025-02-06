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
$sql = "SELECT i.*, po.created_at as order_date, p.name as package_name, p.price, p.type
        FROM invoices i 
        JOIN package_orders po ON i.order_id = po.id 
        JOIN packages p ON po.package_id = p.id 
        WHERE i.id = :invoice_id AND po.user_id = :user_id AND i.status = 'unpaid'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":invoice_id", $_GET['id'], PDO::PARAM_INT);
$stmt->bindParam(":user_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$invoice = $stmt->fetch();

if(!$invoice) {
    header("location: my-invoices.php");
    exit;
}

// Process payment
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['process_payment'])) {
    try {
        $pdo->beginTransaction();

        // Update invoice status
        $sql = "UPDATE invoices SET status = 'paid', paid_at = NOW() WHERE id = :invoice_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":invoice_id", $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        // Update package_order status to confirmed after payment
        $sql = "UPDATE package_orders po 
                JOIN invoices i ON i.order_id = po.id 
                SET po.status = 'confirmed' 
                WHERE i.id = :invoice_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":invoice_id", $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        $pdo->commit();
        header("location: my-invoices.php?paid=1");
        exit;
    } catch(PDOException $e) {
        $pdo->rollBack();
        $payment_error = "Er is een fout opgetreden bij het verwerken van de betaling.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betaal Factuur - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .payment-method label {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .payment-method label:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .payment-method label.selected {
            border-color: hsl(var(--p));
            background-color: hsl(var(--b2));
        }
        
        .payment-form {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
        }
        
        .payment-form.active {
            max-height: 500px;
            opacity: 1;
            padding: 1rem;
            margin-top: 1rem;
            background-color: hsl(var(--b2));
            border-radius: 0.5rem;
        }
    </style>
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-4xl font-bold mb-8 text-center">Betaal Factuur</h1>

            <!-- Factuur Details -->
            <div class="card bg-base-100 shadow-xl mb-8">
                <div class="card-body">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold mb-4">Factuurgegevens</h2>
                            <div class="space-y-2">
                                <p class="text-lg"><span class="font-semibold">Factuurnummer:</span> <?php echo htmlspecialchars($invoice['invoice_number']); ?></p>
                                <p class="text-lg"><span class="font-semibold">Pakket:</span> <?php echo htmlspecialchars($invoice['package_name']); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1">Te betalen</p>
                            <p class="text-3xl font-bold text-primary">€<?php echo number_format($invoice['amount'], 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Betaalmethodes -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="text-2xl font-bold mb-6">Kies een betaalmethode</h2>

                    <form method="POST" id="payment-form" class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- iDEAL -->
                            <div class="payment-method" onclick="selectPaymentMethod('ideal')">
                                <input type="radio" name="payment_method" value="ideal" class="hidden" id="ideal">
                                <label for="ideal" class="block p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-university text-3xl text-[#0066FF]"></i>
                                        <span class="font-semibold">iDEAL</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Creditcard -->
                            <div class="payment-method" onclick="selectPaymentMethod('creditcard')">
                                <input type="radio" name="payment_method" value="creditcard" class="hidden" id="creditcard">
                                <label for="creditcard" class="block p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-credit-card text-3xl text-[#FF5F00]"></i>
                                        <span class="font-semibold">Creditcard</span>
                                    </div>
                                </label>
                            </div>

                            <!-- PayPal -->
                            <div class="payment-method" onclick="selectPaymentMethod('paypal')">
                                <input type="radio" name="payment_method" value="paypal" class="hidden" id="paypal">
                                <label for="paypal" class="block p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                                    <div class="flex items-center gap-3">
                                        <i class="fab fa-paypal text-3xl text-[#003087]"></i>
                                        <span class="font-semibold">PayPal</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Bancontact -->
                            <div class="payment-method" onclick="selectPaymentMethod('bancontact')">
                                <input type="radio" name="payment_method" value="bancontact" class="hidden" id="bancontact">
                                <label for="bancontact" class="block p-4 border rounded-lg cursor-pointer hover:border-primary transition-colors">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-money-bill-wave text-3xl text-[#005498]"></i>
                                        <span class="font-semibold">Bancontact</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Dynamische betaalformulieren -->
                        <div id="payment-forms">
                            <!-- iDEAL Form -->
                            <div id="ideal-form" class="payment-form">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Kies je bank</span>
                                    </label>
                                    <select class="select select-bordered" required name="ideal_bank">
                                        <option value="">Selecteer een bank</option>
                                        <option value="ing">ING</option>
                                        <option value="rabobank">Rabobank</option>
                                        <option value="abn">ABN AMRO</option>
                                        <option value="sns">SNS Bank</option>
                                        <option value="asn">ASN Bank</option>
                                        <option value="regiobank">RegioBank</option>
                                        <option value="bunq">Bunq</option>
                                        <option value="knab">Knab</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Creditcard Form -->
                            <div id="creditcard-form" class="payment-form space-y-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Kaartnummer</span>
                                    </label>
                                    <input type="text" name="card_number" class="input input-bordered" 
                                           placeholder="1234 5678 9012 3456" pattern="[0-9\s]{13,19}" maxlength="19" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">Vervaldatum</span>
                                        </label>
                                        <input type="text" name="expiry_date" class="input input-bordered" 
                                               placeholder="MM/JJ" pattern="(0[1-9]|1[0-2])\/([0-9]{2})" required>
                                    </div>
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">CVC</span>
                                        </label>
                                        <input type="text" name="cvc" class="input input-bordered" 
                                               placeholder="123" pattern="[0-9]{3,4}" maxlength="4" required>
                                    </div>
                                </div>
                            </div>

                            <!-- PayPal Form -->
                            <div id="paypal-form" class="payment-form">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">PayPal e-mailadres</span>
                                    </label>
                                    <input type="email" name="paypal_email" class="input input-bordered" 
                                           placeholder="uw@email.nl" required>
                                </div>
                            </div>

                            <!-- Bancontact Form -->
                            <div id="bancontact-form" class="payment-form">
                                <div class="alert alert-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>U wordt doorgestuurd naar de Bancontact betaalomgeving.</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8 gap-4">
                            <a href="my-invoices.php" class="btn btn-outline flex-1">Annuleren</a>
                            <button type="submit" name="process_payment" class="btn btn-primary" id="submit-button">
                                Betaal €<?php echo number_format($invoice['amount'], 2); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    function selectPaymentMethod(method) {
        // Reset alle payment methods
        document.querySelectorAll('.payment-method label').forEach(label => {
            label.classList.remove('border-primary');
            label.classList.remove('selected');
        });
        
        // Highlight geselecteerde method
        const selectedLabel = document.querySelector(`label[for="${method}"]`);
        selectedLabel.classList.add('border-primary', 'selected');
        
        // Update radio button
        document.getElementById(method).checked = true;
        
        // Verberg alle formulieren
        document.querySelectorAll('.payment-form').forEach(form => {
            form.classList.remove('active');
        });
        
        // Toon relevant formulier
        const formElement = document.getElementById(`${method}-form`);
        if (formElement) {
            formElement.classList.add('active');
        }
    }

    // Format credit card number as user types
    document.querySelector('input[name="card_number"]')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(.{4})/g, '$1 ').trim();
        e.target.value = value;
    });

    // Format expiry date as user types
    document.querySelector('input[name="expiry_date"]')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }
        e.target.value = value;
    });
    </script>
</body>
</html> 