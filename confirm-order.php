<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Initialize order session
if(isset($_GET['package_id'])) {
    if(!isset($_SESSION['order']) || $_SESSION['order']['package_id'] != $_GET['package_id']) {
        $_SESSION['order'] = [
            'package_id' => $_GET['package_id'],
            'step' => 1
        ];
    }
}

// Redirect if no order in session
if(!isset($_SESSION['order'])) {
    header("location: packages.php");
    exit;
}

// Get package details
$sql = "SELECT * FROM packages WHERE id = :id AND active = 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $_SESSION['order']['package_id'], PDO::PARAM_INT);
$stmt->execute();
$package = $stmt->fetch();

if(!$package) {
    header("location: packages.php");
    exit;
}

// Handle form submissions
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Als op "Vorige" wordt geklikt, ga direct terug zonder validatie
    if(isset($_POST['prev_step'])) {
        if($_SESSION['order']['step'] > 1) {
            $_SESSION['order']['step']--;
        }
    }
    // Anders, verwerk de normale flow met validatie
    if(isset($_POST['next_step'])) {
        switch($_SESSION['order']['step']) {
            case 1:
                // Stap 1 heeft geen validatie nodig
                $_SESSION['order']['step'] = 2;
                break;

            case 2:
                // Valideer voorkeuren
                $preferred_days = $_POST['preferred_days'] ?? [];
                $preferred_time = $_POST['preferred_time'] ?? '';
                
                if(empty($preferred_days)) {
                    $error_message = "Selecteer minimaal één dag voor je lessen";
                } elseif(empty($preferred_time)) {
                    $error_message = "Selecteer een voorkeurstijd voor je lessen";
                } else {
                    // Sla voorkeuren op en ga naar volgende stap
                    $_SESSION['order']['preferred_days'] = $preferred_days;
                    $_SESSION['order']['preferred_time'] = $preferred_time;
                    $_SESSION['order']['step'] = 3;
                }
                break;

            case 3:
                // Valideer voorwaarden
                if(isset($_POST['terms_accepted']) && isset($_POST['privacy_accepted'])) {
                    $_SESSION['order']['terms_accepted'] = true;
                    $_SESSION['order']['privacy_accepted'] = true;
                    $_SESSION['order']['step'] = 4;
                } else {
                    $error_message = "Je moet akkoord gaan met beide voorwaarden om door te gaan.";
                }
                break;
        }
    } elseif(isset($_POST['confirm_order'])) {
        // Plaats bestelling
        try {
            $pdo->beginTransaction();

            // Maak bestelling aan
            $sql = "INSERT INTO package_orders (user_id, package_id, status, preferred_days, preferred_time) 
                    VALUES (:user_id, :package_id, 'pending', :preferred_days, :preferred_time)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $_SESSION['id'],
                ':package_id' => $_SESSION['order']['package_id'],
                ':preferred_days' => implode(',', $_SESSION['order']['preferred_days']),
                ':preferred_time' => $_SESSION['order']['preferred_time']
            ]);
            
            $order_id = $pdo->lastInsertId();
            
            // Maak factuur aan
            $invoice_number = date('Y') . str_pad($order_id, 6, '0', STR_PAD_LEFT);
            $sql = "INSERT INTO invoices (order_id, invoice_number, amount, status) 
                    VALUES (:order_id, :invoice_number, :amount, 'unpaid')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':order_id' => $order_id,
                ':invoice_number' => $invoice_number,
                ':amount' => $package['price']
            ]);

            $pdo->commit();
            unset($_SESSION['order']);
            header("Location: my-orders.php?success=1");
            exit();
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = "Er is iets misgegaan bij het plaatsen van je bestelling. Probeer het opnieuw.";
        }
    }
}

// Debug informatie toevoegen (tijdelijk)
if(isset($_POST['next_step'])) {
    error_log("POST data: " . print_r($_POST, true));
    error_log("Session data: " . print_r($_SESSION['order'], true));
}

