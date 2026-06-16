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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $techInput = isset($_POST['tech_stack']) ? trim($_POST['tech_stack']) : '';
    $techArray = [];
    if ($techInput !== '') {
        $techArray = array_map('trim', explode(',', $techInput));
        $techArray = array_values(array_filter($techArray));
    }

    $bulletsInput = isset($_POST['bullets']) ? trim($_POST['bullets']) : '';
    $bulletsArray = [];
    if ($bulletsInput !== '') {
        $lines = explode("\n", str_replace("\r", "", $bulletsInput));
        foreach ($lines as $line) {
            $cleanedLine = trim($line);
            if ($cleanedLine !== '') {
                $cleanedLine = ltrim($cleanedLine, "•-* \t\xA0");
                if (trim($cleanedLine) !== '') {
                    $bulletsArray[] = trim($cleanedLine);
                }
            }
        }
    }

    $dataToUpdate = [
        "id"         => (int)$_POST['id'],
        "role"       => $_POST['role'],
        "company"    => $_POST['company'],
        "location"   => $_POST['location'],
        "period"     => $_POST['period'],
        "sort_order" => (int)$_POST['sort_order'],
        "tech_array" => $techArray,
        "bullets"    => $bulletsArray
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
    // curl_close($ch);

    if ($httpCode === 200 || $httpCode === 204) {
        header("Location: experiences.php?success=1");
        exit();
    } else {
        header("Location: experiences.php?error=" . urlencode("Failed to update experience. HTTP Code: " . $httpCode));
        exit();
    }
}

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

$page_title = "Edit Experience Details"; 
$page = "experience"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="experiences.php" class="btn btn-sm btn-outline-secondary">&larr; Cancel and Return</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 600px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">The work experience entry with ID #<?php echo $id; ?> cannot be edited because it does not exist.</p>
            <hr>
            <a href="experiences.php" class="btn btn-info text-white">Return to Experience List</a>
        </div>
    <?php else: 
        $techs = isset($exp['tech_array']) ? (is_array($exp['tech_array']) ? $exp['tech_array'] : json_decode($exp['tech_array'], true)) : [];
        if (!is_array($techs)) { $techs = []; }
        $tech_string = implode(", ", $techs);

        $bullets = isset($exp['bullets']) ? (is_array($exp['bullets']) ? $exp['bullets'] : json_decode($exp['bullets'], true)) : [];
        if (is_array($bullets)) {
            $bullet_string = "";
            foreach ($bullets as $b) {
                $bullet_string .= "• " . ltrim($b, "•-* \t\xA0") . "\n";
            }
            $bullet_string = trim($bullet_string);
        } else {
            $bullet_string = is_string($bullets) ? trim($bullets) : '';
        }
    ?>
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Modify Experience Entity (ID: #<?php echo $exp['id']; ?>)</h5>
            </div>
            <form action="experience-edit.php?id=<?php echo $exp['id']; ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $exp['id']; ?>">
                <div class="card-body">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role / Job Title</label>
                            <input type="text" class="form-control" name="role" value="<?php echo htmlspecialchars($exp['role']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Company Name</label>
                            <input type="text" class="form-control" name="company" value="<?php echo htmlspecialchars($exp['company']); ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Location</label>
                            <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($exp['location']); ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Employment Period</label>
                            <input type="text" class="form-control" name="period" value="<?php echo htmlspecialchars($exp['period']); ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="<?php echo $exp['sort_order']; ?>" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tech Array Stack (Comma separated)</label>
                        <input type="text" class="form-control" name="tech_stack" value="<?php echo htmlspecialchars($tech_string); ?>">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Key Responsibilities / Bullet Points</label>
                        <textarea class="form-control" name="bullets" rows="6" required><?php echo htmlspecialchars($bullet_string); ?></textarea>
                    </div>

                </div>
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="experiences.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">Update Experience</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>