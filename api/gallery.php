<?php
/**
 * Gallery API
 * KSS Updates System
 */

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../config/functions.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'list':
        if ($method === 'GET') {
            handleListGallery();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'get':
        if ($method === 'GET') {
            handleGetGallery($id);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'upload':
        if ($method === 'POST') {
            requireLogin();
            handleUploadGallery();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'update':
        if ($method === 'PUT' || $method === 'POST') {
            requireLogin();
            handleUpdateGallery($id);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'delete':
        if ($method === 'DELETE' || $method === 'POST') {
            requireLogin();
            handleDeleteGallery($id);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    default:
        errorResponse('Invalid action', 400);
}

function handleListGallery() {
    $db = getDB();
    
    $category = $_GET['category'] ?? null;
    $featured = isset($_GET['featured']) ? (bool)$_GET['featured'] : null;
    $page = (int)($_GET['page'] ?? 1);
    $perPage = (int)($_GET['per_page'] ?? 20);
    
    // Build query
    $where = ["1=1"];
    $params = [];
    
    if ($category) {
        $where[] = "category_id = ?";
        $params[] = $category;
    }
    
    if ($featured !== null) {
        $where[] = "is_featured = ?";
        $params[] = $featured ? 1 : 0;
    }
    
    $whereClause = implode(' AND ', $where);
    
    // Get total count
    $countStmt = $db->prepare("SELECT COUNT(*) FROM gallery WHERE $whereClause");
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();
    
    // Get pagination info
    $pagination = paginate($page, $perPage, $total);
    
    // Get gallery items
    $stmt = $db->prepare("
        SELECT g.*, 
               u.full_name as uploaded_by_name,
               cat.name as category_name,
               cat.slug as category_slug
        FROM gallery g
        LEFT JOIN users u ON g.uploaded_by = u.id
        LEFT JOIN categories cat ON g.category_id = cat.id
        WHERE $whereClause
        ORDER BY g.display_order ASC, g.created_at DESC
        LIMIT ? OFFSET ?
    ");
    
    $params[] = $pagination['perPage'];
    $params[] = $pagination['offset'];
    $stmt->execute($params);
    $items = $stmt->fetchAll();
    
    successResponse([
        'items' => $items,
        'pagination' => $pagination
    ]);
}

function handleGetGallery($id) {
    if (!$id) {
        errorResponse('Gallery ID is required', 400);
    }
    
    $db = getDB();
    $stmt = $db->prepare("
        SELECT g.*, 
               u.full_name as uploaded_by_name,
               cat.name as category_name,
               cat.slug as category_slug
        FROM gallery g
        LEFT JOIN users u ON g.uploaded_by = u.id
        LEFT JOIN categories cat ON g.category_id = cat.id
        WHERE g.id = ?
    ");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    
    if (!$item) {
        errorResponse('Gallery item not found', 404);
    }
    
    // Increment views
    $updateStmt = $db->prepare("UPDATE gallery SET views_count = views_count + 1 WHERE id = ?");
    $updateStmt->execute([$id]);
    
    successResponse($item);
}

function handleUploadGallery() {
    if (!isset($_FILES['file'])) {
        errorResponse('No file uploaded', 400);
    }
    
    $user = getCurrentUser();
    $db = getDB();
    
    try {
        $uploadResult = uploadFile($_FILES['file'], 'gallery');
        
        $title = sanitize($_POST['title'] ?? 'Untitled');
        $description = sanitize($_POST['description'] ?? '');
        $altText = sanitize($_POST['alt_text'] ?? $title);
        $categoryId = $_POST['category_id'] ?? null;
        $isFeatured = isset($_POST['is_featured']) ? (int)$_POST['is_featured'] : 0;
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        
        $stmt = $db->prepare("
            INSERT INTO gallery (title, description, file_path, file_type, file_size, alt_text, category_id, uploaded_by, is_featured, display_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $title,
            $description,
            $uploadResult['url'],
            $uploadResult['type'],
            $uploadResult['size'],
            $altText,
            $categoryId,
            $user['id'],
            $isFeatured,
            $displayOrder
        ]);
        
        $galleryId = $db->lastInsertId();
        
        logActivity('upload', 'gallery', $galleryId, "Uploaded image: $title");
        
        successResponse([
            'id' => $galleryId,
            'file' => $uploadResult
        ], 'Image uploaded successfully');
        
    } catch (Exception $e) {
        errorResponse($e->getMessage(), 400);
    }
}

function handleUpdateGallery($id) {
    if (!$id) {
        errorResponse('Gallery ID is required', 400);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $user = getCurrentUser();
    $db = getDB();
    
    // Check if item exists
    $checkStmt = $db->prepare("SELECT uploaded_by FROM gallery WHERE id = ?");
    $checkStmt->execute([$id]);
    $item = $checkStmt->fetch();
    
    if (!$item) {
        errorResponse('Gallery item not found', 404);
    }
    
    if ($item['uploaded_by'] != $user['id'] && $user['role'] !== 'admin' && $user['role'] !== 'editor') {
        errorResponse('Permission denied', 403);
    }
    
    // Build update query
    $fields = [];
    $params = [];
    
    if (isset($input['title'])) {
        $fields[] = "title = ?";
        $params[] = sanitize($input['title']);
    }
    
    if (isset($input['description'])) {
        $fields[] = "description = ?";
        $params[] = sanitize($input['description']);
    }
    
    if (isset($input['alt_text'])) {
        $fields[] = "alt_text = ?";
        $params[] = sanitize($input['alt_text']);
    }
    
    if (isset($input['category_id'])) {
        $fields[] = "category_id = ?";
        $params[] = $input['category_id'];
    }
    
    if (isset($input['is_featured'])) {
        $fields[] = "is_featured = ?";
        $params[] = (int)$input['is_featured'];
    }
    
    if (isset($input['display_order'])) {
        $fields[] = "display_order = ?";
        $params[] = (int)$input['display_order'];
    }
    
    $fields[] = "updated_at = NOW()";
    $params[] = $id;
    
    $stmt = $db->prepare("UPDATE gallery SET " . implode(', ', $fields) . " WHERE id = ?");
    $stmt->execute($params);
    
    logActivity('update', 'gallery', $id, 'Gallery item updated');
    
    successResponse(['id' => $id], 'Gallery item updated successfully');
}

function handleDeleteGallery($id) {
    if (!$id) {
        errorResponse('Gallery ID is required', 400);
    }
    
    $user = getCurrentUser();
    $db = getDB();
    
    // Check if item exists
    $checkStmt = $db->prepare("SELECT uploaded_by, file_path FROM gallery WHERE id = ?");
    $checkStmt->execute([$id]);
    $item = $checkStmt->fetch();
    
    if (!$item) {
        errorResponse('Gallery item not found', 404);
    }
    
    if ($item['uploaded_by'] != $user['id'] && $user['role'] !== 'admin') {
        errorResponse('Permission denied', 403);
    }
    
    // Delete file
    $filePath = str_replace(UPLOAD_URL, UPLOAD_DIR, $item['file_path']);
    deleteFile($filePath);
    
    $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    
    logActivity('delete', 'gallery', $id, 'Gallery item deleted');
    
    successResponse([], 'Gallery item deleted successfully');
}

