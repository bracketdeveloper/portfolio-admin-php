<?php 
$page_title = "Edit Experience Details"; 
$page = "experience"; 
include('includes/header.php'); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

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
$tech_string = implode(", ", $exp['tech_array']);
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="experiences.php" class="btn btn-sm btn-outline-secondary">&larr; Cancel and Return</a>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Modify Experience Entity (ID: #<?php echo $exp['id']; ?>)</h5>
        </div>
        <form action="experiences.php" method="POST">
            <div class="card-body">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Role / Job Title</label>
                        <input type="text" class="form-control" name="role" value="<?php echo htmlspecialchars($exp['role']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Company Name</label>
                        <input type="text" class="form-control" name="company" value="<?php echo htmlspecialchars($exp['company']); ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Location</label>
                        <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($exp['location']); ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Employment Period</label>
                        <input type="text" class="form-control" name="period" value="<?php echo htmlspecialchars($exp['period']); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="<?php echo $exp['sort_order']; ?>" min="1" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tech Array Stack (Comma separated)</label>
                    <input type="text" class="form-control" name="tech_stack" value="<?php echo htmlspecialchars($tech_string); ?>">
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Key Responsibilities / Bullet Points</label>
                    <textarea class="form-control" name="bullets" rows="6" required><?php echo htmlspecialchars($exp['bullets']); ?></textarea>
                </div>

            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="experiences.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success px-4">Update Experience</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>