<?php
session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin"){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Haal huidige instellingen op
$sql = "SELECT setting_key, setting_value FROM system_settings";
$stmt = $pdo->prepare($sql);
try {
    $stmt->execute();
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    $error_message = "Er is een fout opgetreden bij het ophalen van de instellingen.";
} catch(PDOException $e) {
    $error_message = "Er is een fout opgetreden bij het ophalen van de instellingen.";
}

// Verwerk form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>POST data:\n";
    print_r($_POST);
    echo "</pre>";

    try {
        $pdo->beginTransaction();

        // Update algemene instellingen
        $updates = [
            'company_name' => $_POST['company_name'],
            'company_email' => $_POST['company_email'],
            'company_phone' => $_POST['company_phone'],
            'company_address' => $_POST['company_address'],
            'company_postal' => $_POST['company_postal'],
            'company_city' => $_POST['company_city'],
            'invoice_prefix' => $_POST['invoice_prefix'],
            'vat_percentage' => $_POST['vat_percentage'],
            'max_lessons_per_day' => $_POST['max_lessons_per_day'],
            'min_booking_notice_hours' => $_POST['min_booking_notice_hours'],
            'max_booking_weeks_ahead' => $_POST['max_booking_weeks_ahead'],
            'cancellation_hours_notice' => $_POST['cancellation_hours_notice']
        ];

        foreach($updates as $key => $value) {
            $sql = "INSERT INTO system_settings (setting_key, setting_value) 
                    VALUES (:key, :value) 
                    ON DUPLICATE KEY UPDATE setting_value = :value";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':key' => $key, ':value' => $value]);
        }

        $pdo->commit();
        $success_message = "Instellingen zijn succesvol bijgewerkt.";
    } catch(PDOException $e) {
        $pdo->rollBack();
        $error_message = "Er is een fout opgetreden bij het opslaan van de instellingen.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Systeeminstellingen - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Systeeminstellingen</h1>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <?php if(isset($error_message)): ?>
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-8">
            <!-- Bedrijfsgegevens -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="text-2xl font-bold mb-6">Bedrijfsgegevens</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Bedrijfsnaam</span>
                            </label>
                            <input type="text" name="company_name" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['company_name'] ?? ''); ?>" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">E-mailadres</span>
                            </label>
                            <input type="email" name="company_email" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['company_email'] ?? ''); ?>" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Telefoonnummer</span>
                            </label>
                            <input type="tel" name="company_phone" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['company_phone'] ?? ''); ?>" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Adres</span>
                            </label>
                            <input type="text" name="company_address" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['company_address'] ?? ''); ?>" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Postcode</span>
                            </label>
                            <input type="text" name="company_postal" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['company_postal'] ?? ''); ?>" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Plaats</span>
                            </label>
                            <input type="text" name="company_city" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['company_city'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Factuurinstellingen -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="text-2xl font-bold mb-6">Factuurinstellingen</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Factuurprefix</span>
                            </label>
                            <input type="text" name="invoice_prefix" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['invoice_prefix'] ?? ''); ?>" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">BTW percentage</span>
                            </label>
                            <input type="number" name="vat_percentage" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['vat_percentage'] ?? '21'); ?>" 
                                   min="0" max="100" step="0.1" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lesinstellingen -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="text-2xl font-bold mb-6">Lesinstellingen</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Maximum aantal lessen per dag</span>
                            </label>
                            <input type="number" name="max_lessons_per_day" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['max_lessons_per_day'] ?? '8'); ?>" 
                                   min="1" max="12" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Minimale aanmeldtijd (uren)</span>
                            </label>
                            <input type="number" name="min_booking_notice_hours" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['min_booking_notice_hours'] ?? '24'); ?>" 
                                   min="1" max="168" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Maximum aantal weken vooruit plannen</span>
                            </label>
                            <input type="number" name="max_booking_weeks_ahead" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['max_booking_weeks_ahead'] ?? '8'); ?>" 
                                   min="1" max="52" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Annuleringstermijn (uren)</span>
                            </label>
                            <input type="number" name="cancellation_hours_notice" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($settings['cancellation_hours_notice'] ?? '24'); ?>" 
                                   min="1" max="168" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="reset" class="btn btn-outline">Reset</button>
                <button type="submit" class="btn btn-primary">Instellingen Opslaan</button>
            </div>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 