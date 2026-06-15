<?php 
$page_title = "Skills List"; 
$page = "skills"; 
include('includes/header.php'); 

// Dummy dynamic mapping mimicking skills bound to category relations
$skills = [
    ["id" => 1, "name" => "React", "strength" => 90, "category" => "Frontend", "sort_order" => 1],
    ["id" => 2, "name" => "PHP / Laravel", "strength" => 95, "category" => "Backend", "sort_order" => 2],
    ["id" => 3, "name" => "Docker", "strength" => 75, "category" => "DevOps / Tools", "sort_order" => 3]
];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Skills Management</h2>
        <a href="skill-add.php" class="btn btn-primary">Add New Skill Details</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3" style="width: 100px;">Sort Order</th>
                            <th>Skill Name</th>
                            <th>Assigned Category</th>
                            <th style="width: 30%;">Strength</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($skills as $skill): ?>
                        <tr>
                            <td class="ps-3 text-center"><span class="badge bg-secondary"><?php echo $skill['sort_order']; ?></span></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($skill['name']); ?></td>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($skill['category']); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: <?php echo $skill['strength']; ?>%;"></div>
                                    </div>
                                    <small class="fw-bold text-muted"><?php echo $skill['strength']; ?>%</small>
                                </div>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="skill-view.php?id=<?php echo $skill['id']; ?>" class="btn btn-outline-secondary">View</a>
                                    <a href="skill-edit.php?id=<?php echo $skill['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                    <a href="skill-delete.php?id=<?php echo $skill['id']; ?>" class="btn btn-outline-danger">Delete</a>
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