<?php
require_once 'models/Book.php';

class BookController {

    // Action: Display catalog
    public function index() {
        // 1. Fetch data from Model
        $books = Book::all();

        // 2. Pass data and load View
        require 'views/book_list.php';
    }

    // Action: Borrow or Return a book
    public function toggle() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];

            // 1. Tell Model to execute business logic
            Book::toggleBorrow($id);
        }

        // 2. Redirect back to main list
        header('Location: index.php');
        exit;
    }

    // Action: Add new book
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');

            if (!empty($title) && !empty($author)) {
                Book::create($title, $author);
            }
        }

        header('Location: index.php');
        exit;
    }

    // Action: Update title and author
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');

            if ($id > 0 && !empty($title) && !empty($author)) {
                Book::update($id, $title, $author);
            }
        }

        header('Location: index.php');
        exit;
    }

    // Action: Delete a book
    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            Book::delete($id);
        }

        header('Location: index.php');
        exit;
    }
}