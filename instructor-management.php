<?php
// Initialize the session
session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin"){
    header("location: login.php");
    exit;
}

// Include config file
require_once "includes/config.php";

// Process delete action
if(isset($_POST['delete_instructor'])) {
    $instructor_id = $_POST['instructor_id'];
    $sql = "UPDATE users SET role = 'student' WHERE id = :id AND role = 'instructor'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $instructor_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Process add instructor
if(isset($_POST['add_instructor'])) {
    $user_id = $_POST['user_id'];
    $sql = "UPDATE users SET role = 'instructor' WHERE id = :id AND role = 'student'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Fetch all instructors
$sql = "SELECT id, first_name, last_name, email, phone, created_at FROM users WHERE role = 'instructor' ORDER BY last_name, first_name";
$instructors = $pdo->query($sql)->fetchAll();

// Fetch potential instructors (currently students)
$sql = "SELECT id, first_name, last_name, email FROM users WHERE role = 'student' ORDER BY last_name, first_name";
$potential_instructors = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructeursbeheer - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Instructeursbeheer</h1>
            
            <!-- Nieuwe instructeur knop -->
            <button class="btn btn-primary" onclick="document.getElementById('new_instructor_modal').showModal()">
                Nieuwe Instructeur
            </button>
        </div>

        <!-- Modal voor nieuwe instructeur -->
        <dialog id="new_instructor_modal" class="modal">
            <div class="modal-box w-11/12 max-w-2xl">
                <h3 class="font-bold text-lg mb-4">Selecteer Student om Instructeur te maken</h3>
                
                <!-- Zoekbalk -->
                <input type="text" id="searchStudent" 
                       class="input input-bordered w-full mb-4" 
                       placeholder="Zoek op naam of email..."
                       oninput="filterStudents(this.value)">

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Email</th>
                                <th>Actie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($potential_instructors as $potential): ?>
                            <tr class="student-row">
                                <td><?php echo htmlspecialchars($potential['first_name'] . ' ' . $potential['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($potential['email']); ?></td>
                                <td>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?php echo $potential['id']; ?>">
                                        <button type="submit" name="add_instructor" class="btn btn-primary btn-sm">
                                            Selecteer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">Sluiten</button>
                    </form>
                </div>
            </div>
        </dialog>

        <!-- Instructeurs overzicht -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($instructors as $instructor): ?>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">
                            <?php echo htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']); ?>
                        </h2>
                        
                        <div class="space-y-2">
                            <p>
                                <span class="font-semibold">Email:</span> 
                                <?php echo htmlspecialchars($instructor['email']); ?>
                            </p>
                            <p>
                                <span class="font-semibold">Telefoon:</span> 
                                <?php echo htmlspecialchars($instructor['phone']); ?>
                            </p>
                            <p>
                                <span class="font-semibold">Instructeur sinds:</span> 
                                <?php echo date('d-m-Y', strtotime($instructor['created_at'])); ?>
                            </p>
                        </div>

                        <div class="card-actions justify-between mt-4">
                            <a href="instructor-schedule.php?id=<?php echo $instructor['id']; ?>" class="btn btn-primary btn-sm">
                                Rooster Bekijken
                            </a>
                            <form method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze instructeur wilt verwijderen?');">
                                <input type="hidden" name="instructor_id" value="<?php echo $instructor['id']; ?>">
                                <button type="submit" name="delete_instructor" class="btn btn-error btn-sm">
                                    Verwijderen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Statistieken -->
        <div class="stats shadow mt-8">
            <div class="stat">
                <div class="stat-title">Totaal aantal instructeurs</div>
                <div class="stat-value"><?php echo count($instructors); ?></div>
            </div>
            
            <div class="stat">
                <div class="stat-title">Potentiële instructeurs</div>
                <div class="stat-value"><?php echo count($potential_instructors); ?></div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    // Bevestiging voor verwijderen
    function confirmDelete() {
        return confirm('Weet je zeker dat je deze instructeur wilt verwijderen?');
    }

    // Zoekfunctie voor studenten
    function filterStudents(searchText) {
        searchText = searchText.toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        
        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            const email = row.children[1].textContent.toLowerCase();
            
            if (name.includes(searchText) || email.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    </script>
</body>
</html> 