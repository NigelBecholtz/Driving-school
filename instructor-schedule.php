<?php
// Initialize the session
session_start();

// Check if user is logged in and is instructor
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "instructor"){
    header("location: login.php");
    exit;
}

// Include config file
require_once "includes/config.php";

// Haal weeknummer en jaar op uit URL of gebruik huidige week
$week = isset($_GET['week']) ? intval($_GET['week']) : date('W');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Bereken vorige en volgende week
$prevWeek = $week - 1;
$prevYear = $year;
$nextWeek = $week + 1;
$nextYear = $year;

if($prevWeek < 1) {
    $prevWeek = 52;
    $prevYear--;
}
if($nextWeek > 52) {
    $nextWeek = 1;
    $nextYear++;
}

// Haal alle lessen op voor deze week
$sql = "SELECT 
            lb.id as booking_id,
            lb.booking_date,
            lb.status,
            ins.start_time,
            ins.end_time,
            ins.day_of_week,
            u.first_name as student_first_name,
            u.last_name as student_last_name,
            u.phone as student_phone,
            p.name as package_name,
            p.type as package_type
        FROM instructor_schedules ins
        LEFT JOIN lesson_bookings lb ON ins.id = lb.schedule_id 
            AND WEEK(lb.booking_date, 1) = :week 
            AND YEAR(lb.booking_date) = :year
        LEFT JOIN users u ON lb.student_id = u.id
        LEFT JOIN package_orders po ON lb.order_id = po.id
        LEFT JOIN packages p ON po.package_id = p.id
        WHERE ins.instructor_id = :instructor_id
        ORDER BY ins.day_of_week, ins.start_time";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->bindParam(":week", $week, PDO::PARAM_INT);
$stmt->bindParam(":year", $year, PDO::PARAM_INT);
$stmt->execute();
$schedule = $stmt->fetchAll();

// Organiseer lessen per dag
$weekSchedule = [];
$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
foreach($days as $day) {
    $weekSchedule[$day] = array_filter($schedule, function($lesson) use ($day) {
        return strtolower($lesson['day_of_week']) === $day;
    });
}

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

// Bereken datums voor deze week
$dto = new DateTime();
$dto->setISODate($year, $week);
$weekDates = [];
foreach($days as $day) {
    $weekDates[$day] = $dto->format('Y-m-d');
    $dto->modify('+1 day');
}
?>

