<?php
session_start();

// Check if user is logged in and is instructor
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "instructor"){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Verwerk nieuwe beschikbaarheid toevoegen
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['add_availability'])) {
        $sql = "INSERT INTO instructor_schedules (instructor_id, day_of_week, start_time, end_time) 
                VALUES (:instructor_id, :day_of_week, :start_time, :end_time)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':instructor_id' => $_SESSION['id'],
            ':day_of_week' => $_POST['day_of_week'],
            ':start_time' => $_POST['start_time'],
            ':end_time' => $_POST['end_time']
        ]);
        $success_message = "Beschikbaarheid succesvol toegevoegd.";
    }
    elseif(isset($_POST['add_multiple'])) {
        $days = $_POST['days'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $sql = "INSERT INTO instructor_schedules (instructor_id, day_of_week, start_time, end_time) 
                VALUES (:instructor_id, :day_of_week, :start_time, :end_time)";
        $stmt = $pdo->prepare($sql);

        foreach($days as $day) {
            $stmt->execute([
                ':instructor_id' => $_SESSION['id'],
                ':day_of_week' => $day,
                ':start_time' => $start_time,
                ':end_time' => $end_time
            ]);
        }
        $success_message = "Beschikbaarheid succesvol toegevoegd.";
    }
    elseif(isset($_POST['delete_availability'])) {
        $sql = "DELETE FROM instructor_schedules WHERE id = :id AND instructor_id = :instructor_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $_POST['schedule_id'],
            ':instructor_id' => $_SESSION['id']
        ]);
        $success_message = "Beschikbaarheid succesvol verwijderd.";
    }
}

// Haal huidige beschikbaarheid op
$sql = "SELECT * FROM instructor_schedules 
        WHERE instructor_id = :instructor_id 
        ORDER BY CASE day_of_week 
            WHEN 'monday' THEN 1 
            WHEN 'tuesday' THEN 2 
            WHEN 'wednesday' THEN 3 
            WHEN 'thursday' THEN 4 
            WHEN 'friday' THEN 5 
            WHEN 'saturday' THEN 6 
            WHEN 'sunday' THEN 7 
        END, start_time";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$availabilities = $stmt->fetchAll();

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
?>

<!DOCTYPE html>
<html lang="nl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer Beschikbaarheid - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-300">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-primary">Beheer Beschikbaarheid</h1>
            <div class="flex gap-4">
                <button class="btn btn-secondary" onclick="copyToAllDays()">
                    <i class="fas fa-copy mr-2"></i>
                    Kopieer naar alle dagen
                </button>
                <button class="btn btn-primary" onclick="showQuickAdd()">
                    <i class="fas fa-plus mr-2"></i>
                    Snelle Toevoeging
                </button>
            </div>
        </div>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <!-- Week overzicht -->
        <div class="grid grid-cols-1 md:grid-cols-7 gap-4 mb-8">
            <?php 
            $days = [
                'monday' => 'Maandag',
                'tuesday' => 'Dinsdag',
                'wednesday' => 'Woensdag',
                'thursday' => 'Donderdag',
                'friday' => 'Vrijdag',
                'saturday' => 'Zaterdag',
                'sunday' => 'Zondag'
            ];
            
            foreach($days as $dayKey => $dayName): 
                $daySlots = array_filter($availabilities, function($slot) use ($dayKey) {
                    return $slot['day_of_week'] === $dayKey;
                });
            ?>
                <div class="card bg-base-200 shadow-xl">
                    <div class="card-body p-4">
                        <h2 class="card-title text-primary justify-between">
                            <?php echo $dayName; ?>
                            <button class="btn btn-circle btn-sm btn-ghost" 
                                    onclick="addSlot('<?php echo $dayKey; ?>')">
                                <i class="fas fa-plus"></i>
                            </button>
                        </h2>
                        <div class="space-y-2">
                            <?php foreach($daySlots as $slot): ?>
                                <div class="flex items-center gap-2 bg-base-300 p-2 rounded-lg group">
                                    <span class="flex-1 text-sm">
                                        <?php echo date('H:i', strtotime($slot['start_time'])); ?> - 
                                        <?php echo date('H:i', strtotime($slot['end_time'])); ?>
                                    </span>
                                    <button class="btn btn-ghost btn-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                            onclick="deleteSlot(<?php echo $slot['id']; ?>)">
                                        <i class="fas fa-trash text-error"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                            <?php if(empty($daySlots)): ?>
                                <div class="text-center text-base-content/50 text-sm italic py-2">
                                    Geen tijdsloten
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Veelvoorkomende tijden -->
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Veelvoorkomende Tijden</h2>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $commonTimes = [
                        ['09:00', '10:00'],
                        ['10:00', '11:00'],
                        ['11:00', '12:00'],
                        ['13:00', '14:00'],
                        ['14:00', '15:00'],
                        ['15:00', '16:00'],
                        ['16:00', '17:00']
                    ];
                    foreach($commonTimes as $time):
                    ?>
                        <button class="btn btn-sm btn-outline" 
                                onclick="useCommonTime('<?php echo $time[0]; ?>', '<?php echo $time[1]; ?>')">
                            <?php echo $time[0] . ' - ' . $time[1]; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal voor snelle toevoeging -->
    <dialog id="quick_add" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Snelle Toevoeging</h3>
            <form method="POST" class="py-4" id="quickAddForm">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Dagen</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach($days as $dayKey => $dayName): ?>
                            <label class="label cursor-pointer gap-2 bg-base-300 px-3 py-2 rounded-lg">
                                <span class="label-text"><?php echo $dayName; ?></span>
                                <input type="checkbox" name="days[]" value="<?php echo $dayKey; ?>" 
                                       class="checkbox checkbox-primary">
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Start Tijd</span>
                        </label>
                        <input type="time" name="start_time" id="quick_start_time" 
                               class="input input-bordered" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Eind Tijd</span>
                        </label>
                        <input type="time" name="end_time" id="quick_end_time" 
                               class="input input-bordered" required>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="submit" name="add_multiple" class="btn btn-primary">Toevoegen</button>
                    <button type="button" class="btn" onclick="closeQuickAdd()">Annuleren</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
    function showQuickAdd() {
        document.getElementById('quick_add').showModal();
    }

    function closeQuickAdd() {
        document.getElementById('quick_add').close();
    }

    function useCommonTime(start, end) {
        document.getElementById('quick_start_time').value = start;
        document.getElementById('quick_end_time').value = end;
        showQuickAdd();
    }

    function deleteSlot(id) {
        if(confirm('Weet je zeker dat je dit tijdslot wilt verwijderen?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="schedule_id" value="${id}">
                <input type="hidden" name="delete_availability">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function addSlot(day) {
        document.querySelectorAll('input[name="days[]"]').forEach(checkbox => {
            checkbox.checked = checkbox.value === day;
        });
        showQuickAdd();
    }

    function copyToAllDays() {
        document.querySelectorAll('input[name="days[]"]').forEach(checkbox => {
            checkbox.checked = true;
        });
        showQuickAdd();
    }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 