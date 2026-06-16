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
$categoriesApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skill-categories';
$skillsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skills';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataToSave = [
        "category_id" => (int)$_POST['category_id'],
        "name"        => $_POST['name'],
        "strength"    => (int)$_POST['strength'],
        "sort_order"  => (int)$_POST['sort_order']
    ];

    $ch = curl_init($skillsApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataToSave));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-API-KEY: ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // curl_close($ch);

    if ($httpCode === 201 || $httpCode === 200) {
        header("Location: skills.php?success=1");
        exit();
    } else {
        header("Location: skills.php?error=" . urlencode("Failed to save skill details. HTTP Code: " . $httpCode));
        exit();
    }
}

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);
$categoriesResponse = file_get_contents($categoriesApiUrl, false, $context);

$categories = [];
if ($categoriesResponse !== false) {
    $decodedCategories = json_decode($categoriesResponse, true);
    if (is_array($decodedCategories)) {
        $categories = isset($decodedCategories[0]) ? $decodedCategories : ($decodedCategories['data'] ?? []);
    }
}

$page_title = "Add Skill Details"; 
$page = "skills"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skills.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Add New Skill Details</h5>
        </div>
        <form action="skill-add.php" method="POST">
            <div class="card-body">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Target Category</label>
                    <select class="form-select" name="category_id" required>
                        <option value="" selected disabled>Choose a category...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Skill Name</label>
                    <input type="text" class="form-control" name="name" placeholder="e.g., Vue.js" required>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Strength (1-100)</label>
                        <input type="number" class="form-control" name="strength" min="1" max="100" placeholder="90" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Skill Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" min="1" placeholder="1" required>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="skills.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Skill Setup</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>