<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$id = $_GET['id'] ?? null;

if ($id && deleteRecord($pdo, $id)) {
    header('Location: index.php?success=1');
} else {
    header('Location: index.php?error=1');
}
exit;