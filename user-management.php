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
if(isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM users WHERE id = :id AND role != 'admin'"; // Prevent deleting admins
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Process role update
if(isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];
    $sql = "UPDATE users SET role = :role WHERE id = :id AND role != 'admin'"; // Prevent changing admin roles
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":role", $new_role, PDO::PARAM_STR);
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Fetch all users
$sql = "SELECT id, email, role, created_at, first_name, last_name, phone FROM users ORDER BY created_at DESC";
$users = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikersbeheer - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Gebruikersbeheer</h1>
            <a href="register.php" class="btn btn-primary">Nieuwe Gebruiker</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naam</th>
                        <th>Email</th>
                        <th>Telefoon</th>
                        <th>Rol</th>
                        <th>Aangemaakt op</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td>
                            <?php if($user['role'] !== 'admin'): ?>
                            <form method="POST" class="inline-flex gap-2">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <select name="new_role" class="select select-bordered select-sm">
                                    <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                                    <option value="instructor" <?php echo $user['role'] === 'instructor' ? 'selected' : ''; ?>>Instructeur</option>
                                </select>
                                <button type="submit" name="update_role" class="btn btn-sm">Update</button>
                            </form>
                            <?php else: ?>
                                Administrator
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d-m-Y H:i', strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if($user['role'] !== 'admin'): ?>
                            <form method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-error btn-sm">
                                    Verwijderen
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Filters en zoeken -->
        <div class="mt-8">
            <div class="join">
                <div>
                    <div>
                        <input class="input input-bordered join-item" placeholder="Zoeken op email"/>
                    </div>
                </div>
                <select class="select select-bordered join-item">
                    <option disabled selected>Filter op rol</option>
                    <option>Student</option>
                    <option>Instructeur</option>
                    <option>Administrator</option>
                </select>
                <button class="btn join-item">Zoeken</button>
            </div>
        </div>

        <!-- Statistieken -->
        <div class="stats shadow mt-8">
            <div class="stat">
                <div class="stat-title">Totaal aantal gebruikers</div>
                <div class="stat-value"><?php echo count($users); ?></div>
            </div>
            
            <div class="stat">
                <div class="stat-title">Studenten</div>
                <div class="stat-value">
                    <?php 
                    echo count(array_filter($users, function($user) {
                        return $user['role'] === 'student';
                    }));
                    ?>
                </div>
            </div>
            
            <div class="stat">
                <div class="stat-title">Instructeurs</div>
                <div class="stat-value">
                    <?php 
                    echo count(array_filter($users, function($user) {
                        return $user['role'] === 'instructor';
                    }));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    // Bevestiging voor verwijderen
    function confirmDelete() {
        return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');
    }
    </script>
</body>
</html> 