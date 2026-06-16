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
$skillsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skills';
$categoriesApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skill-categories';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    
    $ch = curl_init($skillsApiUrl);
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
        header("Location: skills.php?success=1");
        exit();
    } else {
        header("Location: skills.php?error=" . urlencode("Failed to delete skill asset. HTTP Code: " . $httpCode));
        exit();
    }
}

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

// Fetch target skill entry from runtime records
$response = file_get_contents($skillsApiUrl, false, $context);
$skill = null;
$is_found = false;

if ($response !== false) {
    $decodedData = json_decode($response, true);
    if (is_array($decodedData)) {
        $items = isset($decodedData[0]) ? $decodedData : ($decodedData['data'] ?? []);
        foreach ($items as $item) {
            if (isset($item['id']) && (int)$item['id'] === $id) {
                $skill = $item;
                $is_found = true;
                break;
            }
        }
    }
}

$page_title = "Confirm Delete Skill"; 
$page = "skills"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skills.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 500px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">The skill index targeting ID #<?php echo $id; ?> does not exist or has been removed already.</p>
            <hr>
            <a href="skills.php" class="btn btn-info text-white">Return to Skills Index</a>
        </div>
    <?php else: 
        $category_name = "Unassigned";
        if (isset($skill['category_id'])) {
            $categoriesResponse = @file_get_contents($categoriesApiUrl, false, $context);
            if ($categoriesResponse !== false) {
                $decodedCats = json_decode($categoriesResponse, true);
                $categories = isset($decodedCats[0]) ? $decodedCats : ($decodedCats['data'] ?? []);
                foreach ($categories as $cat) {
                    if ((int)$cat['id'] === (int)$skill['category_id']) {
                        $category_name = $cat['name'] ?? 'Unassigned';
                        break;
                    }
                }
            }
        } elseif (isset($skill['category'])) {
            $category_name = is_array($skill['category']) ? ($skill['category']['name'] ?? 'Unassigned') : $skill['category'];
        }
    ?>
        <div class="card border-danger shadow-sm" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Delete Skill Confirmation</h5>
            </div>
            <div class="card-body">
                <p class="text-danger fw-bold fs-5 mb-1">Are you sure you want to delete this skill?</p>
                <p class="text-muted small">This configuration data row will be removed permanently from the portfolio record view.</p>
                
                <div class="p-3 bg-light rounded border mb-2">
                    <strong>Skill Name:</strong> <?php echo htmlspecialchars($skill['name'] ?? ''); ?><br>
                    <strong>Category Tier:</strong> <span class="badge bg-primary"><?php echo htmlspecialchars($category_name); ?></span><br>
                    <strong>Strength Metric:</strong> <?php echo (int)($skill['strength'] ?? 0); ?>%<br>
                    <strong>Sort Order Index:</strong> <?php echo (int)($skill['sort_order'] ?? 0); ?>
                </div>
            </div>
            <form action="skill-delete.php?id=<?php echo $skill['id']; ?>" method="POST">
                <input type="hidden" name="delete_id" value="<?php echo $skill['id']; ?>">
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="skills.php" class="btn btn-secondary">No, Cancel</a>
                    <button type="submit" class="btn btn-danger">Yes, Delete Skill</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>