<?php 
$page_title = "Edit Project Category"; 
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
        <a href="project-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Cancel and Return</a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Modify Category Entity (ID: #<?php echo $category['id']; ?>)</h5>
        </div>
        <form action="project-categories.php" method="POST">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold">Sort Order</label>
                    <input type="number" class="form-control" name="sort_order" value="<?php echo $category['sort_order']; ?>" min="1" required>
                </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="project-categories.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Update Data</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>