<?php 
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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skill-categories';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];

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
    curl_close($ch);

    if ($httpCode === 200 || $httpCode === 204) {
        header("Location: skill-categories.php?success=1");
        exit();
    } else {
        header("Location: skill-categories.php?error=" . urlencode("Failed to delete category. HTTP Code: " . $httpCode));
        exit();
    }
}

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);
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

$page_title = "Confirm Delete Category"; 
$page = "categories"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skill-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Safe Escape Back</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 500px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">This target category profile has already been cleared or cannot be discovered in the configuration index.</p>
            <hr>
            <a href="skill-categories.php" class="btn btn-info text-white">Return to Skill Categories</a>
        </div>
    <?php else: ?>
        <div class="card border-danger shadow-sm" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Critical Action Required</h5>
            </div>
            <div class="card-body">
                <p class="text-danger fw-bold fs-5 mb-1">Are you absolute sure you want to delete this category?</p>
                <p class="text-muted small">Deleting this entry might affect skills bound to this entity tier downstream.</p>
                
                <div class="p-3 bg-light rounded border mb-2">
                    <strong>Target Name:</strong> <?php echo htmlspecialchars($category['name']); ?><br>
                    <strong>Sort Priority Index:</strong> <?php echo $category['sort_order']; ?>
                </div>
            </div>
            <form action="skill-category-delete.php?id=<?php echo $category['id']; ?>" method="POST">
                <input type="hidden" name="delete_id" value="<?php echo $category['id']; ?>">
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="skill-categories.php" class="btn btn-secondary">No, Keep It</a>
                    <button type="submit" class="btn btn-danger">Yes, Delete Permanently</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>