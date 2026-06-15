<?php 
$page_title = "Edit Skill Details"; 
$page = "skills"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Dummy dataset lookup matching skills array
$skills_dataset = [
    1 => ["id" => 1, "name" => "React", "strength" => 90, "category_id" => 1, "sort_order" => 1],
    2 => ["id" => 2, "name" => "PHP / Laravel", "strength" => 95, "category_id" => 2, "sort_order" => 1],
    3 => ["id" => 3, "name" => "Docker", "strength" => 75, "category_id" => 3, "sort_order" => 1]
];

// Dummy categories table payload to populate the dropdown menu options
$categories = [
    ["id" => 1, "name" => "Frontend"],
    ["id" => 2, "name" => "Backend"],
    ["id" => 3, "name" => "DevOps / Tools"]
];

$skill = isset($skills_dataset[$id]) ? $skills_dataset[$id] : $skills_dataset[1];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skills.php" class="btn btn-sm btn-outline-secondary">&larr; Cancel and Return</a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Modify Skill Configurations (ID: #<?php echo $skill['id']; ?>)</h5>
        </div>
        <form action="skills.php" method="POST">
            <div class="card-body">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Target Category</label>
                    <select class="form-select" name="category_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($skill['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Skill Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($skill['name']); ?>" required>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Strength (1-100)</label>
                        <input type="number" class="form-control" name="strength" value="<?php echo $skill['strength']; ?>" min="1" max="100" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Skill Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="<?php echo $skill['sort_order']; ?>" min="1" required>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="skills.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success px-4">Update Skill</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>