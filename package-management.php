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

// Process add package
if(isset($_POST['add_package'])) {
    $sql = "INSERT INTO packages (name, description, lessons, price, duration_minutes, active) 
            VALUES (:name, :description, :lessons, :price, :duration_minutes, :active)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(":description", $_POST['description'], PDO::PARAM_STR);
    $stmt->bindParam(":lessons", $_POST['lessons'], PDO::PARAM_INT);
    $stmt->bindParam(":price", $_POST['price'], PDO::PARAM_STR);
    $stmt->bindParam(":duration_minutes", $_POST['duration_minutes'], PDO::PARAM_INT);
    $stmt->bindParam(":active", isset($_POST['active']) ? 1 : 0, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: packages-management.php");
    exit();
}

// Process update package
if(isset($_POST['update_package'])) {
    $sql = "UPDATE packages 
            SET name = :name, 
                description = :description, 
                lessons = :lessons, 
                price = :price, 
                duration_minutes = :duration_minutes, 
                active = :active 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $_POST['package_id'], PDO::PARAM_INT);
    $stmt->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(":description", $_POST['description'], PDO::PARAM_STR);
    $stmt->bindParam(":lessons", $_POST['lessons'], PDO::PARAM_INT);
    $stmt->bindParam(":price", $_POST['price'], PDO::PARAM_STR);
    $stmt->bindParam(":duration_minutes", $_POST['duration_minutes'], PDO::PARAM_INT);
    $stmt->bindParam(":active", isset($_POST['active']) ? 1 : 0, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: packages-management.php");
    exit();
}

// Process delete package
if(isset($_POST['delete_package'])) {
    $sql = "DELETE FROM packages WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $_POST['package_id'], PDO::PARAM_INT);
    $stmt->execute();
    header("Location: packages-management.php");
    exit();
}

// Fetch all packages
$sql = "SELECT * FROM packages ORDER BY price ASC";
$packages = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pakketbeheer - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Pakketbeheer</h1>
            <button class="btn btn-primary" onclick="document.getElementById('new_package_modal').showModal()">
                Nieuw Pakket
            </button>
        </div>

        <!-- Pakketten overzicht -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($packages as $package): ?>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex justify-between items-start">
                            <h2 class="card-title"><?php echo htmlspecialchars($package['name']); ?></h2>
                            <div class="badge <?php echo $package['active'] ? 'badge-success' : 'badge-error'; ?>">
                                <?php echo $package['active'] ? 'Actief' : 'Inactief'; ?>
                            </div>
                        </div>
                        
                        <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($package['description'])); ?></p>
                        
                        <div class="stats shadow mt-4">
                            <div class="stat">
                                <div class="stat-title">Prijs</div>
                                <div class="stat-value text-primary">€<?php echo number_format($package['price'], 2); ?></div>
                            </div>
                            
                            <div class="stat">
                                <div class="stat-title">Lessen</div>
                                <div class="stat-value"><?php echo $package['lessons']; ?></div>
                            </div>
                            
                            <div class="stat">
                                <div class="stat-title">Duur</div>
                                <div class="stat-value text-secondary"><?php echo $package['duration_minutes']; ?> min</div>
                            </div>
                        </div>

                        <div class="card-actions justify-end mt-4">
                            <button class="btn btn-primary btn-sm" 
                                    onclick="showEditPackageModal(<?php echo htmlspecialchars(json_encode($package)); ?>)">
                                Bewerken
                            </button>
                            <button class="btn btn-error btn-sm" 
                                    onclick="showDeletePackageModal(<?php echo $package['id']; ?>, '<?php echo htmlspecialchars($package['name']); ?>')">
                                Verwijderen
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Modal voor nieuw pakket -->
        <dialog id="new_package_modal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Nieuw Pakket Toevoegen</h3>
                <form method="POST">
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Naam</span>
                        </label>
                        <input type="text" name="name" class="input input-bordered" required>
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Beschrijving</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Aantal Lessen</span>
                            </label>
                            <input type="number" name="lessons" class="input input-bordered" required min="1">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Prijs (€)</span>
                            </label>
                            <input type="number" name="price" class="input input-bordered" required min="0" step="0.01">
                        </div>
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Lesduur (minuten)</span>
                        </label>
                        <input type="number" name="duration_minutes" class="input input-bordered" required min="30" step="30">
                    </div>

                    <div class="form-control mb-4">
                        <label class="label cursor-pointer">
                            <span class="label-text">Actief</span>
                            <input type="checkbox" name="active" class="toggle toggle-primary" checked>
                        </label>
                    </div>

                    <div class="modal-action">
                        <button type="submit" name="add_package" class="btn btn-primary">Toevoegen</button>
                        <button type="button" class="btn" onclick="document.getElementById('new_package_modal').close()">Annuleren</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Modal voor bewerken pakket -->
        <dialog id="edit_package_modal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Pakket Bewerken</h3>
                <form method="POST">
                    <input type="hidden" name="package_id" id="edit_package_id">
                    
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Naam</span>
                        </label>
                        <input type="text" name="name" id="edit_package_name" class="input input-bordered" required>
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Beschrijving</span>
                        </label>
                        <textarea name="description" id="edit_package_description" class="textarea textarea-bordered" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Aantal Lessen</span>
                            </label>
                            <input type="number" name="lessons" id="edit_package_lessons" class="input input-bordered" required min="1">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Prijs (€)</span>
                            </label>
                            <input type="number" name="price" id="edit_package_price" class="input input-bordered" required min="0" step="0.01">
                        </div>
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Lesduur (minuten)</span>
                        </label>
                        <input type="number" name="duration_minutes" id="edit_package_duration" class="input input-bordered" required min="30" step="30">
                    </div>

                    <div class="form-control mb-4">
                        <label class="label cursor-pointer">
                            <span class="label-text">Actief</span>
                            <input type="checkbox" name="active" id="edit_package_active" class="toggle toggle-primary">
                        </label>
                    </div>

                    <div class="modal-action">
                        <button type="submit" name="update_package" class="btn btn-primary">Opslaan</button>
                        <button type="button" class="btn" onclick="document.getElementById('edit_package_modal').close()">Annuleren</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Modal voor verwijderen pakket -->
        <dialog id="delete_package_modal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-error">Pakket Verwijderen</h3>
                <p class="py-4">Weet je zeker dat je het pakket "<span id="delete_package_name"></span>" wilt verwijderen?</p>
                <div class="modal-action">
                    <form method="POST">
                        <input type="hidden" name="package_id" id="delete_package_id">
                        <button type="submit" name="delete_package" class="btn btn-error">Verwijderen</button>
                    </form>
                    <button class="btn" onclick="document.getElementById('delete_package_modal').close()">Annuleren</button>
                </div>
            </div>
        </dialog>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    function showEditPackageModal(package) {
        document.getElementById('edit_package_id').value = package.id;
        document.getElementById('edit_package_name').value = package.name;
        document.getElementById('edit_package_description').value = package.description;
        document.getElementById('edit_package_lessons').value = package.lessons;
        document.getElementById('edit_package_price').value = package.price;
        document.getElementById('edit_package_duration').value = package.duration_minutes;
        document.getElementById('edit_package_active').checked = package.active == 1;
        document.getElementById('edit_package_modal').showModal();
    }

    function showDeletePackageModal(packageId, packageName) {
        document.getElementById('delete_package_id').value = packageId;
        document.getElementById('delete_package_name').textContent = packageName;
        document.getElementById('delete_package_modal').showModal();
    }
    </script>
</body>
</html> 