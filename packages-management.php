<!-- In de "Modal voor nieuw pakket" form -->
<div class="form-control mb-4">
    <label class="label">
        <span class="label-text">Naam</span>
    </label>
    <input type="text" name="name" class="input input-bordered" required>
</div>

<div class="form-control mb-4">
    <label class="label">
        <span class="label-text">Type</span>
    </label>
    <select name="type" class="select select-bordered" required>
        <option value="auto">Auto</option>
        <option value="motor">Motor</option>
    </select>
</div>

<!-- Ook toevoegen aan de edit modal -->
<div class="form-control mb-4">
    <label class="label">
        <span class="label-text">Type</span>
    </label>
    <select name="type" id="edit_package_type" class="select select-bordered" required>
        <option value="auto">Auto</option>
        <option value="motor">Motor</option>
    </select>
</div>

<!-- In de PHP verwerking voor add_package -->
$sql = "INSERT INTO packages (name, type, description, lessons, price, duration_minutes, active) 
        VALUES (:name, :type, :description, :lessons, :price, :duration_minutes, :active)";
$stmt->bindParam(":type", $_POST['type'], PDO::PARAM_STR);

<!-- In de PHP verwerking voor update_package -->
$sql = "UPDATE packages 
        SET name = :name,
            type = :type,
            description = :description,
            lessons = :lessons,
            price = :price,
            duration_minutes = :duration_minutes,
            active = :active 
        WHERE id = :id";
$stmt->bindParam(":type", $_POST['type'], PDO::PARAM_STR);

<!-- In de JavaScript voor het edit modal -->
document.getElementById('edit_package_type').value = package.type; 