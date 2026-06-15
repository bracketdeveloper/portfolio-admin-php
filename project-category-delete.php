<?php 
$page_title = "Confirm Delete Project Category"; 
$page = "project-categories"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

$categories_dataset = [
    1 => ["id" => 1, "name" => "Web Applications", "sort_order" => 1],
    2 => ["id" => 2, "name" => "Mobile Apps", "sort_order" => 2],
    3 => ["id" => 3, "name" => "Open Source Tools", "sort_order" => 3]
];

$category = isset($categories_dataset[$id]) ? $categories_dataset[$id] : $categories_dataset[1];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="project-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Safe Escape Back</a>
    </div>

    <div class="card border-danger shadow-sm" style="max-width: 500px;">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Critical Action Required</h5>
        </div>
        <div class="card-body">
            <p class="text-danger fw-bold fs-5 mb-1">Are you absolutely sure you want to delete this category?</p>
            <p class="text-muted small">Deleting this entry might affect projects bound to this category downstream.</p>
            
            <div class="p-3 bg-light rounded border mb-2">
                <strong>Target Name:</strong> <?php echo htmlspecialchars($category['name']); ?><br>
                <strong>Sort Priority Index:</strong> <?php echo $category['sort_order']; ?>
            </div>
        </div>
        <form action="project-categories.php" method="POST">
            <input type="hidden" name="delete_id" value="<?php echo $category['id']; ?>">
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="project-categories.php" class="btn btn-secondary">No, Keep It</a>
                <button type="submit" class="btn btn-danger">Yes, Delete Permanently</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>