<?php 
$page_title = "Add Skill Details"; 
$page = "skills"; 
include('includes/header.php'); 

// Dummy tracking categories payload to populate the functional dropdown menu
$categories = [
    ["id" => 1, "name" => "Frontend"],
    ["id" => 2, "name" => "Backend"],
    ["id" => 3, "name" => "DevOps / Tools"]
];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skills.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Add New Skill Details</h5>
        </div>
        <form action="skills.php" method="POST">
            <div class="card-body">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Target Category</label>
                    <select class="form-select" name="category_id" required>
                        <option value="" selected disabled>Choose a category...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Skill Name</label>
                    <input type="text" class="form-control" name="name" placeholder="e.g., Vue.js" required>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Strength (1-100)</label>
                        <input type="number" class="form-control" name="strength" min="1" max="100" placeholder="90" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Skill Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" min="1" placeholder="1" required>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="skills.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Skill Setup</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>