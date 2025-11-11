<?php
/**
 * Content API (News, Blogs, Announcements)
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
            handleListContent();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'get':
        if ($method === 'GET') {
            handleGetContent($id);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'create':
        if ($method === 'POST') {
            requireLogin();
            handleCreateContent();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'update':
        if ($method === 'PUT' || $method === 'POST') {
            requireLogin();
            handleUpdateContent($id);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'delete':
        if ($method === 'DELETE' || $method === 'POST') {
            requireLogin();
            handleDeleteContent($id);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    default:
        errorResponse('Invalid action', 400);
}

function handleListContent() {
    $db = getDB();
    
    $type = $_GET['type'] ?? null;
    $category = $_GET['category'] ?? null;
    $status = $_GET['status'] ?? 'published';
    $page = (int)($_GET['page'] ?? 1);
    $perPage = (int)($_GET['per_page'] ?? 10);
    $featured = isset($_GET['featured']) ? (bool)$_GET['featured'] : null;
    
    // Build query
    $where = ["status = ?"];
    $params = [$status];
    
    if ($type) {
        $where[] = "content_type = ?";
        $params[] = $type;
    }
    
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
    $countStmt = $db->prepare("SELECT COUNT(*) FROM content WHERE $whereClause");
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();
    
    // Get pagination info
    $pagination = paginate($page, $perPage, $total);
    
    // Get content
    $stmt = $db->prepare("
        SELECT c.*, 
               u.full_name as author_name,
               cat.name as category_name,
               cat.slug as category_slug
        FROM content c
        LEFT JOIN users u ON c.author_id = u.id
        LEFT JOIN categories cat ON c.category_id = cat.id
        WHERE $whereClause
        ORDER BY c.created_at DESC
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

function handleGetContent($id) {
    if (!$id) {
        errorResponse('Content ID is required', 400);
    }
    
    $db = getDB();
    $stmt = $db->prepare("
        SELECT c.*, 
               u.full_name as author_name,
               u.email as author_email,
               cat.name as category_name,
               cat.slug as category_slug
        FROM content c
        LEFT JOIN users u ON c.author_id = u.id
        LEFT JOIN categories cat ON c.category_id = cat.id
        WHERE c.id = ?
    ");
    $stmt->execute([$id]);
    $content = $stmt->fetch();
    
    if (!$content) {
        errorResponse('Content not found', 404);
    }
    
    // Increment views if published
    if ($content['status'] === 'published') {
        $updateStmt = $db->prepare("UPDATE content SET views_count = views_count + 1 WHERE id = ?");
        $updateStmt->execute([$id]);
    }
    
    // Get tags
    $tagsStmt = $db->prepare("
        SELECT t.id, t.name, t.slug
        FROM tags t
        INNER JOIN content_tags ct ON t.id = ct.tag_id
        WHERE ct.content_id = ?
    ");
    $tagsStmt->execute([$id]);
    $content['tags'] = $tagsStmt->fetchAll();
    
    successResponse($content);
}

function handleCreateContent() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $required = ['title', 'content', 'content_type'];
    foreach ($required as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            errorResponse("Field '$field' is required", 400);
        }
    }
    
    $user = getCurrentUser();
    $db = getDB();
    
    $title = sanitize($input['title']);
    $slug = generateSlug($title);
    
    // Check if slug exists
    $checkStmt = $db->prepare("SELECT id FROM content WHERE slug = ?");
    $checkStmt->execute([$slug]);
    if ($checkStmt->fetch()) {
        $slug .= '-' . time();
    }
    
    $stmt = $db->prepare("
        INSERT INTO content (title, slug, excerpt, content, featured_image, category_id, author_id, content_type, status, is_featured, published_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $status = $input['status'] ?? 'draft';
    $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;
    
    $stmt->execute([
        $title,
        $slug,
        sanitize($input['excerpt'] ?? ''),
        $input['content'],
        $input['featured_image'] ?? null,
        $input['category_id'] ?? null,
        $user['id'],
        $input['content_type'],
        $status,
        isset($input['is_featured']) ? (int)$input['is_featured'] : 0,
        $publishedAt
    ]);
    
    $contentId = $db->lastInsertId();
    
    // Handle tags
    if (isset($input['tags']) && is_array($input['tags'])) {
        handleContentTags($db, $contentId, $input['tags']);
    }
    
    logActivity('create', 'content', $contentId, "Created {$input['content_type']}: $title");
    
    successResponse(['id' => $contentId], 'Content created successfully');
}

function handleUpdateContent($id) {
    if (!$id) {
        errorResponse('Content ID is required', 400);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $user = getCurrentUser();
    $db = getDB();
    
    // Check if content exists and user has permission
    $checkStmt = $db->prepare("SELECT author_id FROM content WHERE id = ?");
    $checkStmt->execute([$id]);
    $content = $checkStmt->fetch();
    
    if (!$content) {
        errorResponse('Content not found', 404);
    }
    
    if ($content['author_id'] != $user['id'] && $user['role'] !== 'admin' && $user['role'] !== 'editor') {
        errorResponse('Permission denied', 403);
    }
    
    // Build update query
    $fields = [];
    $params = [];
    
    if (isset($input['title'])) {
        $fields[] = "title = ?";
        $params[] = sanitize($input['title']);
        
        // Update slug if title changed
        $newSlug = generateSlug($input['title']);
        $checkStmt = $db->prepare("SELECT id FROM content WHERE slug = ? AND id != ?");
        $checkStmt->execute([$newSlug, $id]);
        if (!$checkStmt->fetch()) {
            $fields[] = "slug = ?";
            $params[] = $newSlug;
        }
    }
    
    if (isset($input['excerpt'])) {
        $fields[] = "excerpt = ?";
        $params[] = sanitize($input['excerpt']);
    }
    
    if (isset($input['content'])) {
        $fields[] = "content = ?";
        $params[] = $input['content'];
    }
    
    if (isset($input['featured_image'])) {
        $fields[] = "featured_image = ?";
        $params[] = $input['featured_image'];
    }
    
    if (isset($input['category_id'])) {
        $fields[] = "category_id = ?";
        $params[] = $input['category_id'];
    }
    
    if (isset($input['status'])) {
        $fields[] = "status = ?";
        $params[] = $input['status'];
        
        if ($input['status'] === 'published') {
            $fields[] = "published_at = COALESCE(published_at, NOW())";
        }
    }
    
    if (isset($input['is_featured'])) {
        $fields[] = "is_featured = ?";
        $params[] = (int)$input['is_featured'];
    }
    
    $fields[] = "updated_at = NOW()";
    $params[] = $id;
    
    $stmt = $db->prepare("UPDATE content SET " . implode(', ', $fields) . " WHERE id = ?");
    $stmt->execute($params);
    
    // Handle tags
    if (isset($input['tags']) && is_array($input['tags'])) {
        handleContentTags($db, $id, $input['tags'], true);
    }
    
    logActivity('update', 'content', $id, 'Content updated');
    
    successResponse(['id' => $id], 'Content updated successfully');
}

function handleDeleteContent($id) {
    if (!$id) {
        errorResponse('Content ID is required', 400);
    }
    
    $user = getCurrentUser();
    $db = getDB();
    
    // Check if content exists and user has permission
    $checkStmt = $db->prepare("SELECT author_id, featured_image FROM content WHERE id = ?");
    $checkStmt->execute([$id]);
    $content = $checkStmt->fetch();
    
    if (!$content) {
        errorResponse('Content not found', 404);
    }
    
    if ($content['author_id'] != $user['id'] && $user['role'] !== 'admin') {
        errorResponse('Permission denied', 403);
    }
    
    // Delete featured image if exists
    if ($content['featured_image']) {
        deleteFile(UPLOAD_DIR . $content['featured_image']);
    }
    
    $stmt = $db->prepare("DELETE FROM content WHERE id = ?");
    $stmt->execute([$id]);
    
    logActivity('delete', 'content', $id, 'Content deleted');
    
    successResponse([], 'Content deleted successfully');
}

function handleContentTags($db, $contentId, $tags, $replace = false) {
    if ($replace) {
        $deleteStmt = $db->prepare("DELETE FROM content_tags WHERE content_id = ?");
        $deleteStmt->execute([$contentId]);
    }
    
    foreach ($tags as $tagName) {
        $tagName = trim($tagName);
        if (empty($tagName)) continue;
        
        $tagSlug = generateSlug($tagName);
        
        // Get or create tag
        $tagStmt = $db->prepare("SELECT id FROM tags WHERE slug = ?");
        $tagStmt->execute([$tagSlug]);
        $tag = $tagStmt->fetch();
        
        if (!$tag) {
            $insertTagStmt = $db->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
            $insertTagStmt->execute([$tagName, $tagSlug]);
            $tagId = $db->lastInsertId();
        } else {
            $tagId = $tag['id'];
        }
        
        // Link tag to content
        $linkStmt = $db->prepare("INSERT IGNORE INTO content_tags (content_id, tag_id) VALUES (?, ?)");
        $linkStmt->execute([$contentId, $tagId]);
    }
}

