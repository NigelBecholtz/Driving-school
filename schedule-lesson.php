<?php
session_start();

// Check if user is logged in and is instructor
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "instructor"){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Haal student informatie op
if(!isset($_GET['student_id'])) {
    header("location: my-students.php");
    exit;
}

$sql = "SELECT u.*, po.id as order_id, p.name as package_name, p.lessons_amount, 
        (SELECT COUNT(*) FROM lesson_bookings WHERE order_id = po.id) as booked_lessons
        FROM users u
        JOIN package_orders po ON u.id = po.user_id
        JOIN packages p ON po.package_id = p.id
        WHERE u.id = :student_id AND po.status = 'confirmed'
        AND u.id IN (
            SELECT DISTINCT student_id 
            FROM lesson_bookings lb
            JOIN instructor_schedules ins ON lb.schedule_id = ins.id
            WHERE ins.instructor_id = :instructor_id
        )";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $_GET['student_id'], PDO::PARAM_INT);
$stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$student = $stmt->fetch();

if(!$student) {
    header("location: my-students.php");
    exit;
}

// Verwerk lesaanvraag
if($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Controleer of er nog lessen beschikbaar zijn in het pakket
        if($student['booked_lessons'] >= $student['lessons_amount']) {
            throw new Exception("Het lespakket is vol. Er kunnen geen nieuwe lessen worden ingepland.");
        }

        // Controleer of het tijdslot beschikbaar is
        $sql = "SELECT id FROM instructor_schedules 
                WHERE instructor_id = :instructor_id 
                AND day_of_week = :day_of_week 
                AND start_time = :start_time
                AND id NOT IN (
                    SELECT schedule_id FROM lesson_bookings 
                    WHERE booking_date = :booking_date
                    AND status != 'cancelled'
                )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
        $stmt->bindParam(":day_of_week", $_POST['day_of_week']);
        $stmt->bindParam(":start_time", $_POST['start_time']);
        $stmt->bindParam(":booking_date", $_POST['booking_date']);
        $stmt->execute();
        $schedule = $stmt->fetch();

        if(!$schedule) {
            throw new Exception("Dit tijdslot is niet meer beschikbaar.");
        }

        // Plan de les in
        $sql = "INSERT INTO lesson_bookings (student_id, schedule_id, order_id, booking_date, status) 
                VALUES (:student_id, :schedule_id, :order_id, :booking_date, 'confirmed')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":student_id", $student['id'], PDO::PARAM_INT);
        $stmt->bindParam(":schedule_id", $schedule['id'], PDO::PARAM_INT);
        $stmt->bindParam(":order_id", $student['order_id'], PDO::PARAM_INT);
        $stmt->bindParam(":booking_date", $_POST['booking_date']);
        $stmt->execute();

        $success_message = "De les is succesvol ingepland.";
        
        // Update aantal geboekte lessen
        $student['booked_lessons']++;
        
    } catch(Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Haal beschikbare tijdsloten op
$sql = "SELECT day_of_week, start_time, end_time 
        FROM instructor_schedules 
        WHERE instructor_id = :instructor_id
        ORDER BY 
        CASE day_of_week 
            WHEN 'monday' THEN 1 
            WHEN 'tuesday' THEN 2 
            WHEN 'wednesday' THEN 3 
            WHEN 'thursday' THEN 4 
            WHEN 'friday' THEN 5 
            WHEN 'saturday' THEN 6 
            WHEN 'sunday' THEN 7 
        END, 
        start_time";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$available_slots = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Inplannen - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold">Les Inplannen</h1>
            <a href="my-students.php" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar Studenten
            </a>
        </div>

        <?php if(isset($error_message)): ?>
            <div class="alert alert-error mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Student informatie -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Student Informatie</h2>
                    <div class="space-y-4">
                        <div>
                            <div class="font-semibold">Naam</div>
                            <div><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></div>
                        </div>
                        <div>
                            <div class="font-semibold">Pakket</div>
                            <div><?php echo htmlspecialchars($student['package_name']); ?></div>
                        </div>
                        <div>
                            <div class="font-semibold">Lessen</div>
                            <div class="flex items-center gap-2">
                                <progress class="progress progress-primary w-full" 
                                        value="<?php echo $student['booked_lessons']; ?>" 
                                        max="<?php echo $student['lessons_amount']; ?>">
                                </progress>
                                <span class="text-sm">
                                    <?php echo $student['booked_lessons']; ?>/<?php echo $student['lessons_amount']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Les inplannen -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Les Inplannen</h2>
                    
                    <?php if($student['booked_lessons'] >= $student['lessons_amount']): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Alle lessen uit het pakket zijn ingepland.</span>
                        </div>
                    <?php else: ?>
                        <form method="POST" class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Datum</span>
                                </label>
                                <input type="date" name="booking_date" class="input input-bordered" 
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Tijdslot</span>
                                </label>
                                <input type="hidden" name="day_of_week">
                                <input type="hidden" name="start_time">
                                <select name="time_slot" class="select select-bordered" required>
                                    <option value="">Selecteer een tijdslot</option>
                                    <?php foreach($available_slots as $slot): ?>
                                        <option value="<?php echo $slot['day_of_week'] . '|' . $slot['start_time']; ?>">
                                            <?php 
                                            echo getDutchDayName($slot['day_of_week']) . ' ' .
                                                 date('H:i', strtotime($slot['start_time'])) . ' - ' .
                                                 date('H:i', strtotime($slot['end_time'])); 
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-control mt-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus mr-2"></i>
                                    Les Inplannen
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    // Update hidden fields when time slot is selected
    document.querySelector('select[name="time_slot"]').addEventListener('change', function() {
        const [dayOfWeek, startTime] = this.value.split('|');
        document.querySelector('input[name="day_of_week"]').value = dayOfWeek;
        document.querySelector('input[name="start_time"]').value = startTime;
    });

    // Validate date and day match before submitting
    document.querySelector('form').addEventListener('submit', function(e) {
        const date = new Date(document.querySelector('input[name="booking_date"]').value);
        const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const selectedDay = document.querySelector('input[name="day_of_week"]').value;
        
        if(!date || !selectedDay) {
            e.preventDefault();
            alert('Selecteer een datum en tijdslot.');
            return;
        }
        
        if(dayNames[date.getDay()] !== selectedDay) {
            e.preventDefault();
            alert('De geselecteerde dag komt niet overeen met de datum. Kies een andere datum of tijdslot.');
        }
    });
    </script>
</body>
</html> 