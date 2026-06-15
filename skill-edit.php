<?php 
$page_title = "View Skill Details"; 
$page = "skills"; 
include('includes/header.php'); 

// Grab the ID from the URL parameter string (?id=X)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Simulated dataset mimicking database/API collection lookup matches
$skills_dataset = [
    1 => [
        "id" => 1,
        "name" => "React",
        "strength" => 90,
        "category" => "Frontend",
        "category_sort" => 1,
        "sort_order" => 1
    ],
    2 => [
        "id" => 2,
        "name" => "Vue.js",
        "strength" => 85,
        "category" => "Frontend",
        "category_sort" => 1,
        "sort_order" => 2
    ],
    3 => [
        "id" => 3,
        "name" => "PHP / Laravel",
        "strength" => 95,
        "category" => "Backend",
        "category_sort" => 2,
        "sort_order" => 1
    ],
    4 => [
        "id" => 4,
        "name" => "Node.js",
        "strength" => 80,
        "category" => "Backend",
        "category_sort" => 2,
        "sort_order" => 2
    ]
];

// Fallback logic if ID doesn't exist in dummy collection
$skill = isset($skills_dataset[$id]) ? $skills_dataset[$id] : $skills_dataset[1];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skills.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Skill Configuration Info</h5>
            <a href="skill-edit.php?id=<?php echo $skill['id']; ?>" class="btn btn-sm btn-light">Edit</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tr>
                    <th class="bg-light" style="width: 35%;">Category Tab</th>
                    <td>
                        <span class="badge bg-primary"><?php echo htmlspecialchars($skill['category']); ?></span>
                    </td>
                </tr>
                <tr>
                    <th class="bg-light">Tab Sort Order</th>
                    <td><span class="badge bg-dark"><?php echo $skill['category_sort']; ?></span></td>
                </tr>
                <tr>
                    <th class="bg-light">Skill Name</th>
                    <td class="fw-bold"><?php echo htmlspecialchars($skill['name']); ?></td>
                </tr>
                <tr>
                    <th class="bg-light">Skill Sort Order</th>
                    <td><?php echo $skill['sort_order']; ?></td>
                </tr>
                <tr>
                    <th class="bg-light">Strength Metric</th>
                    <td>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <div class="progress flex-grow-1" style="height: 12px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $skill['strength']; ?>%;"></div>
                            </div>
                            <span class="fw-bold text-dark"><?php echo $skill['strength']; ?>/100</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?> 