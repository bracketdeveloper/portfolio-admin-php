<?php 
$page_title = "Project Categories"; 
$page = "project-categories"; 
include('includes/header.php'); 

// Dummy data array mimicking an API response
$categories = [
    ["id" => 1, "name" => "Web Applications", "sort_order" => 1],
    ["id" => 2, "name" => "Mobile Apps", "sort_order" => 2],
    ["id" => 3, "name" => "Open Source Tools", "sort_order" => 3]
];

// Sort locally by sort_order
usort($categories, function($a, $b) {
    return $a['sort_order'] <=> $b['sort_order'];
});
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Project Categories</h2>
        <a href="project-category-add.php" class="btn btn-primary">Add New Category</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3" style="width: 120px;">Sort Order</th>
                            <th>Category Name</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="ps-3"><span class="badge bg-secondary"><?php echo $cat['sort_order']; ?></span></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="project-category-view.php?id=<?php echo $cat['id']; ?>" class="btn btn-outline-secondary">View</a>
                                    <a href="project-category-edit.php?id=<?php echo $cat['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                    <a href="project-category-delete.php?id=<?php echo $cat['id']; ?>" class="btn btn-outline-danger">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>