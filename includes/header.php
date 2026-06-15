<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?> | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .sidebar { min-width: 240px; max-width: 240px; background: #212529; min-height: calc(100vh - 56px); }
        .sidebar .nav-link { color: #dee2e6; }
        .sidebar .nav-link.active { background: #0d6efd; color: #fff; }
        .content { flex: 1; padding: 20px; background: #f8f9fa; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand font-monospace fw-bold" href="index.php">ADMIN PANEL</a>
            <span class="navbar-text text-white">Welcome, Admin</span>
        </div>
    </nav>

    <div class="d-flex flex-grow-1">
        <div class="sidebar p-3 d-none d-md-block">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="about.php" class="nav-link <?php echo (isset($page) && $page == 'about') ? 'active' : ''; ?>">About</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="skill-categories.php" class="nav-link <?php echo (isset($page) && $page == 'categories') ? 'active' : ''; ?>">Skills Categories</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="skills.php" class="nav-link <?php echo (isset($page) && $page == 'skills') ? 'active' : ''; ?>">Skills</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="project-categories.php" class="nav-link <?php echo (isset($page) && $page == 'project-categories') ? 'active' : ''; ?>">Project Categories</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="projects.php" class="nav-link <?php echo (isset($page) && $page == 'projects') ? 'active' : ''; ?>">Projects</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="experiences.php" class="nav-link <?php echo (isset($page) && $page == 'experiences') ? 'active' : ''; ?>">Experiences</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="contact.php" class="nav-link <?php echo (isset($page) && $page == 'contact') ? 'active' : ''; ?>">Contact</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="messages.php" class="nav-link <?php echo (isset($page) && $page == 'messages') ? 'active' : ''; ?>">Messages</a>
                </li>
            </ul>
        </div>

        <main class="content">