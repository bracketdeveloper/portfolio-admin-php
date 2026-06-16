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

// Fetch active projects collection
$projectsResponse = @file_get_contents($projectsApiUrl, false, $context);
$projects = [];

if ($projectsResponse !== false) {
    $decodedProjects = json_decode($projectsResponse, true);
    $items = isset($decodedProjects[0]) ? $decodedProjects : ($decodedProjects['data'] ?? []);
    
    foreach ($items as $item) {
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

        $projects[] = [
            "id"         => (int)($item['id'] ?? 0),
            "title"      => $item['title'] ?? 'Untitled Project',
            "category"   => $categoryName,
            "tech_array" => array_map('trim', $techArray),
            "sort_order" => (int)($item['sort_order'] ?? 0)
        ];
    }
}

// Sort records locally by sort_order ascending sequence
usort($projects, function($a, $b) {
    return $a['sort_order'] <=> $b['sort_order'];
});

$page_title = "Projects List"; 
$page = "projects"; 
include('includes/header.php'); 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Projects Management</h2>
        <a href="project-add.php" class="btn btn-primary">Add New Project</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            Operation execution finalized successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3 text-center" style="width: 110px;">Sort Order</th>
                            <th>Project Title</th>
                            <th>Category</th>
                            <th>Technologies</th>
                            <th class="text-end pe-3" style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projects)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No portfolio project entries discovered or runtime synchronizations failed.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($projects as $project): ?>
                            <tr>
                                <td class="ps-3 text-center">
                                    <span class="badge bg-secondary"><?php echo $project['sort_order']; ?></span>
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars($project['title']); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo htmlspecialchars($project['category']); ?></span>
                                </td>
                                <td>
                                    <?php foreach ($project['tech_array'] as $tech): ?>
                                        <?php if (!empty($tech)): ?>
                                            <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($tech); ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="project-view.php?id=<?php echo $project['id']; ?>" class="btn btn-outline-secondary">View</a>
                                        <a href="project-edit.php?id=<?php echo $project['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                        <a href="project-delete.php?id=<?php echo $project['id']; ?>" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>