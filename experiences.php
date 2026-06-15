<?php 
$page_title = "Work Experience List"; 
$page = "experience"; 
include('includes/header.php'); 

// Dummy dataset mimicking database response
$experiences = [
    [
        "id" => 1,
        "role" => "Full-Stack Web Developer",
        "company" => "Bracket Developer Inc.",
        "location" => "Lahore, Pakistan",
        "period" => "Jan 2024 - Present",
        "tech_array" => ["React", "Node.js", "MongoDB", "Express"],
        "sort_order" => 1
    ],
    [
        "id" => 2,
        "role" => "Software Engineer Intern",
        "company" => "Tech Solutions Lab",
        "location" => "Remote",
        "period" => "June 2023 - Dec 2023",
        "tech_array" => ["PHP", "Laravel", "MySQL", "Bootstrap"],
        "sort_order" => 2
    ]
];

// Sort locally by sort_order
usort($experiences, function($a, $b) {
    return $a['sort_order'] <=> $b['sort_order'];
});
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Work Experience Management</h2>
        <a href="experience-add.php" class="btn btn-primary">Add New Experience</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3" style="width: 100px;">Sort Order</th>
                            <th>Role / Title</th>
                            <th>Company & Location</th>
                            <th>Period</th>
                            <th>Core Stack</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($experiences as $exp): ?>
                        <tr>
                            <td class="ps-3 text-center">
                                <span class="badge bg-secondary"><?php echo $exp['sort_order']; ?></span>
                            </td>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($exp['role']); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($exp['company']); ?></strong>
                                <span class="text-muted small d-block"><?php echo htmlspecialchars($exp['location']); ?></span>
                            </td>
                            <td><span class="text-nowrap small fw-semibold"><?php echo htmlspecialchars($exp['period']); ?></span></td>
                            <td>
                                <?php foreach ($exp['tech_array'] as $tech): ?>
                                    <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($tech); ?></span>
                                <?php endforeach; ?>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="experience-view.php?id=<?php echo $exp['id']; ?>" class="btn btn-outline-secondary">View</a>
                                    <a href="experience-edit.php?id=<?php echo $exp['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                    <a href="experience-delete.php?id=<?php echo $exp['id']; ?>" class="btn btn-outline-danger">Delete</a>
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