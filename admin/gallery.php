<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUser();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$db = getDB();
$categories = $db->query("SELECT * FROM categories WHERE type = 'gallery' ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management - KSS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .gallery-item { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .gallery-item img { width: 100%; height: 200px; object-fit: cover; }
        .gallery-item-body { padding: 15px; }
        .upload-area { border: 2px dashed #ddd; border-radius: 10px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .upload-area:hover { border-color: var(--primary-color); background: #f8f9fa; }
        .upload-area.dragover { border-color: var(--primary-color); background: #fff5f0; }
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
                    <li><a href="gallery.php" class="active"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                    <li><a href="#" onclick="logout(); return false;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10">
                <nav class="navbar navbar-custom mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">Gallery Management</span>
                        <div>
                            <button class="btn btn-primary" onclick="showUploadForm()">
                                <i class="fas fa-upload me-2"></i>Upload Image
                            </button>
                        </div>
                    </div>
                </nav>
                
                <?php if ($action === 'list'): ?>
                    <!-- Gallery Grid -->
                    <div id="galleryGrid" class="gallery-grid">
                        <div class="text-center py-5 w-100">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p>Loading gallery...</p>
                        </div>
                    </div>
                    
                    <!-- Upload Modal -->
                    <div class="modal fade" id="uploadModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Upload Image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="uploadForm" enctype="multipart/form-data">
                                        <div class="upload-area mb-3" id="uploadArea">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <p class="mb-2">Drag and drop image here or click to browse</p>
                                            <input type="file" id="fileInput" name="file" accept="image/*" style="display: none;" required>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('fileInput').click()">
                                                Browse Files
                                            </button>
                                            <p class="text-muted mt-2 mb-0"><small>Max file size: 10MB | Formats: JPEG, PNG, GIF, WebP</small></p>
                                        </div>
                                        <div id="filePreview" class="mb-3" style="display: none;">
                                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                        <div class="mb-3">
                                            <label for="imageTitle" class="form-label">Title *</label>
                                            <input type="text" class="form-control" id="imageTitle" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="imageDescription" class="form-label">Description</label>
                                            <textarea class="form-control" id="imageDescription" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="imageAltText" class="form-label">Alt Text *</label>
                                            <input type="text" class="form-control" id="imageAltText" required>
                                            <small class="text-muted">Important for accessibility and SEO</small>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="imageCategory" class="form-label">Category</label>
                                                <select class="form-select" id="imageCategory">
                                                    <option value="">Select Category</option>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="displayOrder" class="form-label">Display Order</label>
                                                <input type="number" class="form-control" id="displayOrder" value="0">
                                                <small class="text-muted">Lower numbers appear first</small>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="isFeatured">
                                                <label class="form-check-label" for="isFeatured">Featured Image</label>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-upload me-2"></i>Upload
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE = '../api/gallery.php';
        
        function showUploadForm() {
            const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            modal.show();
        }
        
        // File input preview
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('filePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Drag and drop
        const uploadArea = document.getElementById('uploadArea');
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('fileInput').files = files;
                const event = new Event('change');
                document.getElementById('fileInput').dispatchEvent(event);
            }
        });
        
        // Upload form
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('file', document.getElementById('fileInput').files[0]);
            formData.append('title', $('#imageTitle').val());
            formData.append('description', $('#imageDescription').val());
            formData.append('alt_text', $('#imageAltText').val());
            formData.append('category_id', $('#imageCategory').val() || '');
            formData.append('display_order', $('#displayOrder').val());
            formData.append('is_featured', $('#isFeatured').is(':checked') ? 1 : 0);
            
            $.ajax({
                url: API_BASE + '?action=upload',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.error === false) {
                        alert('Image uploaded successfully!');
                        $('#uploadModal').modal('hide');
                        $('#uploadForm')[0].reset();
                        document.getElementById('filePreview').style.display = 'none';
                        loadGallery();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON || {};
                    alert('Error: ' + (response.message || 'Failed to upload image'));
                }
            });
        });
        
        function loadGallery() {
            $.ajax({
                url: API_BASE,
                method: 'GET',
                data: { action: 'list', per_page: 100 },
                success: function(response) {
                    if (response.error === false) {
                        renderGallery(response.data.items);
                    }
                }
            });
        }
        
        function renderGallery(items) {
            let html = '';
            if (items.length === 0) {
                html = '<div class="text-center py-5 w-100"><i class="fas fa-images fa-3x text-muted mb-3"></i><p class="text-muted">No images yet. Upload your first image!</p></div>';
            } else {
                items.forEach(item => {
                    html += `
                        <div class="gallery-item">
                            <img src="${item.file_path}" alt="${item.alt_text || item.title}">
                            <div class="gallery-item-body">
                                <h6 class="mb-2">${item.title}</h6>
                                ${item.description ? `<p class="text-muted small mb-2">${item.description.substring(0, 50)}${item.description.length > 50 ? '...' : ''}</p>` : ''}
                                <div class="d-flex gap-2">
                                    <button onclick="editImage(${item.id})" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                                    <button onclick="deleteImage(${item.id})" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            $('#galleryGrid').html(html);
        }
        
        function editImage(id) {
            // Load image data and show edit form
            $.ajax({
                url: API_BASE,
                method: 'GET',
                data: { action: 'get', id: id },
                success: function(response) {
                    if (response.error === false) {
                        const data = response.data;
                        $('#imageTitle').val(data.title);
                        $('#imageDescription').val(data.description || '');
                        $('#imageAltText').val(data.alt_text);
                        $('#imageCategory').val(data.category_id || '');
                        $('#displayOrder').val(data.display_order);
                        $('#isFeatured').prop('checked', data.is_featured == 1);
                        showUploadForm();
                        // Change form to update mode
                        $('#uploadForm').off('submit').on('submit', function(e) {
                            e.preventDefault();
                            updateImage(id);
                        });
                    }
                }
            });
        }
        
        function updateImage(id) {
            const formData = {
                title: $('#imageTitle').val(),
                description: $('#imageDescription').val(),
                alt_text: $('#imageAltText').val(),
                category_id: $('#imageCategory').val() || null,
                display_order: parseInt($('#displayOrder').val()),
                is_featured: $('#isFeatured').is(':checked') ? 1 : 0
            };
            
            $.ajax({
                url: API_BASE + '?action=update&id=' + id,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.error === false) {
                        alert('Image updated successfully!');
                        $('#uploadModal').modal('hide');
                        loadGallery();
                    }
                }
            });
        }
        
        function deleteImage(id) {
            if (!confirm('Are you sure you want to delete this image? This action cannot be undone!')) {
                return;
            }
            
            $.ajax({
                url: API_BASE + '?action=delete&id=' + id,
                method: 'DELETE',
                success: function(response) {
                    if (response.error === false) {
                        alert('Image deleted successfully!');
                        loadGallery();
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
        
        // Load gallery on page load
        $(document).ready(function() {
            loadGallery();
        });
    </script>
</body>
</html>

