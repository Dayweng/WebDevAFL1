<?php
require_once 'Book.php';

class BookController
{
    public function index()
    {
        $books = getAllBooks();
        require 'book_list.php';
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

        $book = new Book($id, $title, $author);
        $_SESSION['books'][$id] = $book;
    }
}

function toggleBook()
{
    $id = (int)($_POST['id'] ?? 0);

    if (isset($_SESSION['books'][$id])) {
        $book = getAllBooks()[$id];
        $book->is_borrowed = !$book->is_borrowed;
        $_SESSION['books'][$id] = $book;
    }
}

function updateBook()
{
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');

    if (isset($_SESSION['books'][$id]) && $title !== '' && $author !== '') {
        $book = getAllBooks()[$id];
        $book->title = $title;
        $book->author = $author;
        $_SESSION['books'][$id] = $book;
    }
}

function deleteBook()
{
    $id = (int)($_POST['id'] ?? 0);

    if (isset($_SESSION['books'][$id])) {
        unset($_SESSION['books'][$id]);
    }
}