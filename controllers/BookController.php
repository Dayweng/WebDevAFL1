<?php
require_once 'models/Book.php';

class BookController
{
    public function index()
    {
        $books = getAllBooks();
        require 'views/book_list.php';
    }

    public function handlePost()
    {
        $action = $_POST['action'] ?? '';

        if ($action === 'create') {
            createBook();
        }

        if ($action === 'toggle') {
            toggleBook();
        }

        if ($action === 'update') {
            updateBook();
        }

        if ($action === 'delete') {
            deleteBook();
        }

        header('Location: index.php');
        exit;
    }
}

function createBook()
{
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');

    if ($title !== '' && $author !== '') {
        $books = getAllBooks();
        $id = empty($books) ? 1 : max(array_keys($books)) + 1;

        $_SESSION['books'][$id] = [
            'id' => $id,
            'title' => $title,
            'author' => $author,
            'is_borrowed' => false
        ];
    }
}

function toggleBook()
{
    $id = (int)($_POST['id'] ?? 0);

    if (isset($_SESSION['books'][$id])) {
        $_SESSION['books'][$id]['is_borrowed'] = !$_SESSION['books'][$id]['is_borrowed'];
    }
}

function updateBook()
{
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');

    if (isset($_SESSION['books'][$id]) && $title !== '' && $author !== '') {
        $_SESSION['books'][$id]['title'] = $title;
        $_SESSION['books'][$id]['author'] = $author;
    }
}

function deleteBook()
{
    $id = (int)($_POST['id'] ?? 0);

    if (isset($_SESSION['books'][$id])) {
        unset($_SESSION['books'][$id]);
    }
}