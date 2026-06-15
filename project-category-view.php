<?php 
$page_title = "View Project Category Details"; 
$page = "project-categories"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Dummy record lookups
$categories_dataset = [
    1 => ["id" => 1, "name" => "Web Applications", "sort_order" => 1],
    2 => ["id" => 2, "name" => "Mobile Apps", "sort_order" => 2],
    3 => ["id" => 3, "name" => "Open Source Tools", "sort_order" => 3]
];

$category = isset($categories_dataset[$id]) ? $categories_dataset[$id] : $categories_dataset[1];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="project-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Category Configuration Profile</h5>
            <a href="project-category-edit.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-light">Edit Data</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tr>
                    <th class="bg-light" style="width: 35%;">Category ID</th>
                    <td>#<?php echo $category['id']; ?></td>
                </tr>
                <tr>
                    <th class="bg-light">Category Name</th>
                    <td class="fw-bold"><?php echo htmlspecialchars($category['name']); ?></td>
                </tr>
                <tr>
                    <th class="bg-light">Global Sort Order</th>
                    <td><span class="badge bg-secondary"><?php echo $category['sort_order']; ?></span></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>