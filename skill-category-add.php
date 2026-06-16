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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skill-categories'; 
// For local testing use: 'http://localhost/portfolio-api/api/skill-categories'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataToUpdate = [
        "name"       => $_POST['name'],
        "sort_order" => (int)$_POST['sort_order']
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataToUpdate));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-API-KEY: ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //curl_close($ch);

    if ($httpCode === 201) {
        header("Location: skill-categories.php?success=1");
        exit();
    } else {
        header("Location: skill-categories.php?error=" . urlencode("Failed to create category. HTTP Code: " . $httpCode));
        exit();
    }
}

$page_title = "Add Skill Category"; 
$page = "categories"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skill-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Create New Skill Category</h5>
        </div>
        <form action="skill-category-add.php" method="POST">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" class="form-control" name="name" placeholder="e.g., Mobile Development" required>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold">Sort Order</label>
                    <input type="number" class="form-control" name="sort_order" min="1" placeholder="e.g., 4" required>
                </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="skill-categories.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>