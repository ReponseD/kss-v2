<?php
/**
 * Homepage Management API
 * KSS CMS
 */

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../config/functions.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_sections':
        if ($method === 'GET') {
            handleGetSections();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'get_section':
        if ($method === 'GET') {
            handleGetSection($_GET['key'] ?? null);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'update_section':
        if ($method === 'PUT' || $method === 'POST') {
            requireAuth();
            handleUpdateSection();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'get_banners':
        if ($method === 'GET') {
            handleGetBanners();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'create_banner':
        if ($method === 'POST') {
            requireAuth();
            handleCreateBanner();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'update_banner':
        if ($method === 'PUT' || $method === 'POST') {
            requireAuth();
            handleUpdateBanner($_GET['id'] ?? null);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'delete_banner':
        if ($method === 'DELETE' || $method === 'POST') {
            requireAuth();
            handleDeleteBanner($_GET['id'] ?? null);
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    default:
        errorResponse('Invalid action', 400);
}

function handleGetSections() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM homepage_sections WHERE is_active = 1 ORDER BY display_order ASC");
    $sections = $stmt->fetchAll();
    
    // Convert to key-value pairs for easier access
    $result = [];
    foreach ($sections as $section) {
        $result[$section['section_key']] = $section;
    }
    
    successResponse($result);
}

function handleGetSection($key) {
    if (!$key) {
        errorResponse('Section key is required', 400);
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM homepage_sections WHERE section_key = ?");
    $stmt->execute([$key]);
    $section = $stmt->fetch();
    
    if (!$section) {
        errorResponse('Section not found', 404);
    }
    
    successResponse($section);
}

function handleUpdateSection() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['section_key'])) {
        errorResponse('Section key is required', 400);
    }
    
    $user = getCurrentUser();
    $db = getDB();
    
    // Check if section exists
    $checkStmt = $db->prepare("SELECT id FROM homepage_sections WHERE section_key = ?");
    $checkStmt->execute([$input['section_key']]);
    $exists = $checkStmt->fetch();
    
    if ($exists) {
        // Update existing
        $stmt = $db->prepare("
            UPDATE homepage_sections 
            SET content = ?, image_url = ?, updated_by = ?, updated_at = NOW()
            WHERE section_key = ?
        ");
        $stmt->execute([
            $input['content'] ?? null,
            $input['image_url'] ?? null,
            $user['id'],
            $input['section_key']
        ]);
    } else {
        // Create new
        $stmt = $db->prepare("
            INSERT INTO homepage_sections (section_key, section_name, content, image_url, updated_by)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $input['section_key'],
            $input['section_name'] ?? $input['section_key'],
            $input['content'] ?? null,
            $input['image_url'] ?? null,
            $user['id']
        ]);
    }
    
    logActivity('update', 'homepage_section', $input['section_key'], 'Updated homepage section');
    successResponse([], 'Section updated successfully');
}

function handleGetBanners() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM homepage_banners WHERE is_active = 1 ORDER BY display_order ASC");
    $banners = $stmt->fetchAll();
    
    successResponse($banners);
}

function handleCreateBanner() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['image_url'])) {
        errorResponse('Image URL is required', 400);
    }
    
    $db = getDB();
    $stmt = $db->prepare("
        INSERT INTO homepage_banners (title, subtitle, description, image_url, button_text, button_link, display_order, is_active)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $input['title'] ?? null,
        $input['subtitle'] ?? null,
        $input['description'] ?? null,
        $input['image_url'],
        $input['button_text'] ?? null,
        $input['button_link'] ?? null,
        $input['display_order'] ?? 0,
        isset($input['is_active']) ? (int)$input['is_active'] : 1
    ]);
    
    $bannerId = $db->lastInsertId();
    logActivity('create', 'homepage_banner', $bannerId, 'Created homepage banner');
    
    successResponse(['id' => $bannerId], 'Banner created successfully');
}

function handleUpdateBanner($id) {
    if (!$id) {
        errorResponse('Banner ID is required', 400);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $db = getDB();
    
    $fields = [];
    $params = [];
    
    if (isset($input['title'])) {
        $fields[] = "title = ?";
        $params[] = $input['title'];
    }
    if (isset($input['subtitle'])) {
        $fields[] = "subtitle = ?";
        $params[] = $input['subtitle'];
    }
    if (isset($input['description'])) {
        $fields[] = "description = ?";
        $params[] = $input['description'];
    }
    if (isset($input['image_url'])) {
        $fields[] = "image_url = ?";
        $params[] = $input['image_url'];
    }
    if (isset($input['button_text'])) {
        $fields[] = "button_text = ?";
        $params[] = $input['button_text'];
    }
    if (isset($input['button_link'])) {
        $fields[] = "button_link = ?";
        $params[] = $input['button_link'];
    }
    if (isset($input['display_order'])) {
        $fields[] = "display_order = ?";
        $params[] = (int)$input['display_order'];
    }
    if (isset($input['is_active'])) {
        $fields[] = "is_active = ?";
        $params[] = (int)$input['is_active'];
    }
    
    $fields[] = "updated_at = NOW()";
    $params[] = $id;
    
    $stmt = $db->prepare("UPDATE homepage_banners SET " . implode(', ', $fields) . " WHERE id = ?");
    $stmt->execute($params);
    
    logActivity('update', 'homepage_banner', $id, 'Updated homepage banner');
    successResponse(['id' => $id], 'Banner updated successfully');
}

function handleDeleteBanner($id) {
    if (!$id) {
        errorResponse('Banner ID is required', 400);
    }
    
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM homepage_banners WHERE id = ?");
    $stmt->execute([$id]);
    
    logActivity('delete', 'homepage_banner', $id, 'Deleted homepage banner');
    successResponse([], 'Banner deleted successfully');
}

