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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skills';
$categoriesApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skill-categories';

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

$response = file_get_contents($apiUrl, false, $context);
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

$page_title = "View Skill Details"; 
$page = "skills"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="skills.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 600px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">The skill asset entry with ID #<?php echo $id; ?> does not exist or has been modified.</p>
            <hr>
            <a href="skills.php" class="btn btn-info text-white">Return to Skills Index</a>
        </div>
    <?php else: 
        $category_name = "Unassigned";
        $category_sort = 0;
        
        if (isset($skill['category_id'])) {
            $categoriesResponse = file_get_contents($categoriesApiUrl, false, $context);
            if ($categoriesResponse !== false) {
                $decodedCats = json_decode($categoriesResponse, true);
                $categories = isset($decodedCats[0]) ? $decodedCats : ($decodedCats['data'] ?? []);
                foreach ($categories as $cat) {
                    if ((int)$cat['id'] === (int)$skill['category_id']) {
                        $category_name = $cat['name'] ?? 'Unassigned';
                        $category_sort = $cat['sort_order'] ?? 0;
                        break;
                    }
                }
            }
        }
    ?>
        <div class="card shadow-sm" style="max-width: 600px;">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Skill Configuration Info</h5>
                <a href="skill-edit.php?id=<?php echo $skill['id']; ?>" class="btn btn-sm btn-light">Edit</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th class="bg-light" style="width: 35%;">Category Tab</th>
                        <td>
                            <span class="badge bg-primary"><?php echo htmlspecialchars($category_name); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tab Sort Order</th>
                        <td><span class="badge bg-dark"><?php echo (int)$category_sort; ?></span></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Skill Name</th>
                        <td class="fw-bold"><?php echo htmlspecialchars($skill['name'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Skill Sort Order</th>
                        <td><?php echo isset($skill['sort_order']) ? (int)$skill['sort_order'] : 0; ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Strength Metric</th>
                        <td>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <div class="progress flex-grow-1" style="height: 12px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo (int)($skill['strength'] ?? 0); ?>%;"></div>
                                </div>
                                <span class="fw-bold text-dark"><?php echo (int)($skill['strength'] ?? 0); ?>/100</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>