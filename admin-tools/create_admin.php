<?php
require_once "../includes/config.php";

$email = "admin@rijvaardig.nl";
$password = "admin123";
$role = "admin";

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
    echo "Wachtwoord: " . $password;
} else {
    echo "Er is iets misgegaan bij het aanmaken van het admin account.";
}
?> 