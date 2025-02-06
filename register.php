<?php
// Include config file
require_once "includes/config.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = $first_name = $last_name = $phone = $address = $city = $postal_code = "";
$email_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $phone_err = $address_err = $city_err = $postal_code_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Vul een e-mailadres in.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = :email";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = trim($_POST["email"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $email_err = "Dit e-mailadres is al in gebruik.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oeps! Er is iets fout gegaan. Probeer het later opnieuw.";
            }

            unset($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Vul een wachtwoord in.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Wachtwoord moet minimaal 6 tekens bevatten.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Bevestig uw wachtwoord.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Wachtwoorden komen niet overeen.";
        }
    }

    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Vul uw voornaam in.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Vul uw achternaam in.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Vul uw telefoonnummer in.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate address
    if(empty(trim($_POST["address"]))){
        $address_err = "Vul uw adres in.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Validate city
    if(empty(trim($_POST["city"]))){
        $city_err = "Vul uw woonplaats in.";
    } else {
        $city = trim($_POST["city"]);
    }

    // Validate postal code
    if(empty(trim($_POST["postal_code"]))){
        $postal_code_err = "Vul uw postcode in.";
    } else {
        $postal_code = trim($_POST["postal_code"]);
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && 
       empty($first_name_err) && empty($last_name_err) && empty($phone_err) && 
       empty($address_err) && empty($city_err) && empty($postal_code_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, first_name, last_name, phone, address, city, postal_code, role) 
                VALUES (:email, :password, :first_name, :last_name, :phone, :address, :city, :postal_code, 'student')";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":first_name", $first_name, PDO::PARAM_STR);
            $stmt->bindParam(":last_name", $last_name, PDO::PARAM_STR);
            $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
            $stmt->bindParam(":address", $address, PDO::PARAM_STR);
            $stmt->bindParam(":city", $city, PDO::PARAM_STR);
            $stmt->bindParam(":postal_code", $postal_code, PDO::PARAM_STR);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Log de gebruiker direct in
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $pdo->lastInsertId();
                $_SESSION["email"] = $email;
                $_SESSION["role"] = "student";

                // Redirect naar de juiste pagina
                if(isset($_GET['redirect']) && isset($_GET['package_id'])) {
                    $redirect = filter_var($_GET['redirect'], FILTER_SANITIZE_URL);
                    $package_id = filter_var($_GET['package_id'], FILTER_SANITIZE_NUMBER_INT);
                    if($redirect === 'packages.php') {
                        // Automatisch bestelling plaatsen na registratie
                        $sql = "INSERT INTO package_orders (user_id, package_id, status) VALUES (:user_id, :package_id, 'pending')";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(":user_id", $_SESSION['id'], PDO::PARAM_INT);
                        $stmt->bindParam(":package_id", $package_id, PDO::PARAM_INT);
                        $stmt->execute();
                        
                        header("location: my-orders.php?success=1");
                        exit();
                    }
                } else {
                    header("location: dashboard.php");
                }
                exit();
            } else{
                echo "Oeps! Er is iets fout gegaan. Probeer het later opnieuw.";
            }

            unset($stmt);
        }
    }
    
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="min-h-screen flex items-center justify-center bg-base-200">
        <div class="card w-[32rem] bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center mb-4">Registreren</h2>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!-- Persoonlijke informatie -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Voornaam</span>
                            </label>
                            <input type="text" name="first_name" class="input input-bordered <?php echo (!empty($first_name_err)) ? 'input-error' : ''; ?>" value="<?php echo $first_name; ?>">
                            <?php if(!empty($first_name_err)): ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo $first_name_err; ?></span>
                                </label>
                            <?php endif; ?>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Achternaam</span>
                            </label>
                            <input type="text" name="last_name" class="input input-bordered <?php echo (!empty($last_name_err)) ? 'input-error' : ''; ?>" value="<?php echo $last_name; ?>">
                            <?php if(!empty($last_name_err)): ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo $last_name_err; ?></span>
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Contact informatie -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">E-mailadres</span>
                        </label>
                        <input type="email" name="email" class="input input-bordered <?php echo (!empty($email_err)) ? 'input-error' : ''; ?>" value="<?php echo $email; ?>">
                        <?php if(!empty($email_err)): ?>
                            <label class="label">
                                <span class="label-text-alt text-error"><?php echo $email_err; ?></span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Telefoonnummer</span>
                        </label>
                        <input type="tel" name="phone" class="input input-bordered <?php echo (!empty($phone_err)) ? 'input-error' : ''; ?>" value="<?php echo $phone; ?>">
                        <?php if(!empty($phone_err)): ?>
                            <label class="label">
                                <span class="label-text-alt text-error"><?php echo $phone_err; ?></span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <!-- Adres informatie -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Adres</span>
                        </label>
                        <input type="text" name="address" class="input input-bordered <?php echo (!empty($address_err)) ? 'input-error' : ''; ?>" value="<?php echo $address; ?>">
                        <?php if(!empty($address_err)): ?>
                            <label class="label">
                                <span class="label-text-alt text-error"><?php echo $address_err; ?></span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Postcode</span>
                            </label>
                            <input type="text" name="postal_code" class="input input-bordered <?php echo (!empty($postal_code_err)) ? 'input-error' : ''; ?>" value="<?php echo $postal_code; ?>">
                            <?php if(!empty($postal_code_err)): ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo $postal_code_err; ?></span>
                                </label>
                            <?php endif; ?>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Woonplaats</span>
                            </label>
                            <input type="text" name="city" class="input input-bordered <?php echo (!empty($city_err)) ? 'input-error' : ''; ?>" value="<?php echo $city; ?>">
                            <?php if(!empty($city_err)): ?>
                                <label class="label">
                                    <span class="label-text-alt text-error"><?php echo $city_err; ?></span>
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Wachtwoord -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Wachtwoord</span>
                        </label>
                        <input type="password" name="password" class="input input-bordered <?php echo (!empty($password_err)) ? 'input-error' : ''; ?>" value="<?php echo $password; ?>">
                        <?php if(!empty($password_err)): ?>
                            <label class="label">
                                <span class="label-text-alt text-error"><?php echo $password_err; ?></span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Bevestig wachtwoord</span>
                        </label>
                        <input type="password" name="confirm_password" class="input input-bordered <?php echo (!empty($confirm_password_err)) ? 'input-error' : ''; ?>" value="<?php echo $confirm_password; ?>">
                        <?php if(!empty($confirm_password_err)): ?>
                            <label class="label">
                                <span class="label-text-alt text-error"><?php echo $confirm_password_err; ?></span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">Registreren</button>
                    </div>
                </form>

                <p class="text-center mt-4">
                    Al een account? <a href="login.php" class="link link-primary">Login hier</a>
                </p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 