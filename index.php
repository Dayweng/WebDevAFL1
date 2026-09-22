<?php
require_once 'controllers/BookController.php';
session_start();

$controller = new BookController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handlePost();
} else {
    $controller->index();
}