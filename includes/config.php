<?php
// Database configuratie
// define('DB_SERVER', '');
// define('DB_USERNAME', '');
// define('DB_PASSWORD', '');
// define('DB_NAME', '');

// Database verbinding maken
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?> 

Email: admin@rijvaardig.nl
Wachtwoord: admin123
