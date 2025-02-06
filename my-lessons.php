<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Fetch upcoming lessons
$sql = "SELECT lb.*, ins.start_time, ins.end_time, ins.day_of_week,
        u.first_name as instructor_first_name, u.last_name as instructor_last_name,
        u.phone as instructor_phone,
        po.package_id,
        p.name as package_name,
        p.type as package_type
        FROM lesson_bookings lb
        JOIN instructor_schedules ins ON lb.schedule_id = ins.id
        JOIN users u ON ins.instructor_id = u.id
        JOIN package_orders po ON lb.order_id = po.id
        JOIN packages p ON po.package_id = p.id
        WHERE lb.student_id = :student_id 
        AND lb.booking_date >= CURDATE()
        AND lb.status != 'cancelled'
        ORDER BY lb.booking_date ASC, ins.start_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $_SESSION['id'], PDO::PARAM_INT);

$stmt->execute();
$upcoming_lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch past lessons
$sql = "SELECT lb.*, ins.start_time, ins.end_time, ins.day_of_week,
        u.first_name as instructor_first_name, u.last_name as instructor_last_name,
        u.phone as instructor_phone,
        po.package_id,
        p.name as package_name,
        p.type as package_type
        FROM lesson_bookings lb
        JOIN instructor_schedules ins ON lb.schedule_id = ins.id
        JOIN users u ON ins.instructor_id = u.id
        JOIN package_orders po ON lb.order_id = po.id
        JOIN packages p ON po.package_id = p.id
        WHERE lb.student_id = :student_id 
        AND (lb.booking_date < CURDATE() OR lb.status = 'cancelled')
        ORDER BY lb.booking_date DESC, ins.start_time DESC
        LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$past_lessons = $stmt->fetchAll();

// Helper function to format date in Dutch
function formatDutchDate($date) {
    $months = array(
        1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
        5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
        9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december'
    );
    
    $days = array(
        'Monday' => 'Maandag', 'Tuesday' => 'Dinsdag', 'Wednesday' => 'Woensdag',
        'Thursday' => 'Donderdag', 'Friday' => 'Vrijdag', 'Saturday' => 'Zaterdag',
        'Sunday' => 'Zondag'
    );
    
    $timestamp = strtotime($date);
    $dayName = $days[date('l', $timestamp)];
    $day = date('j', $timestamp);
    $month = $months[date('n', $timestamp)];
    $year = date('Y', $timestamp);
    
    return "$dayName $day $month $year";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Lessen - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php 
    if (file_exists('includes/header.php')) {
        include 'includes/header.php';
    } else {
        echo '<div class="alert alert-error">Header file niet gevonden</div>';
    }
    ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Mijn Lessen</h1>

        <?php if(isset($_GET['cancelled'])): ?>
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>De les is succesvol geannuleerd.</span>
            </div>
        <?php endif; ?>

        <!-- Komende lessen -->
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <h2 class="text-2xl font-bold mb-6">Komende Lessen</h2>
                
                <?php if(empty($upcoming_lessons)): ?>
                    <div class="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Je hebt momenteel geen lessen gepland.</span>
                    </div>
                <?php else: ?>
                    <div class="grid gap-4">
                        <?php foreach($upcoming_lessons as $lesson): ?>
                            <div class="bg-base-200 p-6 rounded-lg hover:bg-base-300 transition-colors">
                                <div class="flex flex-col md:flex-row justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold mb-2">
                                            <?php echo formatDutchDate($lesson['booking_date']); ?>
                                        </h3>
                                        <p class="text-lg mb-2">
                                            <?php 
                                            echo date('H:i', strtotime($lesson['start_time'])) . ' - ' . 
                                                 date('H:i', strtotime($lesson['end_time'])); 
                                            ?>
                                        </p>
                                        <p class="text-gray-600">
                                            Instructeur: <?php echo htmlspecialchars($lesson['instructor_first_name'] . ' ' . $lesson['instructor_last_name']); ?>
                                        </p>
                                        <p class="text-gray-600">
                                            Pakket: <?php echo htmlspecialchars($lesson['package_name']); ?> (<?php echo ucfirst($lesson['package_type']); ?>)
                                        </p>
                                    </div>
                                    <div class="flex flex-col md:items-end gap-2">
                                        <a href="tel:<?php echo $lesson['instructor_phone']; ?>" 
                                           class="btn btn-outline btn-sm gap-2">
                                            <i class="fas fa-phone"></i>
                                            Contact Instructeur
                                        </a>
                                        <?php if(strtotime($lesson['booking_date']) > strtotime('+24 hours')): ?>
                                            <button onclick="showCancelModal(<?php echo $lesson['id']; ?>, '<?php echo formatDutchDate($lesson['booking_date']); ?>')" 
                                                    class="btn btn-error btn-sm gap-2">
                                                <i class="fas fa-times"></i>
                                                Annuleren
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Vorige lessen -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="text-2xl font-bold mb-6">Vorige Lessen</h2>
                
                <?php if(empty($past_lessons)): ?>
                    <div class="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Je hebt nog geen lessen gehad.</span>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Tijd</th>
                                    <th>Instructeur</th>
                                    <th>Pakket</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($past_lessons as $lesson): ?>
                                    <tr>
                                        <td><?php echo formatDutchDate($lesson['booking_date']); ?></td>
                                        <td>
                                            <?php 
                                            echo date('H:i', strtotime($lesson['start_time'])) . ' - ' . 
                                                 date('H:i', strtotime($lesson['end_time'])); 
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($lesson['instructor_first_name'] . ' ' . $lesson['instructor_last_name']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($lesson['package_name']); ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $badge_class = match($lesson['status']) {
                                                'confirmed' => 'badge-success',
                                                'cancelled' => 'badge-error',
                                                'pending' => 'badge-warning',
                                                default => 'badge-ghost'
                                            };
                                            $status_text = match($lesson['status']) {
                                                'confirmed' => 'Bevestigd',
                                                'cancelled' => 'Geannuleerd',
                                                'pending' => 'In afwachting',
                                                default => 'Onbekend'
                                            };
                                            ?>
                                            <span class="badge <?php echo $badge_class; ?>">
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <dialog id="cancel_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-error">Les Annuleren</h3>
            <p class="py-4">
                Weet je zeker dat je de les op <span id="cancel_date" class="font-semibold"></span> wilt annuleren?
            </p>
            <div class="modal-action">
                <form method="POST" action="cancel-lesson.php">
                    <input type="hidden" name="lesson_id" id="cancel_lesson_id">
                    <button type="submit" class="btn btn-error">Annuleren</button>
                </form>
                <button class="btn" onclick="document.getElementById('cancel_modal').close()">Terug</button>
            </div>
        </div>
    </dialog>

    <?php 
    if (file_exists('includes/footer.php')) {
        include 'includes/footer.php';
    }
    ?>

    <script>
    function showCancelModal(lessonId, lessonDate) {
        document.getElementById('cancel_lesson_id').value = lessonId;
        document.getElementById('cancel_date').textContent = lessonDate;
        document.getElementById('cancel_modal').showModal();
    }
    </script>
</body>
</html> 