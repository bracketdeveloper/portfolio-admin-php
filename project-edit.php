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
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$projectsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/projects';
$categoriesApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/project-categories';

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

// Handle updating data via API PUT request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $techInput = $_POST['tech_stack'] ?? '';
    $techArray = !empty($techInput) ? array_map('trim', explode(',', $techInput)) : [];

    $dataToUpdate = [
        "id"          => $id,
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
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataToUpdate));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-API-KEY: ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // curl_close($ch);

    if ($httpCode === 200 || $httpCode === 204) {
        header("Location: projects.php?success=1");
        exit();
    } else {
        header("Location: projects.php?error=" . urlencode("Failed to update portfolio entry. HTTP Code: " . $httpCode));
        exit();
    }
}

// Fetch categories dropdown data
$categoriesResponse = @file_get_contents($categoriesApiUrl, false, $context);
$categories = [];
if ($categoriesResponse !== false) {
    $decodedCats = json_decode($categoriesResponse, true);
    $categories = isset($decodedCats[0]) ? $decodedCats : ($decodedCats['data'] ?? []);
}

// Fetch all projects to parse specific target row data elements
$projectsResponse = @file_get_contents($projectsApiUrl, false, $context);
$project = null;
$is_found = false;
$tech_string = '';

if ($projectsResponse !== false) {
    $decodedProjects = json_decode($projectsResponse, true);
    $items = isset($decodedProjects[0]) ? $decodedProjects : ($decodedProjects['data'] ?? []);
    
    foreach ($items as $item) {
        if (isset($item['id']) && (int)$item['id'] === $id) {
            // Standardize technology array parsing rules
            $techArray = [];
            if (isset($item['tech_array'])) {
                $techArray = is_array($item['tech_array']) ? $item['tech_array'] : json_decode($item['tech_array'], true);
            } elseif (isset($item['technologies'])) {
                $techArray = is_array($item['technologies']) ? $item['technologies'] : explode(',', $item['technologies']);
            }
            if (!is_array($techArray)) {
                $techArray = [];
            }
            
            $tech_string = implode(", ", array_map('trim', $techArray));

            $project = [
                "id"          => (int)$item['id'],
                "title"       => $item['title'] ?? '',
                "category_id" => isset($item['category_id']) ? (int)$item['category_id'] : 0,
                "github"      => $item['github'] ?? '',
                "demo"        => $item['demo'] ?? '',
                "description" => $item['description'] ?? '',
                "challenge"   => $item['challenge'] ?? '',
                "solution"    => $item['solution'] ?? '',
                "metrics"     => $item['metrics'] ?? '',
                "sort_order"  => (int)($item['sort_order'] ?? 0)
            ];
            $is_found = true;
            break;
        }
    }
}

$page_title = "Edit Project Details"; 
$page = "projects"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="projects.php" class="btn btn-sm btn-outline-secondary">&larr; Cancel and Return</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 600px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">The portfolio project entity with ID #<?php echo $id; ?> cannot be updated because it does not exist.</p>
            <hr>
            <a href="projects.php" class="btn btn-info text-white">Return to Projects List</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Modify Project Entity Config (ID: #<?php echo $project['id']; ?>)</h5>
            </div>
            <form action="project-edit.php?id=<?php echo $project['id']; ?>" method="POST">
                <div class="card-body">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Project Title</label>
                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category Selection</label>
                            <select class="form-select" name="category_id" required>
                                <option value="" disabled>Choose category...</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo (int)$cat['id']; ?>" <?php echo ((int)$project['category_id'] === (int)$cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Technologies (Comma separated)</label>
                        <input type="text" class="form-control" name="tech_stack" value="<?php echo htmlspecialchars($tech_string); ?>">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">GitHub URL Link</label>
                            <input type="url" class="form-control" name="github" value="<?php echo htmlspecialchars($project['github']); ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Live Demo URL Link <span class="text-muted small">(Optional)</span></label>
                            <input type="url" class="form-control" name="demo" value="<?php echo htmlspecialchars($project['demo']); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="<?php echo $project['sort_order']; ?>" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">The Challenge</label>
                        <textarea class="form-control" name="challenge" rows="3" required><?php echo htmlspecialchars($project['challenge']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">The Solution</label>
                        <textarea class="form-control" name="solution" rows="3" required><?php echo htmlspecialchars($project['solution']); ?></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Key Metrics / Results Achieved</label>
                        <textarea class="form-control" name="metrics" rows="2" required><?php echo htmlspecialchars($project['metrics']); ?></textarea>
                    </div>

                </div>
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="projects.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">Update Project</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>