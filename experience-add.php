<?php 
ob_start();

if (file_exists('.env')) {
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

$apiKey = $_ENV['API_KEY'] ?? '';
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/experiences';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process tech array stack input strings into clean JSON arrays
    $techInput = isset($_POST['tech_stack']) ? trim($_POST['tech_stack']) : '';
    $techArray = [];
    if ($techInput !== '') {
        $techArray = array_map('trim', explode(',', $techInput));
        $techArray = array_values(array_filter($techArray));
    }

    // Standardize bullet items from plain text area inputs into structural clean items array
    $bulletsInput = isset($_POST['bullets']) ? trim($_POST['bullets']) : '';
    $bulletsArray = [];
    if ($bulletsInput !== '') {
        $lines = explode("\n", str_replace("\r", "", $bulletsInput));
        foreach ($lines as $line) {
            $cleanedLine = trim($line);
            if ($cleanedLine !== '') {
                // Remove bullet markers if manually provided by the operator
                $cleanedLine = ltrim($cleanedLine, "•-* \t\xA0");
                if (trim($cleanedLine) !== '') {
                    $bulletsArray[] = trim($cleanedLine);
                }
            }
        }
    }

    $dataToUpdate = [
        "role"       => $_POST['role'],
        "company"    => $_POST['company'],
        "location"   => $_POST['location'],
        "period"     => $_POST['period'],
        "sort_order" => (int)$_POST['sort_order'],
        "tech_array" => $techArray,
        "bullets"    => $bulletsArray
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataToUpdate));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-API-KEY: ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // curl_close($ch);

    if ($httpCode === 201) {
        header("Location: experiences.php?success=1");
        exit();
    } else {
        header("Location: experiences.php?error=" . urlencode("Failed to save experience log entry. HTTP Code: " . $httpCode));
        exit();
    }
}

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
            <h5 class="mb-0">Add New Experience</h5>
        </div>
        <form action="experience-add.php" method="POST">
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