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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/experiences';

$show_success = isset($_GET['success']);
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : false;

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);
$response = file_get_contents($apiUrl, false, $context);

$experiences = [];
if ($response !== false) {
    $decodedData = json_decode($response, true);
    if (is_array($decodedData)) {
        $experiences = $decodedData;
    }
} else {
    $error_message = "Failed to fetch work experiences from API.";
}

$page_title = "Work Experience List"; 
$page = "experience"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Work Experience Management</h2>
        <a href="experience-add.php" class="btn btn-primary">Add New Experience</a>
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
                            <th class="ps-3" style="width: 100px;">Sort Order</th>
                            <th>Role / Title</th>
                            <th>Company & Location</th>
                            <th>Period</th>
                            <th>Core Stack</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($experiences)): ?>
                            <?php foreach ($experiences as $exp): 
                                // Direct assignment handling both stringified JSON fields or fallback arrays from API endpoints
                                $techs = isset($exp['tech_array']) ? (is_array($exp['tech_array']) ? $exp['tech_array'] : json_decode($exp['tech_array'], true)) : [];
                                if (!is_array($techs)) { $techs = []; }
                            ?>
                            <tr>
                                <td class="ps-3 text-center">
                                    <span class="badge bg-secondary"><?php echo $exp['sort_order']; ?></span>
                                </td>
                                <td class="fw-bold text-primary"><?php echo htmlspecialchars($exp['role']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($exp['company']); ?></strong>
                                    <span class="text-muted small d-block"><?php echo htmlspecialchars($exp['location']); ?></span>
                                </td>
                                <td><span class="text-nowrap small fw-semibold"><?php echo htmlspecialchars($exp['period']); ?></span></td>
                                <td>
                                    <?php foreach ($techs as $tech): ?>
                                        <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($tech); ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="experience-view.php?id=<?php echo $exp['id']; ?>" class="btn btn-outline-secondary">View</a>
                                        <a href="experience-edit.php?id=<?php echo $exp['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                        <a href="experience-delete.php?id=<?php echo $exp['id']; ?>" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No experience entries discovered.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>