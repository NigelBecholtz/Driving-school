<?php
require_once "includes/config.php";

try {
    // Voeg lessons_amount kolom toe aan packages tabel
    $sql = "ALTER TABLE packages 
            ADD COLUMN IF NOT EXISTS lessons_amount INT NOT NULL DEFAULT 0";
    $pdo->exec($sql);

    // Update bestaande pakketten met standaard aantal lessen
    $sql = "UPDATE packages 
            SET lessons_amount = 
            CASE 
                WHEN name LIKE '%Basis%' THEN 10
                WHEN name LIKE '%Uitgebreid%' THEN 20
                WHEN name LIKE '%Compleet%' THEN 30
                ELSE 15
            END";
    $pdo->exec($sql);

    echo "Database succesvol geüpdatet!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 