<?php
require_once "includes/config.php";

$email = "admin@rijvaardig.nl";
$password = "admin123";
$role = "admin";

// Eerst de oude admin verwijderen
$sql = "DELETE FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":email", $email, PDO::PARAM_STR);
$stmt->execute();

// Nieuw wachtwoord hashen
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Nieuwe admin toevoegen
$sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":email", $email, PDO::PARAM_STR);
$stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
$stmt->bindParam(":role", $role, PDO::PARAM_STR);

if($stmt->execute()){
    echo "Admin account succesvol aangemaakt!<br>";
    echo "Email: " . $email . "<br>";
    echo "Wachtwoord: " . $password . "<br>";
    echo "Gehashte wachtwoord in database: " . $hashed_password . "<br>";
} else {
    echo "Er is iets misgegaan bij het aanmaken van het admin account.";
}

// Controleer of de user correct is aangemaakt
$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":email", $email, PDO::PARAM_STR);
$stmt->execute();

if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<br>User gevonden in database:<br>";
    echo "ID: " . $row['id'] . "<br>";
    echo "Email: " . $row['email'] . "<br>";
    echo "Role: " . $row['role'] . "<br>";
} else {
    echo "<br>Gebruiker niet gevonden in database!";
}

try {
    // Begin transaction
    $pdo->beginTransaction();

    // 1. Voeg lessons_amount kolom toe aan packages tabel
    $sql = "ALTER TABLE packages 
            ADD COLUMN IF NOT EXISTS lessons_amount INT NOT NULL DEFAULT 0";
    $pdo->exec($sql);

    // 2. Update bestaande pakketten met standaard aantal lessen
    $sql = "UPDATE packages 
            SET lessons_amount = 
            CASE 
                WHEN name LIKE '%Basis%' THEN 10
                WHEN name LIKE '%Uitgebreid%' THEN 20
                WHEN name LIKE '%Compleet%' THEN 30
                ELSE 15
            END";
    $pdo->exec($sql);

    // 3. Commit de wijzigingen
    $pdo->commit();

    echo "Database succesvol geüpdatet!";
} catch(PDOException $e) {
    // Rollback bij fouten
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?> 