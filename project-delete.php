<?php 
$page_title = "Confirm Delete Project"; 
$page = "projects"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

$projects_dataset = [
    1 => [
        "id" => 1,
        "title" => "Optimized Body and Mind",
        "category" => "Web Applications",
        "tech_array" => ["Vue.js", "Node.js", "Express", "MongoDB"],
        "sort_order" => 1
    ]
];

$project = isset($projects_dataset[$id]) ? $projects_dataset[$id] : $projects_dataset[1];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="projects.php" class="btn btn-sm btn-outline-secondary">&larr; Safe Escape Back</a>
    </div>

    <div class="card border-danger shadow-sm" style="max-width: 500px;">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Critical Action Required</h5>
        </div>
        <div class="card-body">
            <p class="text-danger fw-bold fs-5 mb-1">Are you sure you want to delete this project entry?</p>
            <p class="text-muted small">This deployment history card representation will be dropped permanently from your profile list.</p>
            
            <div class="p-3 bg-light rounded border mb-2">
                <strong>Project Title:</strong> <?php echo htmlspecialchars($project['title']); ?><br>
                <strong>Tier Category:</strong> <span class="badge bg-dark"><?php echo htmlspecialchars($project['category']); ?></span><br>
                <strong>Priority Position:</strong> Index #<?php echo $project['sort_order']; ?>
            </div>
        </div>
        <form action="projects.php" method="POST">
            <input type="hidden" name="delete_id" value="<?php echo $project['id']; ?>">
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="projects.php" class="btn btn-secondary">No, Save It</a>
                <button type="submit" class="btn btn-danger">Yes, Delete Entry</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>