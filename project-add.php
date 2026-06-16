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
$projectsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/projects';
$categoriesApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/project-categories';

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

// Handle dynamic category collection retrieval
$categoriesResponse = @file_get_contents($categoriesApiUrl, false, $context);
$categories = [];
if ($categoriesResponse !== false) {
    $decodedCats = json_decode($categoriesResponse, true);
    $categories = isset($decodedCats[0]) ? $decodedCats : ($decodedCats['data'] ?? []);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process comma-separated technologies string into an explicit array context
    $techInput = $_POST['tech_stack'] ?? '';
    $techArray = !empty($techInput) ? array_map('trim', explode(',', $techInput)) : [];

    $dataToInsert = [
        "title"       => $_POST['title'],
        "category_id" => (int)$_POST['category_id'],
        "tech_array"  => $techArray,
        "github"      => $_POST['github'],
        "demo"        => $_POST['demo'] ?? '',
        "sort_order"  => (int)$_POST['sort_order'],
        "description" => $_POST['description'],
        "challenge"   => $_POST['challenge'],
        "solution"    => $_POST['solution'],
        "metrics"     => $_POST['metrics']
    ];

    $ch = curl_init($projectsApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataToInsert));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-API-KEY: ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // curl_close($ch);

    if ($httpCode === 200 || $httpCode === 201) {
        header("Location: projects.php?success=1");
        exit();
    } else {
        header("Location: projects.php?error=" . urlencode("Failed to create portfolio entry. HTTP Code: " . $httpCode));
        exit();
    }
}

$page_title = "Add New Project"; 
$page = "projects"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="projects.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Create Project Portfolio Entry</h5>
        </div>
        <form action="project-add.php" method="POST">
            <div class="card-body">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Project Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category Selection</label>
                        <select class="form-select" name="category_id" required>
                            <option value="" selected disabled>Choose category...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo (int)$cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
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