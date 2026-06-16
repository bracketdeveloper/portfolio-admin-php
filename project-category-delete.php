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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/project-categories';
$projectsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/projects';

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];

    // Check if any projects are linked to this category id before calling DELETE
    $projectsResponse = @file_get_contents($projectsApiUrl, false, $context);
    $hasLinkedProjects = false;

    if ($projectsResponse !== false) {
        $decodedProjects = json_decode($projectsResponse, true);
        $projectsList = isset($decodedProjects[0]) ? $decodedProjects : ($decodedProjects['data'] ?? []);
        
        foreach ($projectsList as $project) {
            if (isset($project['category_id']) && (int)$project['category_id'] === $deleteId) {
                $hasLinkedProjects = true;
                break;
            }
        }
    }

    if ($hasLinkedProjects) {
        header("Location: project-categories.php?error=" . urlencode("Cannot delete category. This project category is linked to active projects. Please reassign or delete the projects first."));
        exit();
    }

    $ch = curl_init($apiUrl);
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
        header("Location: project-categories.php?success=1");
        exit();
    } else {
        header("Location: project-categories.php?error=" . urlencode("Failed to delete category. HTTP Code: " . $httpCode));
        exit();
    }
}

$response = file_get_contents($apiUrl, false, $context);
$category = null;
$is_found = false;

if ($response !== false) {
    $decodedData = json_decode($response, true);
    if (is_array($decodedData)) {
        $items = isset($decodedData[0]) ? $decodedData : ($decodedData['data'] ?? []);
        if (empty($items) && !isset($decodedData[0])) {
            $items = [$decodedData];
        }

        foreach ($items as $item) {
            if (isset($item['id']) && (int)$item['id'] === $id) {
                $category = $item;
                $is_found = true;
                break;
            }
        }
    }
}

$page_title = "Confirm Delete Project Category"; 
$page = "project-categories"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="project-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Safe Escape Back</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 500px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">This target category profile has already been cleared or cannot be discovered in the configuration index.</p>
            <hr>
            <a href="project-categories.php" class="btn btn-info text-white">Return to Project Categories</a>
        </div>
    <?php else: ?>
        <div class="card border-danger shadow-sm" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Critical Action Required</h5>
            </div>
            <div class="card-body">
                <p class="text-danger fw-bold fs-5 mb-1">Are you absolutely sure you want to delete this category?</p>
                <p class="text-muted small">Deleting this entry might affect projects bound to this category downstream.</p>
                
                <div class="p-3 bg-light rounded border mb-2">
                    <strong>Target Name:</strong> <?php echo htmlspecialchars($category['name'] ?? ''); ?><br>
                    <strong>Sort Priority Index:</strong> <?php echo (int)($category['sort_order'] ?? 0); ?>
                </div>
            </div>
            <form action="project-category-delete.php?id=<?php echo $category['id']; ?>" method="POST">
                <input type="hidden" name="delete_id" value="<?php echo $category['id']; ?>">
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="project-categories.php" class="btn btn-secondary">No, Keep It</a>
                    <button type="submit" class="btn btn-danger">Yes, Delete Permanently</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>