<?php 
$page_title = "Add Work Experience"; 
$page = "experience"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="experiences.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Create History Log Entry</h5>
        </div>
        <form action="experiences.php" method="POST">
            <div class="card-body">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Role / Job Title</label>
                        <input type="text" class="form-control" name="role" placeholder="e.g., Senior Java Developer" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Company Name</label>
                        <input type="text" class="form-control" name="company" placeholder="e.g., Google LLC" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Location</label>
                        <input type="text" class="form-control" name="location" placeholder="e.g., Lahore, Pakistan / Remote" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Employment Period</label>
                        <input type="text" class="form-control" name="period" placeholder="e.g., Mar 2025 - Present" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" min="1" placeholder="1" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tech Array Stack (Comma separated)</label>
                    <input type="text" class="form-control" name="tech_stack" placeholder="e.g., Java, Spring Boot, PostgreSQL, AWS">
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Key Responsibilities / Bullet Points</label>
                    <textarea class="form-control" name="bullets" rows="6" placeholder="• Developed scalable architectures.&#10;• Optimized system runtime parameters by 20%.&#10;• Led cross-functional product operations teams." required></textarea>
                    <small class="text-muted mt-1 d-block">Enter each descriptive point on a new line starting with a bullet marker or dash symbol.</small>
                </div>

            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="experiences.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Experience</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>