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

$page_title = "View Category Details"; 
$page = "categories"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skill-categories.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 600px;">
            <h4 class="alert-heading"><i class="bi bi-info-circle-fill"></i> Record Not Found</h4>
            <p class="mb-3">The skill category with ID #<?php echo $id; ?> does not exist or has been permanently deleted.</p>
            <hr>
            <a href="skill-categories.php" class="btn btn-info text-white">Return to Skill Categories</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm" style="max-width: 600px;">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Category Configuration Profile</h5>
                <a href="skill-category-edit.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-light">Edit Data</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th class="bg-light" style="width: 35%;">Category ID</th>
                        <td>#<?php echo $category['id']; ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Category Name</th>
                        <td class="fw-bold"><?php echo htmlspecialchars($category['name']); ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Global Sort Order</th>
                        <td><span class="badge bg-secondary"><?php echo $category['sort_order']; ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>