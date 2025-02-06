<?php
session_start();

// Check if user is logged in and is instructor
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "instructor"){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Helper functie voor Nederlandse dagnamen
function getDutchDayName($day) {
    $days = [
        'monday' => 'Maandag',
        'tuesday' => 'Dinsdag',
        'wednesday' => 'Woensdag',
        'thursday' => 'Donderdag',
        'friday' => 'Vrijdag',
        'saturday' => 'Zaterdag',
        'sunday' => 'Zondag'
    ];
    return $days[strtolower($day)];
}

// Verwerk nieuwe les toevoegen
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_lesson'])) {
    try {
        $pdo->beginTransaction();

        // Voeg les toe aan rooster
        $sql = "INSERT INTO instructor_schedules (instructor_id, day_of_week, start_time, end_time) 
                VALUES (:instructor_id, :day_of_week, :start_time, :end_time)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
        $stmt->bindParam(":day_of_week", $_POST['day_of_week']);
        $stmt->bindParam(":start_time", $_POST['start_time']);
        $stmt->bindParam(":end_time", $_POST['end_time']);
        $stmt->execute();

        $pdo->commit();
        $success_message = "Nieuwe les is succesvol toegevoegd aan je rooster.";
        
        // Refresh de pagina om de nieuwe les te tonen
        header("Location: manage-lessons.php?success=1");
        exit;
    } catch(Exception $e) {
        $pdo->rollBack();
        $error_message = "Er is een fout opgetreden bij het toevoegen van de les.";
    }
}

// Verwerk student toevoegen aan les
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    try {
        $pdo->beginTransaction();

        // Controleer of student nog lessen heeft
        $sql = "SELECT po.id as order_id, p.lessons_amount,
                COUNT(lb.id) as booked_lessons
                FROM package_orders po
                JOIN packages p ON po.package_id = p.id
                LEFT JOIN lesson_bookings lb ON po.id = lb.order_id
                WHERE po.user_id = :student_id
                AND po.status = 'confirmed'
                GROUP BY po.id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":student_id", $_POST['student_id'], PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch();

        if($order['booked_lessons'] >= $order['lessons_amount']) {
            throw new Exception("Deze student heeft geen lessen meer beschikbaar.");
        }

        // Voeg student toe aan les
        $sql = "INSERT INTO lesson_bookings (student_id, schedule_id, order_id, booking_date, status) 
                VALUES (:student_id, :schedule_id, :order_id, :booking_date, 'confirmed')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":student_id", $_POST['student_id'], PDO::PARAM_INT);
        $stmt->bindParam(":schedule_id", $_POST['schedule_id'], PDO::PARAM_INT);
        $stmt->bindParam(":order_id", $order['order_id'], PDO::PARAM_INT);
        $stmt->bindParam(":booking_date", $_POST['booking_date']);
        $stmt->execute();

        $pdo->commit();
        $success_message = "Student is succesvol toegevoegd aan de les.";
        
        header("Location: manage-lessons.php?success=2");
        exit;
    } catch(Exception $e) {
        $pdo->rollBack();
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lessen Beheren - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Lessen Beheren</h1>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle"></i>
                <span>
                    <?php 
                    echo $_GET['success'] == 1 ? 
                        "Nieuwe les is succesvol toegevoegd." : 
                        "Student is succesvol toegevoegd aan de les.";
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if(isset($error_message)): ?>
            <div class="alert alert-error mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Nieuwe les toevoegen -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Nieuwe Les Toevoegen</h2>
                    <form method="POST" class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Dag</span>
                            </label>
                            <select name="day_of_week" class="select select-bordered" required>
                                <option value="">Selecteer een dag</option>
                                <option value="monday">Maandag</option>
                                <option value="tuesday">Dinsdag</option>
                                <option value="wednesday">Woensdag</option>
                                <option value="thursday">Donderdag</option>
                                <option value="friday">Vrijdag</option>
                                <option value="saturday">Zaterdag</option>
                                <option value="sunday">Zondag</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Starttijd</span>
                                </label>
                                <input type="time" name="start_time" class="input input-bordered" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Eindtijd</span>
                                </label>
                                <input type="time" name="end_time" class="input input-bordered" required>
                            </div>
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" name="add_lesson" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>
                                Les Toevoegen
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Student aan les toevoegen -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Student aan Les Toevoegen</h2>
                    <form method="POST" class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Student</span>
                            </label>
                            <select name="student_id" class="select select-bordered" required>
                                <option value="">Selecteer een student</option>
                                <?php foreach($available_students as $student): ?>
                                    <option value="<?php echo $student['id']; ?>">
                                        <?php 
                                        echo htmlspecialchars(
                                            $student['first_name'] . ' ' . $student['last_name'] . 
                                            ' (' . $student['remaining_lessons'] . ' lessen over)'
                                        ); 
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Les</span>
                            </label>
                            <select name="schedule_id" class="select select-bordered" required>
                                <option value="">Selecteer een les</option>
                                <?php foreach($schedule_slots as $slot): ?>
                                    <option value="<?php echo $slot['id']; ?>">
                                        <?php 
                                        echo getDutchDayName($slot['day_of_week']) . ' ' .
                                             date('H:i', strtotime($slot['start_time'])) . ' - ' .
                                             date('H:i', strtotime($slot['end_time'])); 
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Datum</span>
                            </label>
                            <input type="date" name="booking_date" class="input input-bordered" 
                                   min="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" name="add_student" class="btn btn-primary">
                                <i class="fas fa-user-plus mr-2"></i>
                                Student Toevoegen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    // Valideer dat de datum overeenkomt met de geselecteerde les
    document.querySelector('form[name="add_student"]').addEventListener('submit', function(e) {
        const scheduleSelect = document.querySelector('select[name="schedule_id"]');
        const selectedOption = scheduleSelect.options[scheduleSelect.selectedIndex];
        const dayOfWeek = selectedOption.textContent.split(' ')[0].toLowerCase();
        const date = new Date(document.querySelector('input[name="booking_date"]').value);
        const dayNames = {
            'zondag': 0, 'maandag': 1, 'dinsdag': 2, 'woensdag': 3, 
            'donderdag': 4, 'vrijdag': 5, 'zaterdag': 6
        };
        
        if(date.getDay() !== dayNames[dayOfWeek]) {
            e.preventDefault();
            alert('De geselecteerde datum komt niet overeen met de lesdag. Kies een andere datum.');
        }
    });
    </script>
</body>
</html> 