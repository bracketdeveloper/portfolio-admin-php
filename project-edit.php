<?php 
$page_title = "Edit Project Details"; 
$page = "projects"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

$categories = [
    ["id" => 1, "name" => "Web Applications"],
    ["id" => 2, "name" => "Mobile Apps"],
    ["id" => 3, "name" => "Open Source Tools"]
];

$projects_dataset = [
    1 => [
        "id" => 1,
        "title" => "Optimized Body and Mind",
        "category" => "Web Applications",
        "tech_array" => ["Vue.js", "Node.js", "Express", "MongoDB"],
        "github" => "https://github.com/yourprofile/optimized-body-mind",
        "demo" => "https://optimizedbodymind.com",
        "description" => "A full-stack medical test appointment booking engine infrastructure offering intuitive UI systems.",
        "challenge" => "Syncing concurrent slots with high request velocity schedules natively.",
        "solution" => "Engineered Redis distributed queue clusters handling isolation transactions.",
        "metrics" => "Zero double-booking conflicts / Handled speed parameters down to 45ms metrics response.",
        "sort_order" => 1
    ]
];

$project = isset($projects_dataset[$id]) ? $projects_dataset[$id] : $projects_dataset[1];
$tech_string = implode(", ", $project['tech_array']);
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="projects.php" class="btn btn-sm btn-outline-secondary">&larr; Cancel and Return</a>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Modify Project Entity Config (ID: #<?php echo $project['id']; ?>)</h5>
        </div>
        <form action="projects.php" method="POST">
            <div class="card-body">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Project Title</label>
                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category Selection</label>
                        <select class="form-select" name="category" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['name']); ?>" <?php echo ($project['category'] === $cat['name']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Technologies (Comma separated)</label>
                    <input type="text" class="form-control" name="tech_stack" value="<?php echo htmlspecialchars($tech_string); ?>">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">GitHub URL Link</label>
                        <input type="url" class="form-control" name="github" value="<?php echo htmlspecialchars($project['github']); ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Live Demo URL Link <span class="text-muted small">(Optional)</span></label>
                        <input type="url" class="form-control" name="demo" value="<?php echo htmlspecialchars($project['demo']); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="<?php echo $project['sort_order']; ?>" min="1" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">The Challenge</label>
                    <textarea class="form-control" name="challenge" rows="3" required><?php echo htmlspecialchars($project['challenge']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">The Solution</label>
                    <textarea class="form-control" name="solution" rows="3" required><?php echo htmlspecialchars($project['solution']); ?></textarea>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Key Metrics / Results Achieved</label>
                    <textarea class="form-control" name="metrics" rows="2" required><?php echo htmlspecialchars($project['metrics']); ?></textarea>
                </div>

            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="projects.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success px-4">Update Project</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>