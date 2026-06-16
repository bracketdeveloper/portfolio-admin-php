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
$apiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/contact';

$show_success = isset($_GET['success']);
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataToUpdate = [
        "id"           => (int)$_POST['id'],
        "email"        => $_POST['email'],
        "phone"        => $_POST['phone'],
        "location"     => $_POST['location'],
        "github_url"   => $_POST['github_url'],
        "linkedin_url" => $_POST['linkedin_url']
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
        header("Location: contact.php?success=1");
        exit();
    } else {
        header("Location: contact.php?error=" . urlencode("Failed to update contact info. HTTP Code: " . $httpCode));
        exit();
    }
}

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);
$response = file_get_contents($apiUrl, false, $context);

$contact = [
    "id" => 1,
    "email" => "",
    "phone" => "",
    "location" => "",
    "github_url" => "",
    "linkedin_url" => ""
];

if ($response !== false) {
    $decodedData = json_decode($response, true);
    if (is_array($decodedData)) {
        $item = isset($decodedData[0]) ? $decodedData[0] : ($decodedData['data'] ?? $decodedData);
        $contact['id'] = isset($item['id']) ? (int)$item['id'] : 1;
        $contact['email'] = $item['email'] ?? '';
        $contact['phone'] = $item['phone'] ?? '';
        $contact['location'] = $item['location'] ?? '';
        $contact['github_url'] = $item['github_url'] ?? ($item['github'] ?? '');
        $contact['linkedin_url'] = $item['linkedin_url'] ?? ($item['linkedin'] ?? '');
    }
} else {
    $error_message = "Failed to fetch contact details from API.";
}

$page_title = "Contact Info"; 
$page = "contact"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Contact Details</h2>
    </div>

    <?php if ($show_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Contact information updated successfully!
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
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Manage Contact Links & Info</h5>
        </div>
        <form action="contact.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
            <div class="card-body">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>" placeholder="e.g., +923157907337" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Location</label>
                        <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($contact['location']); ?>" required>
                    </div>
                </div>
                
                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">GitHub URL</label>
                        <input type="url" class="form-control" name="github_url" value="<?php echo htmlspecialchars($contact['github_url']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">LinkedIn URL</label>
                        <input type="url" class="form-control" name="linkedin_url" value="<?php echo htmlspecialchars($contact['linkedin_url']); ?>" required>
                    </div>
                </div>

            </div>
            
            <div class="card-footer bg-light d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>