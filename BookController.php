<?php
require_once 'Book.php';

class BookController
{
    public function index()
    {
        $books = getAllBooks();
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
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
        if (bookExistsWithTitleAndAuthor($title, $author)) {
            $_SESSION['error'] = 'A book with this title and author already exists.';
            return;
        }

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
        if (bookExistsWithTitleAndAuthor($title, $author, $id)) {
            $_SESSION['error'] = 'A book with this title and author already exists.';
            return;
        }

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


//Checking double and get all books
function bookExistsWithTitleAndAuthor($title, $author, $excludedId = null)
{
    foreach (getAllBooks() as $book) {
        if ($excludedId !== null && $book->getId() === $excludedId) {
            continue;
        }

        if ($book->title === $title && $book->author === $author) {
            return true;
        }
    }

    return false;
}

function getAllBooks()
{
    if (!isset($_SESSION['books'])) {
        $_SESSION['books'] = [];
    }

    foreach ($_SESSION['books'] as $id => $book) {
        if (is_array($book)) {
            $bookObject = new Book(
                $book['id'],
                $book['title'],
                $book['author'],
                $book['is_borrowed']
            );
            $_SESSION['books'][$id] = $bookObject;
        }
    }

    return $_SESSION['books'];
}