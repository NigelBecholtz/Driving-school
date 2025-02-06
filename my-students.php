<?php
session_start();

// Check if user is logged in and is instructor
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "instructor"){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Haal alle studenten op van deze instructeur
$sql = "SELECT DISTINCT 
            u.id,
            u.first_name,
            u.last_name,
            u.email,
            u.phone,
            p.name as package_name,
            p.type as package_type,
            COUNT(lb.id) as total_lessons,
            COUNT(CASE WHEN lb.status = 'confirmed' THEN 1 END) as completed_lessons,
            COUNT(CASE WHEN lb.booking_date >= CURDATE() THEN 1 END) as upcoming_lessons
        FROM users u
        JOIN lesson_bookings lb ON u.id = lb.student_id
        JOIN instructor_schedules ins ON lb.schedule_id = ins.id
        JOIN package_orders po ON lb.order_id = po.id
        JOIN packages p ON po.package_id = p.id
        WHERE ins.instructor_id = :instructor_id
        AND u.role = 'student'
        GROUP BY u.id
        ORDER BY u.last_name, u.first_name";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();

// Haal aankomende lessen op voor elke student
function getUpcomingLessons($pdo, $student_id, $instructor_id) {
    $sql = "SELECT 
                lb.booking_date,
                ins.start_time,
                ins.end_time,
                lb.status
            FROM lesson_bookings lb
            JOIN instructor_schedules ins ON lb.schedule_id = ins.id
            WHERE lb.student_id = :student_id 
            AND ins.instructor_id = :instructor_id
            AND lb.booking_date >= CURDATE()
            AND lb.status != 'cancelled'
            ORDER BY lb.booking_date ASC, ins.start_time ASC
            LIMIT 3";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->bindParam(":instructor_id", $instructor_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Studenten - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Mijn Studenten</h1>

        <?php if(empty($students)): ?>
            <div class="alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Je hebt momenteel geen studenten.</span>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($students as $student): ?>
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title">
                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                <span class="badge <?php echo $student['package_type'] === 'auto' ? 
                                    'badge-primary' : 'badge-secondary'; ?>">
                                    <?php echo ucfirst($student['package_type']); ?>
                                </span>
                            </h2>
                            
                            <div class="space-y-2">
                                <p class="text-sm">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <a href="mailto:<?php echo htmlspecialchars($student['email']); ?>" 
                                       class="link link-hover">
                                        <?php echo htmlspecialchars($student['email']); ?>
                                    </a>
                                </p>
                                <p class="text-sm">
                                    <i class="fas fa-phone mr-2"></i>
                                    <a href="tel:<?php echo htmlspecialchars($student['phone']); ?>" 
                                       class="link link-hover">
                                        <?php echo htmlspecialchars($student['phone']); ?>
                                    </a>
                                </p>
                                <p class="text-sm">
                                    <i class="fas fa-box mr-2"></i>
                                    <?php echo htmlspecialchars($student['package_name']); ?>
                                </p>
                            </div>

                            <div class="divider"></div>

                            <div class="stats stats-vertical shadow">
                                <div class="stat">
                                    <div class="stat-title">Totaal Lessen</div>
                                    <div class="stat-value"><?php echo $student['total_lessons']; ?></div>
                                    <div class="stat-desc">
                                        <?php echo $student['completed_lessons']; ?> afgerond, 
                                        <?php echo $student['upcoming_lessons']; ?> gepland
                                    </div>
                                </div>
                            </div>

                            <?php 
                            $upcoming_lessons = getUpcomingLessons($pdo, $student['id'], $_SESSION['id']);
                            if(!empty($upcoming_lessons)): 
                            ?>
                                <div class="divider">Aankomende Lessen</div>
                                <ul class="space-y-2">
                                    <?php foreach($upcoming_lessons as $lesson): ?>
                                        <li class="text-sm">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            <?php 
                                            echo date('d-m-Y', strtotime($lesson['booking_date'])) . ' ' .
                                                 date('H:i', strtotime($lesson['start_time'])) . ' - ' .
                                                 date('H:i', strtotime($lesson['end_time'])); 
                                            ?>
                                            <span class="badge badge-sm <?php echo $lesson['status'] === 'confirmed' ? 
                                                'badge-success' : 'badge-warning'; ?>">
                                                <?php echo $lesson['status'] === 'confirmed' ? 'Bevestigd' : 'In afwachting'; ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <div class="card-actions justify-end mt-4">
                                <a href="student-progress.php?id=<?php echo $student['id']; ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Bekijk Voortgang
                                </a>
                                <a href="schedule-lesson.php?student_id=<?php echo $student['id']; ?>" 
                                   class="btn btn-secondary btn-sm">
                                    <i class="fas fa-calendar-plus mr-2"></i>
                                    Plan Les
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 