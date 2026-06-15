<?php 
$page_title = "About Management"; 
$page = "about"; 
include('includes/header.php'); 

// 1. Initial Local Data (Simulating database/API data)
$about = [
    "experience_years" => 5,
    "projects_built" => 42,
    "happy_clients" => 30,
    "core_stack" => 8,
    "description" => "Full-stack developer specializing in building scalable web applications using PHP, Laravel, React, and Node.js."
];

// 2. Simulate Local Form Processing (Temporary until API integration)
$show_success = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $about['experience_years'] = $_POST['experience_years'];
    $about['projects_built'] = $_POST['projects_built'];
    $about['happy_clients'] = $_POST['happy_clients'];
    $about['core_stack'] = $_POST['core_stack'];
    $about['description'] = $_POST['description'];
    $show_success = true;
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>About Management</h2>
        <span class="badge bg-secondary">Static Mode</span>
    </div>

    <?php if ($show_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Changes simulated successfully! (Ready to connect to API later).
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Manage Portfolio Profile Info</h5>
        </div>
        <form action="about.php" method="POST">
            <div class="card-body">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Experience Years</label>
                        <input type="number" class="form-control" name="experience_years" value="<?php echo htmlspecialchars($about['experience_years']); ?>" required min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Projects Built</label>
                        <input type="number" class="form-control" name="projects_built" value="<?php echo htmlspecialchars($about['projects_built']); ?>" required min="0">
                    </div>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Happy Clients</label>
                        <input type="number" class="form-control" name="happy_clients" value="<?php echo htmlspecialchars($about['happy_clients']); ?>" required min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Core Stack (Technologies Count)</label>
                        <input type="number" class="form-control" name="core_stack" value="<?php echo htmlspecialchars($about['core_stack']); ?>" required min="0">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($about['description']); ?></textarea>
                </div>
            </div>
            
            <div class="card-footer bg-light d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>