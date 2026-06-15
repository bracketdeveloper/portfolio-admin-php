<?php 
$page_title = "View Experience Details"; 
$page = "experience"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Dummy tracking entity lookups
$experience_dataset = [
    1 => [
        "id" => 1,
        "role" => "Full-Stack Web Developer",
        "company" => "Bracket Developer Inc.",
        "location" => "Lahore, Pakistan",
        "period" => "Jan 2024 - Present",
        "tech_array" => ["React", "Node.js", "MongoDB", "Express"],
        "bullets" => "• Architected high-performance web applications using scalable paradigms.\n• Managed deployment architecture maps running inside isolated Linux environments.\n• Streamlined continuous integration workflow engines utilizing custom API hooks.",
        "sort_order" => 1
    ]
];

$exp = isset($experience_dataset[$id]) ? $experience_dataset[$id] : $experience_dataset[1];
?>

<div class="container-fluid mb-5">
    <div class="mb-4">
        <a href="experiences.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Professional Chronicle Records Profile</h5>
            <a href="experience-edit.php?id=<?php echo $exp['id']; ?>" class="btn btn-sm btn-light">Edit Entry</a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-8">
                    <h3 class="text-primary mb-1"><?php echo htmlspecialchars($exp['role']); ?></h3>
                    <h5 class="text-dark mb-0"><?php echo htmlspecialchars($exp['company']); ?></h5>
                    <p class="text-muted small mt-1"><?php echo htmlspecialchars($exp['location']); ?> &bull; <?php echo htmlspecialchars($exp['period']); ?></p>

                    <h5 class="fw-semibold mt-4 border-bottom pb-2">Core Performance Scope</h5>
                    <div class="lh-lg text-secondary ps-2">
                        <?php echo nl2br(htmlspecialchars($exp['bullets'])); ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 bg-light rounded border">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Structural Details</h6>
                        
                        <div class="mb-3">
                            <label class="text-muted d-block small fw-bold">LIST SORT ORDER INDEX</label>
                            <span class="badge bg-secondary fs-6"><?php echo $exp['sort_order']; ?></span>
                        </div>

                        <div class="mb-2">
                            <label class="text-muted d-block small fw-bold mb-1">UTILIZED TECH TIERS</label>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                <?php foreach ($exp['tech_array'] as $tech): ?>
                                    <span class="badge bg-white text-dark border p-2"><?php echo htmlspecialchars($tech); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>