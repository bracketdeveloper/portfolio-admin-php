<?php 
$page_title = "Projects List"; 
$page = "projects"; 
include('includes/header.php'); 

// Dummy dataset mimicking an API response collection
$projects = [
    [
        "id" => 1,
        "title" => "Optimized Body and Mind",
        "category" => "Web Applications",
        "tech_array" => ["Vue.js", "Node.js", "Express", "MongoDB"],
        "github" => "https://github.com/yourprofile/optimized-body-mind",
        "demo" => "https://optimizedbodymind.com",
        "sort_order" => 1
    ],
    [
        "id" => 2,
        "title" => "Manha Vogue POS",
        "category" => "Web Applications",
        "tech_array" => ["PHP", "Laravel", "MySQL", "Bootstrap"],
        "github" => "https://github.com/yourprofile/manha-vogue-pos",
        "demo" => "", // Optional field empty
        "sort_order" => 2
    ]
];

// Sort records locally by sort_order
usort($projects, function($a, $b) {
    return $a['sort_order'] <=> $b['sort_order'];
});
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Projects Management</h2>
        <a href="project-add.php" class="btn btn-primary">Add New Project</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3" style="width: 100px;">Sort Order</th>
                            <th>Project Title</th>
                            <th>Category</th>
                            <th>Technologies</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                        <tr>
                            <td class="ps-3 text-center">
                                <span class="badge bg-secondary"><?php echo $project['sort_order']; ?></span>
                            </td>
                            <td class="fw-bold"><?php echo htmlspecialchars($project['title']); ?></td>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($project['category']); ?></span>
                            </td>
                            <td>
                                <?php foreach ($project['tech_array'] as $tech): ?>
                                    <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($tech); ?></span>
                                <?php endforeach; ?>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="project-view.php?id=<?php echo $project['id']; ?>" class="btn btn-outline-secondary">View</a>
                                    <a href="project-edit.php?id=<?php echo $project['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                    <a href="project-delete.php?id=<?php echo $project['id']; ?>" class="btn btn-outline-danger">Delete</a>
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