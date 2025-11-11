<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUser();
$type = $_GET['type'] ?? 'news';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$db = getDB();
$categories = $db->query("SELECT * FROM categories WHERE type = '$type' ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($type); ?> Management - KSS Admin</title>
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
        .content-card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        #editor { min-height: 300px; }
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
                    <li><a href="content.php?type=news" class="<?php echo $type === 'news' ? 'active' : ''; ?>"><i class="fas fa-newspaper"></i> News</a></li>
                    <li><a href="content.php?type=blog" class="<?php echo $type === 'blog' ? 'active' : ''; ?>"><i class="fas fa-blog"></i> Blogs</a></li>
                    <li><a href="content.php?type=announcement" class="<?php echo $type === 'announcement' ? 'active' : ''; ?>"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                    <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                    <li><a href="#" onclick="logout(); return false;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10">
                <nav class="navbar navbar-custom mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1"><?php echo ucfirst($type); ?> Management</span>
                        <div>
                            <a href="content.php?type=<?php echo $type; ?>&action=create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create New
                            </a>
                        </div>
                    </div>
                </nav>
                
                <?php if ($action === 'list'): ?>
                    <!-- Content List -->
                    <div id="contentList">
                        <div class="text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p>Loading content...</p>
                        </div>
                    </div>
                <?php elseif ($action === 'create' || $action === 'edit'): ?>
                    <!-- Content Form -->
                    <div class="content-card">
                        <form id="contentForm">
                            <input type="hidden" id="contentId" value="<?php echo $id; ?>">
                            <input type="hidden" id="contentType" value="<?php echo $type; ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <textarea class="form-control" id="excerpt" rows="3"></textarea>
                                <small class="text-muted">Brief summary that appears in listings</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Content *</label>
                                <div id="editor"></div>
                                <input type="hidden" id="content">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="featuredImage" class="form-label">Featured Image URL</label>
                                <input type="url" class="form-control" id="featuredImage" placeholder="https://...">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isFeatured">
                                    <label class="form-check-label" for="isFeatured">Featured (Highlight on homepage)</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="tags" placeholder="tag1, tag2, tag3">
                                <small class="text-muted">Separate tags with commas</small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save
                                </button>
                                <a href="content.php?type=<?php echo $type; ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        const API_BASE = '../api/content.php';
        const type = '<?php echo $type; ?>';
        const action = '<?php echo $action; ?>';
        const contentId = <?php echo $id ?: 'null'; ?>;
        
        let quill;
        
        if (action === 'create' || action === 'edit') {
            quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });
            
            if (action === 'edit' && contentId) {
                loadContent(contentId);
            }
        }
        
        if (action === 'list') {
            loadContentList();
        }
        
        function loadContentList() {
            $.ajax({
                url: API_BASE,
                method: 'GET',
                data: { action: 'list', type: type, per_page: 50 },
                success: function(response) {
                    if (response.error === false) {
                        renderContentList(response.data.items);
                    }
                }
            });
        }
        
        function renderContentList(items) {
            let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Author</th><th>Created</th><th>Actions</th></tr></thead><tbody>';
            
            if (items.length === 0) {
                html += '<tr><td colspan="6" class="text-center text-muted">No content found. <a href="?type=' + type + '&action=create">Create your first ' + type + '!</a></td></tr>';
            } else {
                items.forEach(item => {
                    html += `
                        <tr>
                            <td><strong>${item.title}</strong></td>
                            <td>${item.category_name || '-'}</td>
                            <td><span class="badge bg-${item.status === 'published' ? 'success' : 'warning'}">${item.status}</span></td>
                            <td>${item.author_name}</td>
                            <td>${new Date(item.created_at).toLocaleDateString()}</td>
                            <td>
                                <a href="?type=${type}&action=edit&id=${item.id}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <button onclick="deleteContent(${item.id})" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
            }
            
            html += '</tbody></table></div>';
            $('#contentList').html(html);
        }
        
        function loadContent(id) {
            $.ajax({
                url: API_BASE,
                method: 'GET',
                data: { action: 'get', id: id },
                success: function(response) {
                    if (response.error === false) {
                        const data = response.data;
                        $('#title').val(data.title);
                        $('#excerpt').val(data.excerpt);
                        quill.root.innerHTML = data.content;
                        $('#category').val(data.category_id || '');
                        $('#status').val(data.status);
                        $('#featuredImage').val(data.featured_image || '');
                        $('#isFeatured').prop('checked', data.is_featured == 1);
                        if (data.tags && data.tags.length > 0) {
                            $('#tags').val(data.tags.map(t => t.name).join(', '));
                        }
                    }
                }
            });
        }
        
        $('#contentForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                title: $('#title').val(),
                excerpt: $('#excerpt').val(),
                content: quill.root.innerHTML,
                content_type: type,
                category_id: $('#category').val() || null,
                status: $('#status').val(),
                featured_image: $('#featuredImage').val() || null,
                is_featured: $('#isFeatured').is(':checked') ? 1 : 0,
                tags: $('#tags').val().split(',').map(t => t.trim()).filter(t => t)
            };
            
            const url = contentId 
                ? API_BASE + '?action=update&id=' + contentId
                : API_BASE + '?action=create';
            const method = contentId ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.error === false) {
                        alert('Content saved successfully!');
                        window.location.href = 'content.php?type=' + type;
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON || {};
                    alert('Error: ' + (response.message || 'Failed to save content'));
                }
            });
        });
        
        function deleteContent(id) {
            if (!confirm('Are you sure you want to delete this content? This action cannot be undone!')) {
                return;
            }
            
            $.ajax({
                url: API_BASE + '?action=delete&id=' + id,
                method: 'DELETE',
                success: function(response) {
                    if (response.error === false) {
                        alert('Content deleted successfully!');
                        loadContentList();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        }
        
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

