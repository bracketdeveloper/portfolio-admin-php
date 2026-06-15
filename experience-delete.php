<?php 
$page_title = "Confirm Delete Experience"; 
$page = "experience"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

$experience_dataset = [
    1 => [
        "id" => 1,
        "role" => "Full-Stack Web Developer",
        "company" => "Bracket Developer Inc.",
        "sort_order" => 1
    ]
];

$exp = isset($experience_dataset[$id]) ? $experience_dataset[$id] : $experience_dataset[1];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="experiences.php" class="btn btn-sm btn-outline-secondary">&larr; Safe Escape Back</a>
    </div>

    <div class="card border-danger shadow-sm" style="max-width: 500px;">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Critical Action Required</h5>
        </div>
        <div class="card-body">
            <p class="text-danger fw-bold fs-5 mb-1">Are you sure you want to delete this experience log?</p>
            <p class="text-muted small">This record removal action is absolute and drops data elements downstream permanently.</p>
            
            <div class="p-3 bg-light rounded border mb-2">
                <strong>Role Designation:</strong> <?php echo htmlspecialchars($exp['role']); ?><br>
                <strong>Corporate Station:</strong> <?php echo htmlspecialchars($exp['company']); ?><br>
                <strong>List Hierarchy Index:</strong> Row Position #<?php echo $exp['sort_order']; ?>
            </div>
        </div>
        <form action="experiences.php" method="POST">
            <input type="hidden" name="delete_id" value="<?php echo $exp['id']; ?>">
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="experiences.php" class="btn btn-secondary">No, Preserve It</a>
                <button type="submit" class="btn btn-danger">Yes, Delete Permanently</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>