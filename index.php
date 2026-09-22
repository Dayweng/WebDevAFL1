<?php
session_start();

require_once 'controllers/BookController.php';

$controller = new BookController();
$action = $_GET['action'] ?? 'index';

// Simple routing mechanism
if ($action === 'toggle') {
    $controller->toggle();
} else {
    $controller->index();
}