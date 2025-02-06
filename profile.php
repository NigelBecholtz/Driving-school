<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Haal gebruikersgegevens op
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch();

// Verwerk form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $error_message = "";
    $success_message = "";

    // Valideer en update profiel
    if(isset($_POST['update_profile'])) {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $postal = trim($_POST['postal']);
        $city = trim($_POST['city']);

        if(empty($first_name) || empty($last_name) || empty($phone) || empty($address) || empty($postal) || empty($city)) {
            $error_message = "Vul alle verplichte velden in.";
        } else {
            $sql = "UPDATE users SET 
                    first_name = :first_name,
                    last_name = :last_name,
                    phone = :phone,
                    address = :address,
                    postal = :postal,
                    city = :city
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":first_name", $first_name);
            $stmt->bindParam(":last_name", $last_name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":postal", $postal);
            $stmt->bindParam(":city", $city);
            $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);

            if($stmt->execute()) {
                $success_message = "Je profiel is succesvol bijgewerkt.";
                // Update user data
                $user['first_name'] = $first_name;
                $user['last_name'] = $last_name;
                $user['phone'] = $phone;
                $user['address'] = $address;
                $user['postal'] = $postal;
                $user['city'] = $city;
            } else {
                $error_message = "Er is een fout opgetreden bij het bijwerken van je profiel.";
            }
        }
    }

    // Valideer en update wachtwoord
    if(isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if(empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error_message = "Vul alle wachtwoordvelden in.";
        } elseif($new_password !== $confirm_password) {
            $error_message = "De nieuwe wachtwoorden komen niet overeen.";
        } elseif(strlen($new_password) < 6) {
            $error_message = "Het nieuwe wachtwoord moet minimaal 6 tekens lang zijn.";
        } elseif(!password_verify($current_password, $user['password'])) {
            $error_message = "Het huidige wachtwoord is onjuist.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $sql = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);

            if($stmt->execute()) {
                $success_message = "Je wachtwoord is succesvol bijgewerkt.";
            } else {
                $error_message = "Er is een fout opgetreden bij het bijwerken van je wachtwoord.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Profiel - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Mijn Profiel</h1>

        <?php if(isset($error_message)): ?>
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Profielgegevens -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="text-2xl font-bold mb-6">Profielgegevens</h2>
                    <form method="POST" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Voornaam</span>
                                </label>
                                <input type="text" name="first_name" class="input input-bordered" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Achternaam</span>
                                </label>
                                <input type="text" name="last_name" class="input input-bordered" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">E-mailadres</span>
                            </label>
                            <input type="email" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <label class="label">
                                <span class="label-text-alt">E-mailadres kan niet worden gewijzigd</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Telefoonnummer</span>
                            </label>
                            <input type="tel" name="phone" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Adres</span>
                            </label>
                            <input type="text" name="address" class="input input-bordered" 
                                   value="<?php echo htmlspecialchars($user['address']); ?>" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Postcode</span>
                                </label>
                                <input type="text" name="postal" class="input input-bordered" 
                                       value="<?php echo htmlspecialchars($user['postal']); ?>" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Plaats</span>
                                </label>
                                <input type="text" name="city" class="input input-bordered" 
                                       value="<?php echo htmlspecialchars($user['city']); ?>" required>
                            </div>
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                Profiel Bijwerken
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Wachtwoord wijzigen -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="text-2xl font-bold mb-6">Wachtwoord Wijzigen</h2>
                    <form method="POST" class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Huidig Wachtwoord</span>
                            </label>
                            <input type="password" name="current_password" class="input input-bordered" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Nieuw Wachtwoord</span>
                            </label>
                            <input type="password" name="new_password" class="input input-bordered" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Bevestig Nieuw Wachtwoord</span>
                            </label>
                            <input type="password" name="confirm_password" class="input input-bordered" required>
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" name="update_password" class="btn btn-primary">
                                Wachtwoord Wijzigen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 