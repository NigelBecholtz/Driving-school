<?php
// Database configuratie
define('DB_SERVER', 'database-5017150390.webspace-host.com');
define('DB_USERNAME', 'dbu2197313');
define('DB_PASSWORD', 'f#7Yr03f3');
define('DB_NAME', 'dbs13783541');

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