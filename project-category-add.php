<?php 
$page_title = "Add Project Category"; 
$page = "project-categories"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="project-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Create New Project Category</h5>
        </div>
        <form action="project-categories.php" method="POST">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" class="form-control" name="name" placeholder="e.g., E-commerce" required>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold">Sort Order</label>
                    <input type="number" class="form-control" name="sort_order" min="1" placeholder="e.g., 4" required>
                </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="project-categories.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>