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

$projectsApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/projects';
$categoriesApiUrl = 'https://portfolio-api-wine-seven.vercel.app/api/project-categories';

$context = stream_context_create([
    'http' => [
        'header' => "X-API-KEY: " . $apiKey . "\r\n"
    ]
]);

// Fetch project categories lookup mapping
$categoriesResponse = @file_get_contents($categoriesApiUrl, false, $context);
$categoriesMap = [];
if ($categoriesResponse !== false) {
    $decodedCats = json_decode($categoriesResponse, true);
    $categoriesList = isset($decodedCats[0]) ? $decodedCats : ($decodedCats['data'] ?? []);
    foreach ($categoriesList as $cat) {
        if (isset($cat['id'])) {
            $categoriesMap[(int)$cat['id']] = $cat['name'] ?? 'Unknown';
        }
    }
}

// Fetch all projects to isolate the targeted record entity row details
$projectsResponse = @file_get_contents($projectsApiUrl, false, $context);
$project = null;
$is_found = false;

if ($projectsResponse !== false) {
    $decodedProjects = json_decode($projectsResponse, true);
    $items = isset($decodedProjects[0]) ? $decodedProjects : ($decodedProjects['data'] ?? []);
    
    foreach ($items as $item) {
        if (isset($item['id']) && (int)$item['id'] === $id) {
            // Resolve target category tier string presentation safely
            $categoryName = 'Unassigned';
            if (isset($item['category_id']) && isset($categoriesMap[(int)$item['category_id']])) {
                $categoryName = $categoriesMap[(int)$item['category_id']];
            } elseif (isset($item['category'])) {
                $categoryName = is_array($item['category']) ? ($item['category']['name'] ?? 'Unassigned') : $item['category'];
            }

            // Standardize technology elements parsing logic
            $techArray = [];
            if (isset($item['tech_array'])) {
                $techArray = is_array($item['tech_array']) ? $item['tech_array'] : json_decode($item['tech_array'], true);
            } elseif (isset($item['technologies'])) {
                $techArray = is_array($item['technologies']) ? $item['technologies'] : explode(',', $item['technologies']);
            }
            if (!is_array($techArray)) {
                $techArray = [];
            }

            $project = [
                "id"          => (int)$item['id'],
                "title"       => $item['title'] ?? 'Untitled Project',
                "category"    => $categoryName,
                "tech_array"  => array_map('trim', $techArray),
                "github"      => $item['github'] ?? '',
                "demo"        => $item['demo'] ?? '',
                "description" => $item['description'] ?? '',
                "challenge"   => $item['challenge'] ?? '',
                "solution"    => $item['solution'] ?? '',
                "metrics"     => $item['metrics'] ?? '',
                "sort_order"  => (int)($item['sort_order'] ?? 0)
            ];
            $is_found = true;
            break;
        }
    }
}

$page_title = "View Project Details"; 
$page = "projects"; 
include('includes/header.php'); 
?>

<div class="container-fluid mb-5">
    <div class="mb-4">
        <a href="projects.php" class="btn btn-sm btn-outline-secondary">&larr; Back to List</a>
    </div>

    <?php if (!$is_found): ?>
        <div class="alert alert-info shadow-sm" style="max-width: 600px;">
            <h4 class="alert-heading">Record Not Found</h4>
            <p class="mb-3">The portfolio entity with ID #<?php echo $id; ?> could not be isolated or index parsing rules failed.</p>
            <hr>
            <a href="projects.php" class="btn btn-info text-white">Return to Projects List</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Project Technical Specifications Overview</h5>
                <a href="project-edit.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-light">Edit Project</a>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h3 class="text-primary mb-1"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <span class="badge bg-dark mb-3"><?php echo htmlspecialchars($project['category']); ?></span>

                        <h5 class="fw-semibold mt-2">Description</h5>
                        <p class="text-secondary"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>

                        <h5 class="fw-semibold">The Challenge</h5>
                        <p class="text-secondary"><?php echo nl2br(htmlspecialchars($project['challenge'])); ?></p>

                        <h5 class="fw-semibold">The Solution</h5>
                        <p class="text-secondary"><?php echo nl2br(htmlspecialchars($project['solution'])); ?></p>

                        <h5 class="fw-semibold">Metrics & Results</h5>
                        <div class="p-3 bg-light border rounded text-success fw-medium">
                            <?php echo nl2br(htmlspecialchars($project['metrics'])); ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded border">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Meta Configurations</h6>
                            
                            <div class="mb-3">
                                <label class="text-muted d-block small fw-bold">SORT POSITION INDEX</label>
                                <span class="badge bg-secondary fs-6"><?php echo $project['sort_order']; ?></span>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted d-block small fw-bold mb-1">TECHNOLOGIES USED</label>
                                <?php foreach ($project['tech_array'] as $tech): ?>
                                    <?php if (!empty($tech)): ?>
                                        <span class="badge bg-white text-dark border p-2 mb-1"><?php echo htmlspecialchars($tech); ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <div class="mb-2">
                                <label class="text-muted d-block small fw-bold mb-1 font-monospace">RESOURCE LINKS</label>
                                <?php if (!empty($project['github'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['github']); ?>" target="_blank" class="btn btn-sm btn-outline-dark w-100 mb-2">GitHub Repository</a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary w-100 mb-2" disabled>No GitHub Repository Link Available</button>
                                <?php endif; ?>
                                
                                <?php if (!empty($project['demo'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['demo']); ?>" target="_blank" class="btn btn-sm btn-success w-100">Live Demo Preview</a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary w-100" disabled>No Demo Link Assigned</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>