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
$categoriesApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skill-categories';
$skillsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skills';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataToUpdate = [
        "id"          => (int)$_POST['id'],
        "category_id" => (int)$_POST['category_id'],
        "name"        => $_POST['name'],
        "strength"    => (int)$_POST['strength'],
        "sort_order"  => (int)$_POST['sort_order']
    ];

    $ch = curl_init($skillsApiUrl);
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
        header("Location: skills.php?success=1");
        exit();
    } else {
        header("Location: skills.php?error=" . urlencode("Failed to update skill details. HTTP Code: " . $httpCode));
        exit();
    }
}

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

// Fetch target skill details from list endpoint matching ID
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

// Fetch categories dropdown dataset
$categoriesResponse = file_get_contents($categoriesApiUrl, false, $context);
$categories = [];
if ($categoriesResponse !== false) {
    $decodedCats = json_decode($categoriesResponse, true);
    $categories = isset($decodedCats[0]) ? $decodedCats : ($decodedCats['data'] ?? []);
}

$page_title = "Edit Skill Details"; 
$page = "skills"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skills.php" class="btn btn-sm btn-outline-secondary">&larr; Cancel and Return</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 600px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">The skill entry with ID #<?php echo $id; ?> does not exist or has been removed.</p>
            <hr>
            <a href="skills.php" class="btn btn-info text-white">Return to Skills Index</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Modify Skill Configurations (ID: #<?php echo $skill['id']; ?>)</h5>
            </div>
            <form action="skill-edit.php?id=<?php echo $skill['id']; ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Target Category</label>
                        <select class="form-select" name="category_id" required>
                            <option value="" disabled>Choose a category...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ((int)($skill['category_id'] ?? 0) === (int)$cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name'] ?? ''); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Skill Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($skill['name'] ?? ''); ?>" required>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Strength (1-100)</label>
                            <input type="number" class="form-control" name="strength" value="<?php echo (int)($skill['strength'] ?? 0); ?>" min="1" max="100" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Skill Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="<?php echo (int)($skill['sort_order'] ?? 0); ?>" min="1" required>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="skills.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">Update Skill</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>    