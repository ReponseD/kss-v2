<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUser();
$db = getDB();

// Get statistics
$stats = [
    'news' => $db->query("SELECT COUNT(*) FROM content WHERE content_type = 'news' AND status = 'published'")->fetchColumn(),
    'blogs' => $db->query("SELECT COUNT(*) FROM content WHERE content_type = 'blog' AND status = 'published'")->fetchColumn(),
    'announcements' => $db->query("SELECT COUNT(*) FROM content WHERE content_type = 'announcement' AND status = 'published'")->fetchColumn(),
    'gallery' => $db->query("SELECT COUNT(*) FROM gallery")->fetchColumn(),
    'drafts' => $db->query("SELECT COUNT(*) FROM content WHERE status = 'draft'")->fetchColumn(),
];

// Get recent content
$recentContent = $db->query("
    SELECT c.*, u.full_name as author_name
    FROM content c
    LEFT JOIN users u ON c.author_id = u.id
    ORDER BY c.created_at DESC
    LIMIT 10
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KSS Updates Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #a05d3c;
        }
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1E1F24 0%, #2c3e50 100%);
            color: white;
            padding: 0;
        }
        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li a {
            display: block;
            padding: 15px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(160, 93, 60, 0.2);
            border-left-color: var(--primary-color);
            color: white;
        }
        .sidebar-menu li a i {
            width: 20px;
            margin-right: 10px;
        }
        .main-content {
            padding: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .stat-card.news .icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card.blog .icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-card.announcement .icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-card.gallery .icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .stat-card.draft .icon { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="sidebar-header">
                    <h5><i class="fas fa-school me-2"></i>KSS Admin</h5>
                    <small class="text-muted"><?php echo htmlspecialchars($user['full_name']); ?></small>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="content.php?type=news"><i class="fas fa-newspaper"></i> News</a></li>
                    <li><a href="content.php?type=blog"><i class="fas fa-blog"></i> Blogs</a></li>
                    <li><a href="content.php?type=announcement"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                    <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                    <li><a href="#" onclick="logout(); return false;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10">
                <!-- Navbar -->
                <nav class="navbar navbar-custom mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">Dashboard</span>
                        <div>
                            <span class="text-muted me-3">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
                            <button class="btn btn-sm btn-outline-danger" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </div>
                    </div>
                </nav>
                
                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="stat-card news">
                            <div class="d-flex align-items-center">
                                <div class="icon me-3">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $stats['news']; ?></h3>
                                    <small class="text-muted">News</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="stat-card blog">
                            <div class="d-flex align-items-center">
                                <div class="icon me-3">
                                    <i class="fas fa-blog"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $stats['blogs']; ?></h3>
                                    <small class="text-muted">Blogs</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="stat-card announcement">
                            <div class="d-flex align-items-center">
                                <div class="icon me-3">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $stats['announcements']; ?></h3>
                                    <small class="text-muted">Announcements</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="stat-card gallery">
                            <div class="d-flex align-items-center">
                                <div class="icon me-3">
                                    <i class="fas fa-images"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $stats['gallery']; ?></h3>
                                    <small class="text-muted">Gallery Items</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="stat-card draft">
                            <div class="d-flex align-items-center">
                                <div class="icon me-3">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0"><?php echo $stats['drafts']; ?></h3>
                                    <small class="text-muted">Drafts</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <a href="content.php?action=create&type=news" class="btn btn-primary me-2 mb-2">
                                    <i class="fas fa-plus me-2"></i>Create News
                                </a>
                                <a href="content.php?action=create&type=blog" class="btn btn-success me-2 mb-2">
                                    <i class="fas fa-plus me-2"></i>Create Blog
                                </a>
                                <a href="content.php?action=create&type=announcement" class="btn btn-info me-2 mb-2">
                                    <i class="fas fa-plus me-2"></i>Create Announcement
                                </a>
                                <a href="gallery.php?action=upload" class="btn btn-warning me-2 mb-2">
                                    <i class="fas fa-upload me-2"></i>Upload Image
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Content</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Author</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($recentContent)): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No content yet</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($recentContent as $item): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                                                        <td><span class="badge bg-secondary"><?php echo ucfirst($item['content_type']); ?></span></td>
                                                        <td><?php echo htmlspecialchars($item['author_name']); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $item['status'] === 'published' ? 'success' : 'warning'; ?>">
                                                                <?php echo ucfirst($item['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M j, Y', strtotime($item['created_at'])); ?></td>
                                                        <td>
                                                            <a href="content.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
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
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                $.ajax({
                    url: '../api/auth.php?action=logout',
                    method: 'POST',
                    success: function() {
                        window.location.href = 'login.php';
                    }
                });
            }
        }
    </script>
</body>
</html>

