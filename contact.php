<?php 
$page_title = "Contact Info"; 
$page = "contact"; 
include('includes/header.php'); 

// 1. Initial Local Data (Simulating database/API data)
$contact = [
    "email" => "developer@example.com",
    "location" => "Lahore, Pakistan",
    "github" => "https://github.com/yourprofile",
    "linkedin" => "https://linkedin.com/in/yourprofile"
];

// 2. Simulate Local Form Processing (Temporary until API integration)
$show_success = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact['email'] = $_POST['email'];
    $contact['location'] = $_POST['location'];
    $contact['github'] = $_POST['github'];
    $contact['linkedin'] = $_POST['linkedin'];
    $show_success = true;
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Contact Details</h2>
        <span class="badge bg-secondary">Static Mode</span>
    </div>

    <?php if ($show_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Contact information updated successfully! (Ready to connect to API later).
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Manage Contact Links & Info</h5>
        </div>
        <form action="contact.php" method="POST">
            <div class="card-body">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Location</label>
                        <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($contact['location']); ?>" required>
                    </div>
                </div>
                
                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">GitHub URL</label>
                        <input type="url" class="form-control" name="github" value="<?php echo htmlspecialchars($contact['github']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">LinkedIn URL</label>
                        <input type="url" class="form-control" name="linkedin" value="<?php echo htmlspecialchars($contact['linkedin']); ?>" required>
                    </div>
                </div>

            </div>
            
            <div class="card-footer bg-light d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>