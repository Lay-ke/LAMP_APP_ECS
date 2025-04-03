<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    
    switch ($action) {
        case 'update':
            $data = [
                'id' => $_POST['id'] ?? '',
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            
            if (updateRecord($pdo, $data)) {
                header('Location: index.php?success=1');
            } else {
                header('Location: index.php?error=1');
            }
            break;
            
        default: // create
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            
            if (saveRecord($pdo, $data)) {
                header('Location: index.php?success=1');
            } else {
                header('Location: index.php?error=1');
            }
            break;
    }
    exit;
}