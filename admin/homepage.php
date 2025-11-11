<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../config/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUser();
$db = getDB();

// Get all homepage sections
$sections = $db->query("SELECT * FROM homepage_sections ORDER BY display_order ASC")->fetchAll();
$banners = $db->query("SELECT * FROM homepage_banners ORDER BY display_order ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Management - KSS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        :root { --primary-color: #a05d3c; }
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #1E1F24 0%, #2c3e50 100%); color: white; padding: 0; }
        .sidebar-header { padding: 20px; background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li a { display: block; padding: 15px 20px; color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s; border-left: 3px solid transparent; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background: rgba(160, 93, 60, 0.2); border-left-color: var(--primary-color); color: white; }
        .sidebar-menu li a i { width: 20px; margin-right: 10px; }
        .main-content { padding: 30px; }
        .navbar-custom { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section-card { background: white; border-radius: 10px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .banner-item { background: white; border-radius: 10px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .banner-preview { max-width: 100%; max-height: 200px; border-radius: 5px; }
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
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="content.php?type=news"><i class="fas fa-newspaper"></i> News</a></li>
                    <li><a href="content.php?type=blog"><i class="fas fa-blog"></i> Blogs</a></li>
                    <li><a href="content.php?type=announcement"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                    <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="homepage.php" class="active"><i class="fas fa-edit"></i> Homepage</a></li>
                    <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                    <li><a href="#" onclick="logout(); return false;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10">
                <nav class="navbar navbar-custom mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">Homepage Management</span>
                    </div>
                </nav>
                
                <!-- Homepage Sections -->
                <div class="section-card">
                    <h4 class="mb-4"><i class="fas fa-file-alt me-2"></i>Homepage Sections</h4>
                    <div id="sectionsContainer">
                        <?php foreach ($sections as $section): ?>
                            <div class="mb-4 p-3 border rounded">
                                <h6><?php echo htmlspecialchars($section['section_name']); ?></h6>
                                <small class="text-muted">Key: <?php echo htmlspecialchars($section['section_key']); ?></small>
                                <div class="mt-2">
                                    <?php if (strpos($section['section_key'], 'content') !== false): ?>
                                        <textarea class="form-control section-content" data-key="<?php echo $section['section_key']; ?>" rows="4"><?php echo htmlspecialchars($section['content']); ?></textarea>
                                    <?php else: ?>
                                        <input type="text" class="form-control section-content" data-key="<?php echo $section['section_key']; ?>" value="<?php echo htmlspecialchars($section['content']); ?>">
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-sm btn-primary mt-2" onclick="updateSection('<?php echo $section['section_key']; ?>')">
                                    <i class="fas fa-save me-1"></i>Save
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Homepage Banners -->
                <div class="section-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0"><i class="fas fa-images me-2"></i>Homepage Banners</h4>
                        <button class="btn btn-primary" onclick="showBannerModal()">
                            <i class="fas fa-plus me-2"></i>Add Banner
                        </button>
                    </div>
                    <div id="bannersContainer">
                        <?php foreach ($banners as $banner): ?>
                            <div class="banner-item">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <img src="<?php echo htmlspecialchars($banner['image_url']); ?>" alt="Banner" class="banner-preview">
                                    </div>
                                    <div class="col-md-6">
                                        <h6><?php echo htmlspecialchars($banner['title'] ?? 'Untitled'); ?></h6>
                                        <p class="text-muted small mb-0"><?php echo htmlspecialchars($banner['subtitle'] ?? ''); ?></p>
                                        <small class="text-muted">Order: <?php echo $banner['display_order']; ?> | 
                                        Status: <span class="badge bg-<?php echo $banner['is_active'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $banner['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span></small>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <button class="btn btn-sm btn-primary" onclick="editBanner(<?php echo $banner['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteBanner(<?php echo $banner['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Modal -->
    <div class="modal fade" id="bannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bannerModalTitle">Add Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bannerForm">
                        <input type="hidden" id="bannerId">
                        <div class="mb-3">
                            <label for="bannerTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="bannerTitle">
                        </div>
                        <div class="mb-3">
                            <label for="bannerSubtitle" class="form-label">Subtitle</label>
                            <input type="text" class="form-control" id="bannerSubtitle">
                        </div>
                        <div class="mb-3">
                            <label for="bannerDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="bannerDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="bannerImage" class="form-label">Image URL *</label>
                            <input type="url" class="form-control" id="bannerImage" required>
                            <small class="text-muted">Enter full URL to image (or upload to gallery first)</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bannerButtonText" class="form-label">Button Text</label>
                                <input type="text" class="form-control" id="bannerButtonText">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bannerButtonLink" class="form-label">Button Link</label>
                                <input type="url" class="form-control" id="bannerButtonLink">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bannerOrder" class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="bannerOrder" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="bannerActive" checked>
                                    <label class="form-check-label" for="bannerActive">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE = '../api/homepage.php';
        
        function updateSection(key) {
            const input = $(`.section-content[data-key="${key}"]`);
            const content = input.val();
            
            $.ajax({
                url: API_BASE + '?action=update_section',
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({
                    section_key: key,
                    content: content
                }),
                success: function(response) {
                    if (response.error === false) {
                        alert('Section updated successfully!');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON || {};
                    alert('Error: ' + (response.message || 'Failed to update section'));
                }
            });
        }
        
        function showBannerModal() {
            $('#bannerModalTitle').text('Add Banner');
            $('#bannerForm')[0].reset();
            $('#bannerId').val('');
            const modal = new bootstrap.Modal(document.getElementById('bannerModal'));
            modal.show();
        }
        
        function editBanner(id) {
            $.ajax({
                url: API_BASE + '?action=get_banners',
                method: 'GET',
                success: function(response) {
                    if (response.error === false) {
                        const banner = response.data.find(b => b.id == id);
                        if (banner) {
                            $('#bannerId').val(banner.id);
                            $('#bannerTitle').val(banner.title || '');
                            $('#bannerSubtitle').val(banner.subtitle || '');
                            $('#bannerDescription').val(banner.description || '');
                            $('#bannerImage').val(banner.image_url);
                            $('#bannerButtonText').val(banner.button_text || '');
                            $('#bannerButtonLink').val(banner.button_link || '');
                            $('#bannerOrder').val(banner.display_order);
                            $('#bannerActive').prop('checked', banner.is_active == 1);
                            $('#bannerModalTitle').text('Edit Banner');
                            const modal = new bootstrap.Modal(document.getElementById('bannerModal'));
                            modal.show();
                        }
                    }
                }
            });
        }
        
        function deleteBanner(id) {
            if (!confirm('Are you sure you want to delete this banner?')) {
                return;
            }
            
            $.ajax({
                url: API_BASE + '?action=delete_banner&id=' + id,
                method: 'DELETE',
                success: function(response) {
                    if (response.error === false) {
                        alert('Banner deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        }
        
        $('#bannerForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                title: $('#bannerTitle').val(),
                subtitle: $('#bannerSubtitle').val(),
                description: $('#bannerDescription').val(),
                image_url: $('#bannerImage').val(),
                button_text: $('#bannerButtonText').val(),
                button_link: $('#bannerButtonLink').val(),
                display_order: parseInt($('#bannerOrder').val()),
                is_active: $('#bannerActive').is(':checked') ? 1 : 0
            };
            
            const bannerId = $('#bannerId').val();
            const url = bannerId 
                ? API_BASE + '?action=update_banner&id=' + bannerId
                : API_BASE + '?action=create_banner';
            const method = bannerId ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.error === false) {
                        alert('Banner saved successfully!');
                        $('#bannerModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON || {};
                    alert('Error: ' + (response.message || 'Failed to save banner'));
                }
            });
        });
        
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

