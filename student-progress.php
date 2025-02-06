<?php
session_start();

// Check if user is logged in and is instructor
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "instructor"){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Controleer of er een student ID is meegegeven
if(!isset($_GET['id'])) {
    header("location: my-students.php");
    exit;
}

// Haal student informatie op
$sql = "SELECT u.*, 
        p.name as package_name, 
        p.type as package_type,
        p.lessons_amount,
        po.id as order_id,
        (SELECT COUNT(*) FROM lesson_bookings WHERE order_id = po.id) as completed_lessons
        FROM users u
        JOIN package_orders po ON u.id = po.user_id
        JOIN packages p ON po.package_id = p.id
        WHERE u.id = :student_id 
        AND u.id IN (
            SELECT DISTINCT student_id 
            FROM lesson_bookings lb
            JOIN instructor_schedules ins ON lb.schedule_id = ins.id
            WHERE ins.instructor_id = :instructor_id
        )";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $_GET['id'], PDO::PARAM_INT);
$stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$student = $stmt->fetch();

if(!$student) {
    header("location: my-students.php");
    exit;
}

// Haal lesgeschiedenis op
$sql = "SELECT 
            lb.booking_date,
            lb.status,
            ins.start_time,
            ins.end_time,
            IFNULL(lb.notes, '') as notes,
            IFNULL(lb.rating, 0) as rating
        FROM lesson_bookings lb
        JOIN instructor_schedules ins ON lb.schedule_id = ins.id
        WHERE lb.student_id = :student_id 
        AND ins.instructor_id = :instructor_id
        ORDER BY lb.booking_date DESC, ins.start_time DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $student['id'], PDO::PARAM_INT);
$stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$lessons = $stmt->fetchAll();

