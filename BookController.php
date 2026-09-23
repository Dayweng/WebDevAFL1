<?php
require_once 'Book.php';
require_once 'Member.php';

class BookController
{
    public function index()
    {
        $books = getAllBooks();
        $members = getAllMembers();
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
        if ($action === 'register_member') {
            registerMember();
        }
        if ($action === 'borrow') {
            borrowBook();
        }
        if ($action === 'return') {
            returnBook();
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
        $_SESSION['books'][$id] = new Book($id, $title, $author);
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

function registerMember()
{
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $_SESSION['error'] = 'Member name cannot be empty.';
        return;
    }

    foreach (getAllMembers() as $member) {
        if (strcasecmp($member->name, $name) === 0) {
            $_SESSION['error'] = 'This member is already registered.';
            return;
        }
    }

    $members = getAllMembers();
    $id = empty($members) ? 1 : max(array_keys($members)) + 1;
    $_SESSION['members'][$id] = new Member($id, $name);
}

function borrowBook()
{
    $bookId = (int)($_POST['id'] ?? 0);
    $memberId = (int)($_POST['member_id'] ?? 0);
    $members = getAllMembers();

    if (!isset($_SESSION['books'][$bookId])) {
        return;
    }

    if (!isset($members[$memberId])) {
        $_SESSION['error'] = 'Please select a registered member.';
        return;
    }

    $book = getAllBooks()[$bookId];

    if ($book->is_borrowed) {
        $_SESSION['error'] = 'This book is already borrowed.';
        return;
    }

    $book->is_borrowed = true;
    $book->borrower_id = $memberId;
    $_SESSION['books'][$bookId] = $book;
}

function returnBook()
{
    $bookId = (int)($_POST['id'] ?? 0);

    if (isset($_SESSION['books'][$bookId])) {
        $book = getAllBooks()[$bookId];
        $book->is_borrowed = false;
        $book->borrower_id = null;
        $_SESSION['books'][$bookId] = $book;
    }
}

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
            $_SESSION['books'][$id] = new Book(
                $book['id'],
                $book['title'],
                $book['author'],
                $book['is_borrowed'],
                $book['borrower_id'] ?? null
            );
        }
    }
    return $_SESSION['books'];
}

function getAllMembers()
{
    if (!isset($_SESSION['members'])) {
        $_SESSION['members'] = [];
    }

    foreach ($_SESSION['members'] as $id => $member) {
        if (is_array($member)) {
            $_SESSION['members'][$id] = new Member(
                $member['id'],
                $member['name']
            );
        }
    }
    return $_SESSION['members'];
}
?>