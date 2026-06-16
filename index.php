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
$projectsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/projects';
$skillsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/skills';
$experienceApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/experiences';

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

$projectsCount = 0;
$skillsCount = 0;
$experienceCount = 0;

// Fetch Projects Count
$projectsResponse = @file_get_contents($projectsApiUrl, false, $context);
if ($projectsResponse !== false) {
    $decodedProjects = json_decode($projectsResponse, true);
    $projectsList = isset($decodedProjects[0]) ? $decodedProjects : ($decodedProjects['data'] ?? []);
    if (is_array($projectsList)) {
        $projectsCount = count($projectsList);
    }
}

// Fetch Skills Count
$skillsResponse = @file_get_contents($skillsApiUrl, false, $context);
if ($skillsResponse !== false) {
    $decodedSkills = json_decode($skillsResponse, true);
    $skillsList = isset($decodedSkills[0]) ? $decodedSkills : ($decodedSkills['data'] ?? []);
    if (is_array($skillsList)) {
        $skillsCount = count($skillsList);
    }
}

// Fetch Experience Count
$experienceResponse = @file_get_contents($experienceApiUrl, false, $context);
if ($experienceResponse !== false) {
    $decodedExperience = json_decode($experienceResponse, true);
    $experienceList = isset($decodedExperience[0]) ? $decodedExperience : ($decodedExperience['data'] ?? []);
    if (is_array($experienceList)) {
        $experienceCount = count($experienceList);
    }
}

$page_title = "Dashboard"; 
$page = "dashboard"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <h2 class="mb-4">Dashboard Overview</h2>
    
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 bg-primary text-white shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1 opacity-75">Projects</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $projectsCount; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 bg-success text-white shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1 opacity-75">Skills</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $skillsCount; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1 opacity-75">Experience</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $experienceCount; ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>