// Update lesnotities
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_notes'])) {
    $sql = "UPDATE lesson_bookings 
            SET notes = :notes, rating = :rating
            WHERE id = :booking_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":notes", $_POST['notes']);
    $stmt->bindParam(":rating", $_POST['rating']);
    $stmt->bindParam(":booking_id", $_POST['booking_id']);
    
    if($stmt->execute()) {
        $success_message = "Notities zijn succesvol bijgewerkt.";
    } else {
        $error_message = "Er is een fout opgetreden bij het bijwerken van de notities.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Voortgang - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold">Student Voortgang</h1>
            <a href="my-students.php" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar Studenten
            </a>
        </div>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <?php if(isset($error_message)): ?>
            <div class="alert alert-error mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
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
                            <div class="font-semibold">Contact</div>
                            <div class="space-y-1">
                                <a href="mailto:<?php echo $student['email']; ?>" class="link link-hover flex items-center gap-2">
                                    <i class="fas fa-envelope w-4"></i>
                                    <?php echo htmlspecialchars($student['email']); ?>
                                </a>
                                <a href="tel:<?php echo $student['phone']; ?>" class="link link-hover flex items-center gap-2">
                                    <i class="fas fa-phone w-4"></i>
                                    <?php echo htmlspecialchars($student['phone']); ?>
                                </a>
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold">Pakket</div>
                            <div class="flex items-center gap-2">
                                <span class="badge <?php echo $student['package_type'] === 'auto' ? 
                                    'badge-primary' : 'badge-secondary'; ?>">
                                    <?php echo ucfirst($student['package_type']); ?>
                                </span>
                                <?php echo htmlspecialchars($student['package_name']); ?>
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold">Voortgang</div>
                            <div class="flex items-center gap-2">
                                <progress class="progress progress-primary w-full" 
                                        value="<?php echo $student['completed_lessons']; ?>" 
                                        max="<?php echo $student['lessons_amount']; ?>">
                                </progress>
                                <span class="text-sm">
                                    <?php echo $student['completed_lessons']; ?>/<?php echo $student['lessons_amount']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voortgang overzicht -->
            <div class="md:col-span-2 space-y-6">
                <!-- CBR Voortgang -->
                <?php
                $categories = ['Voertuigcontrole', 'Verkeersdeelname', 'Bijzondere verrichtingen', 
                              'Bijzondere weggedeelten', 'Verantwoord rijgedrag'];
                
                foreach($categories as $category):
                    $sql = "SELECT * FROM student_progress 
                            WHERE student_id = :student_id 
                            AND instructor_id = :instructor_id 
                            AND category = :category
                            ORDER BY topic";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":student_id", $student['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":instructor_id", $_SESSION['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":category", $category);
                    $stmt->execute();
                    $topics = $stmt->fetchAll();
                ?>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $category; ?></h2>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>Onderwerp</th>
                                        <th>Niveau</th>
                                        <th>Notities</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($topics as $topic): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($topic['topic']); ?></td>
                                            <td>
                                                <div class="rating rating-sm">
                                                    <?php for($i = 0; $i <= 3; $i++): ?>
                                                        <input type="radio" 
                                                               name="rating_<?php echo $topic['id']; ?>" 
                                                               class="mask mask-star-2 bg-orange-400"
                                                               value="<?php echo $i; ?>"
                                                               <?php echo $topic['proficiency_level'] == $i ? 'checked' : ''; ?>
                                                               onchange="updateProficiency(<?php echo $topic['id']; ?>, this.value)" />
                                                    <?php endfor; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($topic['notes']): ?>
                                                    <span class="tooltip" data-tip="<?php echo htmlspecialchars($topic['notes']); ?>">
                                                        <i class="fas fa-sticky-note text-primary"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-xs btn-outline"
                                                        onclick="editTopic(<?php echo htmlspecialchars(json_encode($topic)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Lesgeschiedenis -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Lesgeschiedenis</h2>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th>Datum</th>
                                        <th>Tijd</th>
                                        <th>Status</th>
                                        <th>Beoordeling</th>
                                        <th>Notities</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($lessons as $lesson): ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($lesson['booking_date'])); ?></td>
                                            <td>
                                                <?php 
                                                echo date('H:i', strtotime($lesson['start_time'])) . ' - ' .
                                                     date('H:i', strtotime($lesson['end_time'])); 
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm <?php 
                                                    echo $lesson['status'] === 'confirmed' ? 
                                                        'badge-success' : 'badge-warning'; 
                                                ?>">
                                                    <?php echo $lesson['status'] === 'confirmed' ? 
                                                        'Bevestigd' : 'In afwachting'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($lesson['rating']): ?>
                                                    <div class="rating rating-sm">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <input type="radio" 
                                                                   class="mask mask-star-2 bg-orange-400" 
                                                                   <?php echo $i == $lesson['rating'] ? 'checked' : ''; ?> 
                                                                   disabled />
                                                        <?php endfor; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($lesson['notes']): ?>
                                                    <span class="tooltip" data-tip="<?php echo htmlspecialchars($lesson['notes']); ?>">
                                                        <i class="fas fa-sticky-note text-primary"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-xs btn-outline"
                                                        onclick="editLesson(<?php echo htmlspecialchars(json_encode($lesson)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Les bewerken modal -->
    <dialog id="edit_lesson" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg" id="modal_title">Les Bewerken</h3>
            <form method="POST" class="py-4">
                <input type="hidden" name="booking_id" id="booking_id">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Beoordeling</span>
                    </label>
                    <div class="rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" name="rating" value="<?php echo $i; ?>" 
                                   class="mask mask-star-2 bg-orange-400" />
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Notities</span>
                    </label>
                    <textarea name="notes" class="textarea textarea-bordered" rows="4"></textarea>
                </div>
                <div class="modal-action">
                    <button type="submit" name="update_notes" class="btn btn-primary">Opslaan</button>
                    <button type="button" class="btn" onclick="closeModal()">Annuleren</button>
                </div>
            </form>
        </div>
    </dialog>

    <?php include 'includes/footer.php'; ?>

    <script>
    function editLesson(lesson) {
        const modal = document.getElementById('edit_lesson');
        document.getElementById('booking_id').value = lesson.id;
        document.querySelector('textarea[name="notes"]').value = lesson.notes || '';
        
        const ratings = document.getElementsByName('rating');
        ratings.forEach(radio => {
            radio.checked = radio.value == lesson.rating;
        });
        
        modal.showModal();
    }

    function closeModal() {
        document.getElementById('edit_lesson').close();
    }

    async function updateProficiency(topicId, level) {
        try {
            const response = await fetch('update_progress.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    topic_id: topicId,
                    level: level
                })
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            if (data.success) {
                // Toon success message
            } else {
                // Toon error message
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
    </script>
</body>
</html> 