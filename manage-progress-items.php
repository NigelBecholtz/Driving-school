<?php
session_start();

// Check if user is logged in and is instructor or admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || 
   ($_SESSION["role"] !== "instructor" && $_SESSION["role"] !== "admin")) {
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

// Verwerk formulier voor nieuwe categorie/onderwerp
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['add_category'])) {
        $sql = "INSERT INTO progress_categories (name) VALUES (:name)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':name' => $_POST['category_name']]);
        $success_message = "Categorie succesvol toegevoegd.";
    }
    elseif(isset($_POST['add_topic'])) {
        $sql = "INSERT INTO progress_topics (category_id, name, description) 
                VALUES (:category_id, :name, :description)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':category_id' => $_POST['category_id'],
            ':name' => $_POST['topic_name'],
            ':description' => $_POST['description']
        ]);
        $success_message = "Onderwerp succesvol toegevoegd.";
    }
    elseif(isset($_POST['update_topic'])) {
        $sql = "UPDATE progress_topics 
                SET description = :description 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':description' => $_POST['description'],
            ':id' => $_POST['topic_id']
        ]);
        $success_message = "Onderwerp succesvol bijgewerkt.";
    }
    elseif(isset($_POST['delete_category'])) {
        $sql = "DELETE FROM progress_categories WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_POST['category_id']]);
        $success_message = "Categorie succesvol verwijderd.";
    }
    elseif(isset($_POST['delete_topic'])) {
        $sql = "DELETE FROM progress_topics WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_POST['topic_id']]);
        $success_message = "Onderwerp succesvol verwijderd.";
    }
}

// Haal alle categorieën en onderwerpen op
$sql = "SELECT c.*, COUNT(t.id) as topic_count 
        FROM progress_categories c
        LEFT JOIN progress_topics t ON c.id = t.category_id
        GROUP BY c.id
        ORDER BY c.name";
$categories = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer Voortgangspunten - RijVaardig Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-base-200">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold">Beheer Voortgangspunten</h1>
            <button class="btn btn-primary" onclick="document.getElementById('add_category').showModal()">
                <i class="fas fa-plus mr-2"></i>
                Nieuwe Categorie
            </button>
        </div>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <div class="grid gap-6">
            <?php foreach($categories as $category): ?>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex justify-between items-center">
                            <h2 class="card-title">
                                <?php echo htmlspecialchars($category['name']); ?>
                                <span class="badge badge-sm"><?php echo $category['topic_count']; ?> onderwerpen</span>
                            </h2>
                            <div class="flex gap-2">
                                <button class="btn btn-sm btn-secondary" 
                                        onclick="showAddTopic(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name']); ?>')">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <form method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze categorie wilt verwijderen?');">
                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" name="delete_category" class="btn btn-sm btn-error">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <?php
                        $sql = "SELECT * FROM progress_topics WHERE category_id = ? ORDER BY name";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$category['id']]);
                        $topics = $stmt->fetchAll();
                        ?>

                        <div class="overflow-x-auto mt-4">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th>Onderwerp</th>
                                        <th>Beschrijving</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($topics as $topic): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($topic['name']); ?></td>
                                            <td><?php echo htmlspecialchars($topic['description']); ?></td>
                                            <td>
                                                <button class="btn btn-xs btn-outline"
                                                        onclick='editTopic(<?php echo json_encode([
                                                            "id" => $topic["id"],
                                                            "name" => $topic["name"],
                                                            "description" => $topic["description"]
                                                        ]); ?>)'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" class="inline" 
                                                      onsubmit="return confirm('Weet je zeker dat je dit onderwerp wilt verwijderen?');">
                                                    <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                                                    <button type="submit" name="delete_topic" class="btn btn-xs btn-error">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal voor nieuwe categorie -->
    <dialog id="add_category" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Nieuwe Categorie Toevoegen</h3>
            <form method="POST" class="py-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Naam</span>
                    </label>
                    <input type="text" name="category_name" class="input input-bordered" required>
                </div>
                <div class="modal-action">
                    <button type="submit" name="add_category" class="btn btn-primary">Toevoegen</button>
                    <button type="button" class="btn" onclick="closeModal('add_category')">Annuleren</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Modal voor nieuw onderwerp -->
    <dialog id="add_topic" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Nieuw Onderwerp Toevoegen</h3>
            <form method="POST" class="py-4">
                <input type="hidden" name="category_id" id="topic_category_id">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Categorie</span>
                    </label>
                    <input type="text" id="topic_category_name" class="input input-bordered" disabled>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Naam</span>
                    </label>
                    <input type="text" name="topic_name" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Beschrijving</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered" rows="3"></textarea>
                </div>
                <div class="modal-action">
                    <button type="submit" name="add_topic" class="btn btn-primary">Toevoegen</button>
                    <button type="button" class="btn" onclick="closeModal('add_topic')">Annuleren</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Modal voor notities bewerken -->
    <dialog id="edit_topic" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Onderwerp Bewerken</h3>
            <form method="POST" class="py-4">
                <input type="hidden" name="topic_id" id="edit_topic_id">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Naam</span>
                    </label>
                    <input type="text" id="edit_topic_name" class="input input-bordered" disabled>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Beschrijving</span>
                    </label>
                    <textarea name="description" id="edit_topic_description" 
                              class="textarea textarea-bordered" rows="3" required></textarea>
                </div>
                <div class="modal-action">
                    <button type="submit" name="update_topic" class="btn btn-primary">Opslaan</button>
                    <button type="button" class="btn" onclick="closeModal('edit_topic')">Annuleren</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
    function editTopic(topic) {
        const modal = document.getElementById('edit_topic');
        document.getElementById('edit_topic_id').value = topic.id;
        document.getElementById('edit_topic_name').value = topic.name;
        document.getElementById('edit_topic_description').value = topic.description || '';
        modal.showModal();
    }

    function showAddTopic(categoryId, categoryName) {
        const modal = document.getElementById('add_topic');
        document.getElementById('topic_category_id').value = categoryId;
        document.getElementById('topic_category_name').value = categoryName;
        modal.showModal();
    }

    function closeModal(modalId) {
        document.getElementById(modalId).close();
    }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 