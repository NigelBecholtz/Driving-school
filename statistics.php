<?php
session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin"){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Haal gebruikersstatistieken op
$sql = "SELECT 
            COUNT(*) as total_users,
            COUNT(CASE WHEN role = 'student' THEN 1 END) as total_students,
            COUNT(CASE WHEN role = 'instructor' THEN 1 END) as total_instructors
        FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$user_stats = $stmt->fetch();

// Haal lesstatistieken op
$sql = "SELECT 
            COUNT(*) as total_lessons,
            COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as completed_lessons,
            COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_lessons,
            COUNT(CASE WHEN booking_date >= CURDATE() THEN 1 END) as upcoming_lessons
        FROM lesson_bookings";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$lesson_stats = $stmt->fetch();

// Haal pakketstatistieken op
$sql = "SELECT 
            p.type,
            p.name,
            COUNT(po.id) as total_orders,
            COUNT(CASE WHEN po.status = 'confirmed' THEN 1 END) as confirmed_orders
        FROM packages p
        LEFT JOIN package_orders po ON p.id = po.package_id
        GROUP BY p.id
        ORDER BY total_orders DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$package_stats = $stmt->fetchAll();

// Haal top instructeurs op
$sql = "SELECT 
            u.first_name,
            u.last_name,
            COUNT(lb.id) as total_lessons,
            COUNT(CASE WHEN lb.status = 'confirmed' THEN 1 END) as completed_lessons
        FROM users u
        LEFT JOIN instructor_schedules ins ON u.id = ins.instructor_id
        LEFT JOIN lesson_bookings lb ON ins.id = lb.schedule_id
        WHERE u.role = 'instructor'
        GROUP BY u.id
        ORDER BY completed_lessons DESC
        LIMIT 5";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$instructor_stats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistieken - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Statistieken</h1>

        <!-- Algemene Statistieken -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat bg-base-100 rounded-lg shadow">
                <div class="stat-figure text-primary">
                    <i class="fas fa-users text-3xl"></i>
                </div>
                <div class="stat-title">Totaal Gebruikers</div>
                <div class="stat-value"><?php echo $user_stats['total_users']; ?></div>
                <div class="stat-desc">
                    <?php echo $user_stats['total_students']; ?> studenten, 
                    <?php echo $user_stats['total_instructors']; ?> instructeurs
                </div>
            </div>

            <div class="stat bg-base-100 rounded-lg shadow">
                <div class="stat-figure text-secondary">
                    <i class="fas fa-calendar-check text-3xl"></i>
                </div>
                <div class="stat-title">Totaal Lessen</div>
                <div class="stat-value"><?php echo $lesson_stats['total_lessons']; ?></div>
                <div class="stat-desc">
                    <?php echo $lesson_stats['completed_lessons']; ?> afgerond, 
                    <?php echo $lesson_stats['upcoming_lessons']; ?> gepland
                </div>
            </div>

            <div class="stat bg-base-100 rounded-lg shadow">
                <div class="stat-figure text-accent">
                    <i class="fas fa-graduation-cap text-3xl"></i>
                </div>
                <div class="stat-title">Slagingspercentage</div>
                <div class="stat-value">
                    <?php 
                    $success_rate = $lesson_stats['completed_lessons'] > 0 ? 
                        round(($lesson_stats['completed_lessons'] - $lesson_stats['cancelled_lessons']) / 
                        $lesson_stats['completed_lessons'] * 100) : 0;
                    echo $success_rate . '%';
                    ?>
                </div>
                <div class="stat-desc"><?php echo $lesson_stats['cancelled_lessons']; ?> geannuleerde lessen</div>
            </div>

            <div class="stat bg-base-100 rounded-lg shadow">
                <div class="stat-figure text-info">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
                <div class="stat-title">Gemiddelde Bezetting</div>
                <div class="stat-value">
                    <?php 
                    $occupancy_rate = $user_stats['total_instructors'] > 0 ? 
                        round($lesson_stats['upcoming_lessons'] / ($user_stats['total_instructors'] * 8) * 100) : 0;
                    echo $occupancy_rate . '%';
                    ?>
                </div>
                <div class="stat-desc">van beschikbare lesplekken</div>
            </div>
        </div>

        <!-- Pakketstatistieken -->
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <h2 class="text-2xl font-bold mb-6">Pakketstatistieken</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Pakket</th>
                                <th>Type</th>
                                <th>Totaal Verkocht</th>
                                <th>Bevestigde Orders</th>
                                <th>Conversie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($package_stats as $stat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($stat['name']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $stat['type'] === 'auto' ? 
                                            'badge-primary' : 'badge-secondary'; ?>">
                                            <?php echo ucfirst($stat['type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $stat['total_orders']; ?></td>
                                    <td><?php echo $stat['confirmed_orders']; ?></td>
                                    <td>
                                        <?php 
                                        $conversion = $stat['total_orders'] > 0 ? 
                                            round(($stat['confirmed_orders'] / $stat['total_orders']) * 100) : 0;
                                        echo $conversion . '%';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Instructeurs -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="text-2xl font-bold mb-6">Top Instructeurs</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Instructeur</th>
                                <th>Totaal Lessen</th>
                                <th>Afgeronde Lessen</th>
                                <th>Voltooiingspercentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($instructor_stats as $stat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($stat['first_name'] . ' ' . $stat['last_name']); ?></td>
                                    <td><?php echo $stat['total_lessons']; ?></td>
                                    <td><?php echo $stat['completed_lessons']; ?></td>
                                    <td>
                                        <?php 
                                        $completion = $stat['total_lessons'] > 0 ? 
                                            round(($stat['completed_lessons'] / $stat['total_lessons']) * 100) : 0;
                                        echo $completion . '%';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 