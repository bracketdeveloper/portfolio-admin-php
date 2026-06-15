<?php 
$page_title = "Add New Project"; 
$page = "projects"; 
include('includes/header.php'); 

// Dummy categories array to populate the dropdown menu
$categories = [
    ["id" => 1, "name" => "Web Applications"],
    ["id" => 2, "name" => "Mobile Apps"],
    ["id" => 3, "name" => "Open Source Tools"]
];
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="projects.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Create Project Portfolio Entry</h5>
        </div>
        <form action="projects.php" method="POST">
            <div class="card-body">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Project Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category Selection</label>
                        <select class="form-select" name="category" required>
                            <option value="" selected disabled>Choose category...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['name']); ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Technologies (Comma separated)</label>
                    <input type="text" class="form-control" name="tech_stack" placeholder="e.g., React, Node.js, MongoDB, Tailwind">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">GitHub URL Link</label>
                        <input type="url" class="form-control" name="github" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Live Demo URL Link <span class="text-muted small">(Optional)</span></label>
                        <input type="url" class="form-control" name="demo">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" min="1" placeholder="1" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" name="description" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">The Challenge</label>
                    <textarea class="form-control" name="challenge" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">The Solution</label>
                    <textarea class="form-control" name="solution" rows="3" required></textarea>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Key Metrics / Results Achieved</label>
                    <textarea class="form-control" name="metrics" rows="2" placeholder="e.g., Reduced API latency by 40% / Handled 10k+ rows clean." required></textarea>
                </div>

            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="projects.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Project</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>