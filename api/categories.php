<?php
/**
 * Categories API
 * KSS Updates System
 */

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        if ($method === 'GET') {
            handleListCategories();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    default:
        errorResponse('Invalid action', 400);
}

function handleListCategories() {
    $db = getDB();
    
    $type = $_GET['type'] ?? null;
    
    $where = ["1=1"];
    $params = [];
    
    if ($type) {
        $where[] = "type = ?";
        $params[] = $type;
    }
    
    $whereClause = implode(' AND ', $where);
    
    $stmt = $db->prepare("
        SELECT * FROM categories 
        WHERE $whereClause
        ORDER BY display_order ASC, name ASC
    ");
    $stmt->execute($params);
    $categories = $stmt->fetchAll();
    
    successResponse($categories);
}

