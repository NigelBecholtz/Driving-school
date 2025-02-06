<?php
require_once "includes/config.php";

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Check of de kolom al bestaat
    $sql = "SHOW COLUMNS FROM packages LIKE 'lessons_amount'";
    $result = $pdo->query($sql)->fetch();
    
    if (!$result) {
        // 1. Voeg lessons_amount kolom toe aan packages tabel
        $sql = "ALTER TABLE packages 
                ADD COLUMN lessons_amount INT NOT NULL DEFAULT 0";
        $pdo->exec($sql);
        echo "Kolom lessons_amount toegevoegd.<br>";
    }

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

    // 3. Controleer of de update is gelukt
    $sql = "SELECT name, lessons_amount FROM packages";
    $result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Huidige pakket configuratie:<br>";
    foreach ($result as $row) {
        echo $row['name'] . ": " . $row['lessons_amount'] . " lessen<br>";
    }

    // Voeg notes en rating kolommen toe aan lesson_bookings tabel
    $sql = "ALTER TABLE lesson_bookings 
            ADD COLUMN IF NOT EXISTS notes TEXT,
            ADD COLUMN IF NOT EXISTS rating INT";
    $pdo->exec($sql);

    // 1. Maak de student_progress tabel aan
    $sql = "CREATE TABLE IF NOT EXISTS student_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        instructor_id INT NOT NULL,
        category VARCHAR(50) NOT NULL,
        topic VARCHAR(100) NOT NULL,
        proficiency_level INT NOT NULL DEFAULT 0,
        notes TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES users(id),
        FOREIGN KEY (instructor_id) REFERENCES users(id)
    )";
    $pdo->exec($sql);

    // 2. Functie om onderwerpen toe te voegen voor een student
    function addTopicsForStudent($pdo, $student_id, $instructor_id) {
        $topics = [
            'Voertuigcontrole' => [
                'Algemene voertuigcontrole',
                'Banden en wielen',
                'Verlichting en signalen'
            ],
            'Verkeersdeelname' => [
                'Wegrijden',
                'Rijden op rechte wegen',
                'Bochten',
                'Kruispunten',
                'Invoegen en uitvoegen',
                'Inhalen en rijstrookwisseling',
                'Gedrag bij verkeerslichten',
                'Rotondes'
            ],
            'Bijzondere verrichtingen' => [
                'Achteruit rijden',
                'Keren',
                'Parkeren',
                'Hellingproef'
            ],
            'Bijzondere weggedeelten' => [
                'Erf',
                'Auto(snel)weg',
                'Bebouwde kom'
            ],
            'Verantwoord rijgedrag' => [
                'Defensief rijden',
                'Economisch rijden',
                'Sociaal rijgedrag',
                'Zelfreflectie'
            ]
        ];

        $sql = "INSERT INTO student_progress 
                (student_id, instructor_id, category, topic, proficiency_level) 
                VALUES (:student_id, :instructor_id, :category, :topic, 0)";
        $stmt = $pdo->prepare($sql);

        foreach ($topics as $category => $categoryTopics) {
            foreach ($categoryTopics as $topic) {
                $stmt->execute([
                    ':student_id' => $student_id,
                    ':instructor_id' => $instructor_id,
                    ':category' => $category,
                    ':topic' => $topic
                ]);
            }
        }
    }

    // 3. Voeg onderwerpen toe voor de specifieke student
    $student_id = 23; // ID van student@test.nl
    $instructor_id = 25; // ID van instructeur@test.nl
    addTopicsForStudent($pdo, $student_id, $instructor_id);

    // Maak tabellen voor categorieën en onderwerpen
    $sql = "CREATE TABLE IF NOT EXISTS progress_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);

    $sql = "CREATE TABLE IF NOT EXISTS progress_topics (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES progress_categories(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);

    // Voeg standaard CBR categorieën en onderwerpen toe
    $categories = [
        'Voertuigcontrole' => [
            'Algemene voertuigcontrole' => 'Controle van vloeistoffen, banden, verlichting etc.',
            'Banden en wielen' => 'Controle van bandenspanning, profieldiepte en wielmoeren',
            'Verlichting en signalen' => 'Controle van alle lichten en richtingaanwijzers'
        ],
        'Verkeersdeelname' => [
            'Wegrijden' => 'Veilig wegrijden en invoegen in het verkeer',
            'Rijden op rechte wegen' => 'Positie op de weg, snelheid en afstand houden',
            'Bochten' => 'Techniek en snelheid in verschillende soorten bochten',
            'Kruispunten' => 'Voorrang verlenen en krijgen, oversteken',
            'Invoegen en uitvoegen' => 'Techniek bij op- en afritten',
            'Inhalen en rijstrookwisseling' => 'Veilig inhalen en van rijstrook wisselen',
            'Gedrag bij verkeerslichten' => 'Anticiperen op verkeerslichten en kruispunten',
            'Rotondes' => 'Techniek en voorrangsregels op rotondes'
        ],
        'Bijzondere verrichtingen' => [
            'Achteruit rijden' => 'Verschillende technieken voor achteruit rijden',
            'Keren' => 'Veilig keren op de weg en in een straat',
            'Parkeren' => 'File-, haaks- en achteruit parkeren',
            'Hellingproef' => 'Wegrijden op een helling zonder terugrollen'
        ],
        'Bijzondere weggedeelten' => [
            'Erf' => 'Rijden in woonerven en shared spaces',
            'Auto(snel)weg' => 'Rijden op autowegen en snelwegen',
            'Bebouwde kom' => 'Rijden binnen de bebouwde kom'
        ],
        'Verantwoord rijgedrag' => [
            'Defensief rijden' => 'Anticiperen en gevaren herkennen',
            'Economisch rijden' => 'Zuinig en milieubewust rijden',
            'Sociaal rijgedrag' => 'Rekening houden met andere weggebruikers',
            'Zelfreflectie' => 'Eigen rijgedrag kunnen beoordelen en bijsturen'
        ]
    ];

    foreach ($categories as $categoryName => $topics) {
        // Voeg categorie toe
        $sql = "INSERT INTO progress_categories (name) VALUES (:name)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':name' => $categoryName]);
        $categoryId = $pdo->lastInsertId();

        // Voeg onderwerpen toe
        $sql = "INSERT INTO progress_topics (category_id, name, description) VALUES (:category_id, :name, :description)";
        $stmt = $pdo->prepare($sql);
        
        foreach ($topics as $topicName => $description) {
            $stmt->execute([
                ':category_id' => $categoryId,
                ':name' => $topicName,
                ':description' => $description
            ]);
        }
    }

    // 4. Commit de wijzigingen
    $pdo->commit();
    echo "<br>Database succesvol geüpdatet!";

} catch(PDOException $e) {
    // Rollback bij fouten
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?> 