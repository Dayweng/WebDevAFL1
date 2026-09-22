<?php
session_start();

require_once 'controllers/BookController.php';

$controller = new BookController();
$action = $_GET['action'] ?? 'index';

// Simple routing mechanism
if ($action === 'create') {
    $controller->create();
} elseif ($action === 'toggle') {
    $controller->toggle();
} elseif ($action === 'delete') {
    $controller->delete();
} else {
    $controller->index();
}