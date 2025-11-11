<?php
/**
 * Authentication API
 * KSS Updates System
 */

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../config/functions.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        if ($method === 'POST') {
            handleLogin();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'logout':
        if ($method === 'POST') {
            handleLogout();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    case 'check':
        if ($method === 'GET') {
            handleCheckAuth();
        } else {
            errorResponse('Method not allowed', 405);
        }
        break;
        
    default:
        errorResponse('Invalid action', 400);
}

function handleLogin() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['username']) || !isset($input['password'])) {
        errorResponse('Username and password are required', 400);
    }
    
    $username = sanitize($input['username']);
    $password = $input['password'];
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND status = 'active'");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password_hash'])) {
        logActivity('login_failed', 'user', null, "Failed login attempt for: $username");
        errorResponse('Invalid username or password', 401);
    }
    
    // Update last login
    $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $stmt->execute([$user['id']]);
    
    // Create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    
    // Log activity
    logActivity('login', 'user', $user['id'], 'User logged in');
    
    successResponse([
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role']
        ]
    ], 'Login successful');
}

function handleLogout() {
    if (isLoggedIn()) {
        $user = getCurrentUser();
        logActivity('logout', 'user', $user['id'], 'User logged out');
    }
    
    session_destroy();
    successResponse([], 'Logout successful');
}

function handleCheckAuth() {
    if (!isLoggedIn()) {
        errorResponse('Not authenticated', 401);
    }
    
    $user = getCurrentUser();
    successResponse([
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role']
        ]
    ]);
}

