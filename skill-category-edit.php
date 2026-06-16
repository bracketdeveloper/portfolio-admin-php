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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataToUpdate = [
        "id"         => (int)$_POST['id'],
        "name"       => $_POST['name'],
        "sort_order" => (int)$_POST['sort_order']
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataToUpdate));
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
        header("Location: skill-categories.php?error=" . urlencode("Failed to update category. HTTP Code: " . $httpCode));
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

$page_title = "Edit Skill Category"; 
$page = "categories"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skill-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Cancel and Return</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 600px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">The skill category with ID #<?php echo $id; ?> cannot be edited because it does not exist.</p>
            <hr>
            <a href="skill-categories.php" class="btn btn-info text-white">Return to Skill Categories</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Modify Category Entity (ID: #<?php echo $category['id']; ?>)</h5>
            </div>
            <form action="skill-category-edit.php?id=<?php echo $category['id']; ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="<?php echo $category['sort_order']; ?>" min="1" required>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="skill-categories.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update Data</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>