<!DOCTYPE html>
<html lang="nl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Rooster - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-300">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header met navigatie -->
        <div class="bg-base-200 rounded-box shadow-xl p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h1 class="text-4xl font-bold text-primary">Mijn Rooster</h1>
                
                <div class="flex gap-4 items-center">
                    <div class="join bg-base-300 rounded-lg">
                        <a href="?week=<?php echo $prevWeek; ?>&year=<?php echo $prevYear; ?>" 
                           class="join-item btn btn-ghost hover:bg-base-100">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="?week=<?php echo date('W'); ?>&year=<?php echo date('Y'); ?>" 
                           class="join-item btn btn-ghost hover:bg-base-100 font-bold">
                            Week <?php echo $week; ?>
                        </a>
                        <a href="?week=<?php echo $nextWeek; ?>&year=<?php echo $nextYear; ?>" 
                           class="join-item btn btn-ghost hover:bg-base-100">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <a href="manage-lessons.php" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Les Toevoegen
                    </a>
                </div>
            </div>
        </div>

        <!-- Rooster grid -->
        <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
            <?php foreach($weekSchedule as $day => $lessons): ?>
                <div class="bg-base-200 rounded-box shadow-xl overflow-hidden">
                    <!-- Dag header -->
                    <div class="bg-primary text-primary-content p-4">
                        <h2 class="font-bold text-center">
                            <?php echo getDutchDayName($day); ?>
                            <span class="text-sm font-normal block mt-1">
                                <?php echo date('d/m', strtotime($weekDates[$day])); ?>
                            </span>
                        </h2>
                    </div>

                    <!-- Lessen -->
                    <div class="divide-y divide-base-300">
                        <?php if(empty($lessons)): ?>
                            <div class="p-6 text-sm text-base-content/60 text-center italic">
                                Geen lessen
                            </div>
                        <?php else: ?>
                            <?php foreach($lessons as $lesson): ?>
                                <div class="p-4 hover:bg-base-300 transition-colors duration-200
                                            <?php echo $lesson['student_first_name'] ? 
                                                'border-l-4 border-primary' : 
                                                'border-l-4 border-secondary opacity-75'; ?>">
                                    <!-- Tijdslot -->
                                    <div class="font-bold text-lg text-primary">
                                        <?php 
                                        echo date('H:i', strtotime($lesson['start_time'])) . ' - ' . 
                                             date('H:i', strtotime($lesson['end_time'])); 
                                        ?>
                                    </div>

                                    <?php if($lesson['student_first_name']): ?>
                                        <!-- Student info -->
                                        <div class="mt-3 space-y-2">
                                            <div class="flex items-center gap-2 text-base-content/80">
                                                <i class="fas fa-user w-4"></i>
                                                <span class="truncate">
                                                    <?php echo htmlspecialchars(
                                                        $lesson['student_first_name'] . ' ' . 
                                                        $lesson['student_last_name']
                                                    ); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center gap-2 text-base-content/70">
                                                <i class="fas fa-car w-4"></i>
                                                <span class="truncate">
                                                    <?php echo htmlspecialchars($lesson['package_name']); ?>
                                                </span>
                                            </div>

                                            <!-- Status badge -->
                                            <div class="mt-2">
                                            <span class="badge badge-sm <?php 
                                                echo $lesson['status'] === 'confirmed' ? 
                                                    'badge-success' : 'badge-warning'; 
                                            ?>">
                                                <?php echo $lesson['status'] === 'confirmed' ? 
                                                    'Bevestigd' : 'In afwachting'; ?>
                                            </span>
                                            </div>
                                        </div>

                                        <!-- Contact knoppen -->
                                        <div class="mt-3 flex gap-2">
                                            <a href="tel:<?php echo $lesson['student_phone']; ?>" 
                                               class="btn btn-sm btn-ghost btn-circle hover:bg-success/20">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                            <button class="btn btn-sm btn-ghost btn-circle hover:bg-info/20"
                                                    onclick="showLessonDetails(<?php echo htmlspecialchars(json_encode($lesson)); ?>)">
                                                <i class="fas fa-info"></i>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-2 text-base-content/50 italic">
                                            Beschikbaar
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Les details modal -->
    <dialog id="lesson_details" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg" id="modal_title"></h3>
            <div class="py-4" id="modal_content"></div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Sluiten</button>
                </form>
            </div>
        </div>
    </dialog>

    <?php include 'includes/footer.php'; ?>

    <script>
    function showLessonDetails(lesson) {
        const modal = document.getElementById('lesson_details');
        const title = document.getElementById('modal_title');
        const content = document.getElementById('modal_content');

        title.textContent = `Les Details - ${lesson.student_first_name} ${lesson.student_last_name}`;
        content.innerHTML = `
            <div class="space-y-4">
                <div>
                    <div class="font-semibold">Datum & Tijd</div>
                    <div>${new Date(lesson.booking_date).toLocaleDateString('nl-NL')} ${lesson.start_time} - ${lesson.end_time}</div>
                </div>
                <div>
                    <div class="font-semibold">Contact</div>
                    <div>Tel: <a href="tel:${lesson.student_phone}" class="link">${lesson.student_phone}</a></div>
                </div>
                <div>
                    <div class="font-semibold">Pakket</div>
                    <div>${lesson.package_name} (${lesson.package_type})</div>
                </div>
                <div>
                    <div class="font-semibold">Status</div>
                    <div><span class="badge ${lesson.status === 'confirmed' ? 'badge-success' : 'badge-warning'}">${lesson.status === 'confirmed' ? 'Bevestigd' : 'In afwachting'}</span></div>
                </div>
            </div>
        `;

        modal.showModal();
    }
    </script>
</body>
</html> 