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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/experiences';

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);
$response = file_get_contents($apiUrl, false, $context);

$exp = null;
$is_found = false;

if ($response !== false) {
    $decodedData = json_decode($response, true);
    if (is_array($decodedData)) {
        foreach ($decodedData as $item) {
            if (isset($item['id']) && (int)$item['id'] === $id) {
                $exp = $item;
                $is_found = true;
                break;
            }
        }
    }
}

$page_title = "View Experience Details"; 
$page = "experience"; 
include('includes/header.php'); 
?>

<div class="container-fluid mb-5">
    <div class="mb-4">
        <a href="experiences.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 600px;">
            <h4 class="alert-heading"><i class="bi bi-info-circle-fill"></i> Record Not Found</h4>
            <p class="mb-3">The work experience entry with ID #<?php echo $id; ?> does not exist or has been permanently deleted.</p>
            <hr>
            <a href="experiences.php" class="btn btn-info text-white">Return to Experience List</a>
        </div>
    <?php else: 
        // Normalize array variations for tech stacks and bullet metrics
        $techs = isset($exp['tech_array']) ? (is_array($exp['tech_array']) ? $exp['tech_array'] : json_decode($exp['tech_array'], true)) : [];
        if (!is_array($techs)) { $techs = []; }

        $bullets = isset($exp['bullets']) ? (is_array($exp['bullets']) ? $exp['bullets'] : json_decode($exp['bullets'], true)) : [];
        if (!is_array($bullets) && is_string($bullets)) {
            $bullets = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $bullets))));
        }
        if (!is_array($bullets)) { $bullets = []; }
    ?>
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Professional Chronicle Records Profile</h5>
                <a href="experience-edit.php?id=<?php echo $exp['id']; ?>" class="btn btn-sm btn-light">Edit Entry</a>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h3 class="text-primary mb-1"><?php echo htmlspecialchars($exp['role']); ?></h3>
                        <h5 class="text-dark mb-0"><?php echo htmlspecialchars($exp['company']); ?></h5>
                        <p class="text-muted small mt-1"><?php echo htmlspecialchars($exp['location']); ?> &bull; <?php echo htmlspecialchars($exp['period']); ?></p>

                        <h5 class="fw-semibold mt-4 border-bottom pb-2">Core Performance Scope</h5>
                        <div class="lh-lg text-secondary ps-2">
                            <?php if (!empty($bullets)): ?>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($bullets as $bullet): ?>
                                        <li class="mb-2">&bull; <?php echo htmlspecialchars(ltrim($bullet, "•-* \t\xA0")); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <span class="text-muted italic">No performance logs tracking metrics recorded.</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded border">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Structural Details</h6>
                            
                            <div class="mb-3">
                                <label class="text-muted d-block small fw-bold">LIST SORT ORDER INDEX</label>
                                <span class="badge bg-secondary fs-6"><?php echo $exp['sort_order']; ?></span>
                            </div>

                            <div class="mb-2">
                                <label class="text-muted d-block small fw-bold mb-1">UTILIZED TECH TIERS</label>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <?php if (!empty($techs)): ?>
                                        <?php foreach ($techs as $tech): ?>
                                            <span class="badge bg-white text-dark border p-2"><?php echo htmlspecialchars($tech); ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">None indicated.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>