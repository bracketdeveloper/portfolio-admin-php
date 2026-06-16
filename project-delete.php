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

// Handle API DELETE request execution
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];

    $ch = curl_init($projectsApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["id" => $deleteId]));
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
        header("Location: projects.php?error=" . urlencode("Failed to delete project. HTTP Code: " . $httpCode));
        exit();
    }
}

// Fetch project categories lookup mapping
$categoriesResponse = @file_get_contents($categoriesApiUrl, false, $context);
$categoriesMap = [];
if ($categoriesResponse !== false) {
    $decodedCats = json_decode($categoriesResponse, true);
    $categoriesList = isset($decodedCats[0]) ? $decodedCats : ($decodedCats['data'] ?? []);
    foreach ($categoriesList as $cat) {
        if (isset($cat['id'])) {
            $categoriesMap[(int)$cat['id']] = $cat['name'] ?? 'Unknown';
        }
    }
}

// Fetch target row matching parameters
$projectsResponse = @file_get_contents($projectsApiUrl, false, $context);
$project = null;
$is_found = false;

if ($projectsResponse !== false) {
    $decodedProjects = json_decode($projectsResponse, true);
    $items = isset($decodedProjects[0]) ? $decodedProjects : ($decodedProjects['data'] ?? []);
    
    foreach ($items as $item) {
        if (isset($item['id']) && (int)$item['id'] === $id) {
            $categoryName = 'Unassigned';
            if (isset($item['category_id']) && isset($categoriesMap[(int)$item['category_id']])) {
                $categoryName = $categoriesMap[(int)$item['category_id']];
            } elseif (isset($item['category'])) {
                $categoryName = is_array($item['category']) ? ($item['category']['name'] ?? 'Unassigned') : $item['category'];
            }

            $project = [
                "id"         => (int)$item['id'],
                "title"      => $item['title'] ?? 'Untitled Project',
                "category"   => $categoryName,
                "sort_order" => (int)($item['sort_order'] ?? 0)
            ];
            $is_found = true;
            break;
        }
    }
}

$page_title = "Confirm Delete Project"; 
$page = "projects"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="projects.php" class="btn btn-sm btn-outline-secondary">&larr; Safe Escape Back</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 500px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">The project entry with ID #<?php echo $id; ?> could not be discovered or has been deleted previously.</p>
            <hr>
            <a href="projects.php" class="btn btn-info text-white">Return to Projects List</a>
        </div>
    <?php else: ?>
        <div class="card border-danger shadow-sm" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Critical Action Required</h5>
            </div>
            <div class="card-body">
                <p class="text-danger fw-bold fs-5 mb-1">Are you sure you want to delete this project entry?</p>
                <p class="text-muted small">This deployment history card representation will be dropped permanently from your profile list.</p>
                
                <div class="p-3 bg-light rounded border mb-2">
                    <strong>Project Title:</strong> <?php echo htmlspecialchars($project['title']); ?><br>
                    <strong>Tier Category:</strong> <span class="badge bg-dark"><?php echo htmlspecialchars($project['category']); ?></span><br>
                    <strong>Priority Position:</strong> Index #<?php echo $project['sort_order']; ?>
                </div>
            </div>
            <form action="project-delete.php?id=<?php echo $project['id']; ?>" method="POST">
                <input type="hidden" name="delete_id" value="<?php echo $project['id']; ?>">
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="projects.php" class="btn btn-secondary">No, Save It</a>
                    <button type="submit" class="btn btn-danger">Yes, Delete Entry</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>