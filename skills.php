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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skills';
$categoriesApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skill-categories';

$show_success = isset($_GET['success']);
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : false;

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

// Fetch Skills Matrix
$response = file_get_contents($apiUrl, false, $context);
$skills = [];
if ($response !== false) {
    $decodedData = json_decode($response, true);
    if (is_array($decodedData)) {
        $skills = isset($decodedData[0]) ? $decodedData : ($decodedData['data'] ?? []);
    }
} else {
    $error_message = "Failed to fetch skills matrix telemetry logs from remote repository index.";
}

// Fetch and Index Skill Categories by their ID
$categories_lookup = [];
$categoriesResponse = @file_get_contents($categoriesApiUrl, false, $context);
if ($categoriesResponse !== false) {
    $decodedCats = json_decode($categoriesResponse, true);
    $categoriesList = isset($decodedCats[0]) ? $decodedCats : ($decodedCats['data'] ?? []);
    foreach ($categoriesList as $cat) {
        if (isset($cat['id'])) {
            $categories_lookup[(int)$cat['id']] = $cat['name'] ?? 'Unassigned';
        }
    }
}

$page_title = "Skills List"; 
$page = "skills"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Skills Management</h2>
        <a href="skill-add.php" class="btn btn-primary">Add New Skill Details</a>
    </div>

    <?php if ($show_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Skill transaction context metrics committed successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3 text-center" style="width: 100px;">Sort Order</th>
                            <th>Skill Name</th>
                            <th>Assigned Category</th>
                            <th style="width: 30%;">Strength</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($skills)): ?>
                            <?php foreach ($skills as $skill): 
                                $category_name = "Unassigned";
                                
                                // Match using lookup array maps or existing properties fallbacks
                                if (isset($skill['category_id']) && isset($categories_lookup[(int)$skill['category_id']])) {
                                    $category_name = $categories_lookup[(int)$skill['category_id']];
                                } elseif (isset($skill['category'])) {
                                    $category_name = is_array($skill['category']) ? ($skill['category']['name'] ?? 'Unassigned') : $skill['category'];
                                } elseif (isset($skill['category_name'])) {
                                    $category_name = $skill['category_name'];
                                }
                            ?>
                            <tr>
                                <td class="ps-3 text-center"><span class="badge bg-secondary"><?php echo isset($skill['sort_order']) ? (int)$skill['sort_order'] : 0; ?></span></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($skill['name'] ?? ''); ?></td>
                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($category_name); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: <?php echo (int)($skill['strength'] ?? 0); ?>%;"></div>
                                        </div>
                                        <small class="fw-bold text-muted"><?php echo (int)($skill['strength'] ?? 0); ?>%</small>
                                    </div>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="skill-view.php?id=<?php echo $skill['id']; ?>" class="btn btn-outline-secondary">View</a>
                                        <a href="skill-edit.php?id=<?php echo $skill['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                        <a href="skill-delete.php?id=<?php echo $skill['id']; ?>" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center p-4 text-muted italic">No skill profile listings registered in runtime inventory context.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>