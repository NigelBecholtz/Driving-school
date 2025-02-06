<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file to get user role
require_once "includes/config.php";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Welkom <?php echo htmlspecialchars($_SESSION["email"]); ?></h1>
        
        <?php if($_SESSION["role"] === "student"): ?>
            <!-- Student Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Mijn Lessen</h2>
                        <p>Bekijk je geplande rijlessen en geschiedenis.</p>
                        <div class="card-actions justify-end">
                            <a href="my-lessons.php" class="btn btn-primary">Bekijk Lessen</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Mijn Bestellingen</h2>
                        <p>Bekijk je pakketbestellingen.</p>
                        <div class="card-actions justify-end">
                            <a href="my-orders.php" class="btn btn-primary">Bekijk Bestellingen</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Mijn Facturen</h2>
                        <p>Bekijk en download je facturen.</p>
                        <div class="card-actions justify-end">
                            <a href="my-invoices.php" class="btn btn-primary">Bekijk Facturen</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Theorie Oefenen</h2>
                        <p>Oefen je theoriekennis.</p>
                        <div class="card-actions justify-end">
                            <a href="theory-practice.php" class="btn btn-primary">Start Oefenen</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif($_SESSION["role"] === "instructor"): ?>
            <!-- Instructor Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Mijn Rooster</h2>
                        <p>Bekijk en beheer je lesrooster.</p>
                        <div class="card-actions justify-end">
                            <a href="instructor-schedule.php" class="btn btn-primary">Bekijk Rooster</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Les Inplannen</h2>
                        <p>Plan een nieuwe rijles in voor een student.</p>
                        <div class="card-actions justify-end">
                            <a href="schedule-lesson.php" class="btn btn-primary">Plan Les</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Mijn Studenten</h2>
                        <p>Bekijk en beheer je studenten.</p>
                        <div class="card-actions justify-end">
                            <a href="my-students.php" class="btn btn-primary">Bekijk Studenten</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Voortgangsrapporten</h2>
                        <p>Maak en bekijk voortgangsrapporten.</p>
                        <div class="card-actions justify-end">
                            <a href="progress-reports.php" class="btn btn-primary">Bekijk Rapporten</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif($_SESSION["role"] === "admin"): ?>
            <!-- Admin Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Gebruikersbeheer</h2>
                        <p>Beheer alle gebruikers van het systeem.</p>
                        <div class="card-actions justify-end">
                            <a href="user-management.php" class="btn btn-primary">Beheer Gebruikers</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Pakketbeheer</h2>
                        <p>Beheer de beschikbare lespakketten.</p>
                        <div class="card-actions justify-end">
                            <a href="package-management.php" class="btn btn-primary">Beheer Pakketten</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Financieel Overzicht</h2>
                        <p>Bekijk omzet, facturen en betalingen.</p>
                        <div class="card-actions justify-end">
                            <a href="financial-overview.php" class="btn btn-primary">Bekijk Overzicht</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Instructeursbeheer</h2>
                        <p>Beheer instructeurs en hun roosters.</p>
                        <div class="card-actions justify-end">
                            <a href="instructor-management.php" class="btn btn-primary">Beheer Instructeurs</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Systeeminstellingen</h2>
                        <p>Beheer algemene systeeminstellingen.</p>
                        <div class="card-actions justify-end">
                            <a href="system-settings.php" class="btn btn-primary">Beheer Instellingen</a>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Statistieken</h2>
                        <p>Bekijk systeem- en gebruiksstatistieken.</p>
                        <div class="card-actions justify-end">
                            <a href="statistics.php" class="btn btn-primary">Bekijk Statistieken</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-8 text-center">
            <a href="profile.php" class="btn btn-primary mr-4">Mijn Profiel</a>
            <a href="logout.php" class="btn btn-error">Uitloggen</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 