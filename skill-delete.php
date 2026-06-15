<?php 
$page_title = "Confirm Delete Skill"; 
$page = "skills"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Dummy dataset lookup matching skills array
$skills_dataset = [
    1 => ["id" => 1, "name" => "React", "strength" => 90, "category" => "Frontend", "sort_order" => 1],
    2 => ["id" => 2, "name" => "PHP / Laravel", "strength" => 95, "category" => "Backend", "sort_order" => 1],
    3 => ["id" => 3, "name" => "Docker", "strength" => 75, "category" => "DevOps / Tools", "sort_order" => 1]
];

$skill = isset($skills_dataset[$id]) ? $skills_dataset[$id] : $skills_dataset[1];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skills.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card border-danger shadow-sm" style="max-width: 500px;">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Delete Skill Confirmation</h5>
        </div>
        <div class="card-body">
            <p class="text-danger fw-bold fs-5 mb-1">Are you sure you want to delete this skill?</p>
            <p class="text-muted small">This configuration data row will be removed permanently from the portfolio record view.</p>
            
            <div class="p-3 bg-light rounded border mb-2">
                <strong>Skill Name:</strong> <?php echo htmlspecialchars($skill['name']); ?><br>
                <strong>Category Tier:</strong> <span class="badge bg-primary"><?php echo htmlspecialchars($skill['category']); ?></span><br>
                <strong>Strength Metric:</strong> <?php echo $skill['strength']; ?>%<br>
                <strong>Sort Order Index:</strong> <?php echo $skill['sort_order']; ?>
            </div>
        </div>
        <form action="skills.php" method="POST">
            <input type="hidden" name="delete_id" value="<?php echo $skill['id']; ?>">
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="skills.php" class="btn btn-secondary">No, Cancel</a>
                <button type="submit" class="btn btn-danger">Yes, Delete Skill</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>