$currentStep = $_SESSION['order']['step'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelling Bevestigen - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Voortgangsbalk -->
            <div class="card bg-base-100 shadow-xl mb-6">
                <div class="card-body p-4">
                    <ul class="steps steps-horizontal w-full">
                        <li class="step <?php echo $currentStep >= 1 ? 'step-primary' : ''; ?>">Pakket</li>
                        <li class="step <?php echo $currentStep >= 2 ? 'step-primary' : ''; ?>">Voorkeuren</li>
                        <li class="step <?php echo $currentStep >= 3 ? 'step-primary' : ''; ?>">Voorwaarden</li>
                        <li class="step <?php echo $currentStep >= 4 ? 'step-primary' : ''; ?>">Bevestiging</li>
                    </ul>
                </div>
            </div>

            <?php if(isset($error_message) && $_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <div class="alert alert-error mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>

            <!-- Hoofdformulier -->
            <div class="card bg-base-100 shadow-xl">
                <form method="POST" class="card-body">
                    <?php if($currentStep == 1): ?>
                        <!-- Stap 1: Pakketdetails -->
                        <h2 class="text-2xl font-bold mb-6">Controleer je pakket</h2>
                        <div class="bg-base-200 rounded-lg p-6">
                            <h3 class="text-xl font-bold mb-4"><?php echo htmlspecialchars($package['name']); ?></h3>
                            <p class="text-2xl font-bold text-primary mb-4">€<?php echo number_format($package['price'], 2); ?></p>
                            <div class="space-y-3">
                                <?php 
                                $description_points = explode("\n", $package['description']);
                                foreach($description_points as $point):
                                    if(trim($point)):
                                ?>
                                    <div class="flex items-start gap-2">
                                        <svg class="h-5 w-5 text-primary mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span><?php echo htmlspecialchars(trim($point, "- \t\n\r\0\x0B")); ?></span>
                                    </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>

                    <?php elseif($currentStep == 2): ?>
                        <!-- Stap 2: Voorkeuren -->
                        <h2 class="text-2xl font-bold mb-6">Jouw voorkeuren</h2>
                        
                        <!-- Voorkeursdagen -->
                        <div class="form-control mb-6">
                            <label class="label">
                                <span class="label-text text-lg font-semibold">Op welke dagen wil je les?</span>
                            </label>
                            <div class="bg-base-200 rounded-lg p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <?php 
                                    $days = ['Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];
                                    foreach($days as $day): 
                                    ?>
                                        <label class="flex items-center gap-3 p-3 bg-base-100 rounded-lg cursor-pointer hover:bg-base-300 transition-colors">
                                            <input type="checkbox" 
                                                   name="preferred_days[]" 
                                                   value="<?php echo $day; ?>" 
                                                   class="checkbox checkbox-primary"
                                                   <?php echo isset($_SESSION['order']['preferred_days']) && in_array($day, $_SESSION['order']['preferred_days']) ? 'checked' : ''; ?>>
                                            <span class="text-lg"><?php echo $day; ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Voorkeurstijd -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-lg font-semibold">Wat is je voorkeurstijd?</span>
                            </label>
                            <div class="bg-base-200 rounded-lg p-6">
                                <select name="preferred_time" class="select select-bordered w-full text-lg">
                                    <option value="">Kies een tijdvak</option>
                                    <option value="ochtend" <?php echo isset($_SESSION['order']['preferred_time']) && $_SESSION['order']['preferred_time'] === 'ochtend' ? 'selected' : ''; ?>>
                                        Ochtend (9:00 - 12:00)
                                    </option>
                                    <option value="middag" <?php echo isset($_SESSION['order']['preferred_time']) && $_SESSION['order']['preferred_time'] === 'middag' ? 'selected' : ''; ?>>
                                        Middag (12:00 - 17:00)
                                    </option>
                                    <option value="avond" <?php echo isset($_SESSION['order']['preferred_time']) && $_SESSION['order']['preferred_time'] === 'avond' ? 'selected' : ''; ?>>
                                        Avond (17:00 - 21:00)
                                    </option>
                                </select>
                            </div>
                        </div>

                    <?php elseif($currentStep == 3): ?>
                        <!-- Stap 3: Voorwaarden -->
                        <h2 class="text-2xl font-bold mb-6">Voorwaarden</h2>
                        <div class="bg-base-200 rounded-lg p-6 space-y-6">
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="terms_accepted" class="checkbox checkbox-primary">
                                    <span class="label-text text-lg">
                                        Ik ga akkoord met de <a href="#" class="link link-primary">algemene voorwaarden</a>
                                    </span>
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="privacy_accepted" class="checkbox checkbox-primary">
                                    <span class="label-text text-lg">
                                        Ik ga akkoord met het <a href="#" class="link link-primary">privacybeleid</a>
                                    </span>
                                </label>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Stap 4: Bevestiging -->
                        <h2 class="text-2xl font-bold mb-6">Bevestig je bestelling</h2>
                        <div class="space-y-6">
                            <div class="bg-base-200 rounded-lg p-6">
                                <h3 class="font-bold text-lg mb-2">Gekozen pakket</h3>
                                <p class="text-lg"><?php echo htmlspecialchars($package['name']); ?></p>
                                <p class="text-2xl font-bold text-primary mt-2">€<?php echo number_format($package['price'], 2); ?></p>
                            </div>

                            <div class="bg-base-200 rounded-lg p-6">
                                <h3 class="font-bold text-lg mb-4">Jouw voorkeuren</h3>
                                <p class="mb-2">
                                    <span class="font-semibold">Dagen:</span> 
                                    <?php echo implode(', ', $_SESSION['order']['preferred_days']); ?>
                                </p>
                                <p>
                                    <span class="font-semibold">Tijdvak:</span> 
                                    <?php echo ucfirst($_SESSION['order']['preferred_time']); ?>
                                </p>
                            </div>

                            <div class="alert alert-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Na bevestiging ontvang je een factuur en nemen we contact met je op voor het inplannen van je eerste les.</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Navigatieknoppen -->
                    <div class="flex justify-between mt-8">
                        <?php if($currentStep > 1): ?>
                            <button type="submit" name="prev_step" class="btn btn-outline">Vorige</button>
                        <?php else: ?>
                            <a href="packages.php" class="btn btn-outline">Annuleren</a>
                        <?php endif; ?>

                        <?php if($currentStep < 4): ?>
                            <button type="submit" name="next_step" class="btn btn-primary">Volgende</button>
                        <?php else: ?>
                            <button type="submit" name="confirm_order" class="btn btn-primary">Bestelling Plaatsen</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 