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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skill-categories';

$show_success = isset($_GET['success']);
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : false;

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);
$response = file_get_contents($apiUrl, false, $context);

$categories = [];
if ($response !== false) {
    $categories = json_decode($response, true);
} else {
    $error_message = "Failed to fetch skill categories from API.";
}

$page_title = "Skill Categories"; 
$page = "categories"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Skill Categories</h2>
        <a href="skill-category-add.php" class="btn btn-primary">Add New Category</a>
    </div>

    <?php if ($show_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Operation completed successfully!
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
                            <th class="ps-3" style="width: 120px;">Sort Order</th>
                            <th>Category Name</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td class="ps-3"><span class="badge bg-secondary"><?php echo $cat['sort_order']; ?></span></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($cat['name']); ?></td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="skill-category-view.php?id=<?php echo $cat['id']; ?>" class="btn btn-outline-secondary">View</a>
                                        <a href="skill-category-edit.php?id=<?php echo $cat['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                        <a href="skill-category-delete.php?id=<?php echo $cat['id']; ?>" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No skill categories discovered.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>