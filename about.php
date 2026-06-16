<?php 
// 1. Load environmental variables manually from .env file
if (file_exists('.env')) {
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

$apiKey = $_ENV['API_KEY'] ?? '';
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/about';

// 2. Handle Form Submission (PUT request) - MUST RUN BEFORE ANY HTML OUTPUT
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataToUpdate = [
        "experience_years" => (int)$_POST['experience_years'],
        "projects_built"   => (int)$_POST['projects_built'],
        "happy_clients"    => (int)$_POST['happy_clients'],
        "core_stack_count" => (int)$_POST['core_stack'],
        "description"      => $_POST['description']
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataToUpdate));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-API-KEY: ' . $apiKey
    ]);

    $updateResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode === 200 || $httpCode === 204) {
        header("Location: about.php?success=1");
        exit();
    } else {
        header("Location: about.php?error=" . urlencode("Failed to update profile via API. HTTP Code: " . $httpCode));
        exit();
    }
}

// 3. Fetch live data from API for GET request
$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);
$response = file_get_contents($apiUrl, false, $context);

if ($response !== false) {
    $apiData = json_decode($response, true);
    
    $about = [
        "experience_years" => $apiData['experience_years'] ?? 0,
        "projects_built"   => $apiData['projects_built'] ?? 0,
        "happy_clients"    => $apiData['happy_clients'] ?? 0,
        "core_stack"       => $apiData['core_stack_count'] ?? 0, 
        "description"      => $apiData['description'] ?? ""
    ];
} else {
    $about = [
        "experience_years" => 5,
        "projects_built" => 42,
        "happy_clients" => 30,
        "core_stack" => 8,
        "description" => "Full-stack developer specializing in building scalable web applications using PHP, Laravel, React, and Node.js."
    ];
}

// 4. Now set configuration variables and render layouts safely
$page_title = "About Management"; 
$page = "about"; 
include('includes/header.php'); 

$show_success = isset($_GET['success']);
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : false;
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>About Management</h2>
    </div>

    <?php if ($show_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profile updated successfully via API!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
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