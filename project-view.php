<?php 
$page_title = "View Project Details"; 
$page = "projects"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Dummy comprehensive database mockup
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
?>

<div class="container-fluid mb-5">
    <div class="mb-4">
        <a href="projects.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Project Technical Specifications Overview</h5>
            <a href="project-edit.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-light">Edit Project</a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-8">
                    <h3 class="text-primary mb-1"><?php echo htmlspecialchars($project['title']); ?></h3>
                    <span class="badge bg-dark mb-3"><?php echo htmlspecialchars($project['category']); ?></span>

                    <h5 class="fw-semibold mt-2">Description</h5>
                    <p class="text-secondary"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>

                    <h5 class="fw-semibold">The Challenge</h5>
                    <p class="text-secondary"><?php echo nl2br(htmlspecialchars($project['challenge'])); ?></p>

                    <h5 class="fw-semibold">The Solution</h5>
                    <p class="text-secondary"><?php echo nl2br(htmlspecialchars($project['solution'])); ?></p>

                    <h5 class="fw-semibold">Metrics & Results</h5>
                    <div class="p-3 bg-light border rounded text-success fw-medium">
                        <?php echo nl2br(htmlspecialchars($project['metrics'])); ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 bg-light rounded border">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Meta Configurations</h6>
                        
                        <div class="mb-3">
                            <label class="text-muted d-block small fw-bold">SORT POSITION INDEX</label>
                            <span class="badge bg-secondary fs-6"><?php echo $project['sort_order']; ?></span>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted d-block small fw-bold mb-1">TECHNOLOGIES USED</label>
                            <?php foreach ($project['tech_array'] as $tech): ?>
                                <span class="badge bg-white text-dark border p-2 mb-1"><?php echo htmlspecialchars($tech); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <div class="mb-2">
                            <label class="text-muted d-block small fw-bold mb-1 font-monospace">RESOURCE LINKS</label>
                            <a href="<?php echo htmlspecialchars($project['github']); ?>" target="_blank" class="btn btn-sm btn-outline-dark w-100 mb-2">GitHub Repository</a>
                            
                            <?php if (!empty($project['demo'])): ?>
                                <a href="<?php echo htmlspecialchars($project['demo']); ?>" target="_blank" class="btn btn-sm btn-success w-100">Live Demo Preview</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-outline-secondary w-100" disabled>No Demo Link Assigned</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>