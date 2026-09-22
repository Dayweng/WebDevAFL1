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

    // Action: Add a new book
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $is_borrowed = isset($_POST['is_borrowed']) ? (bool)$_POST['is_borrowed'] : false;

            if (!empty($title) && !empty($author)) {
                // 1. Tell Model to create a new book
                Book::create($title, $author, $is_borrowed);
            }
        }

        // 2. Redirect back to main list
        header('Location: index.php');
        exit;
    }

    // Action: Delete a book
    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];

            // 1. Tell Model to delete the book
            Book::delete($id);
        }

        // 2. Redirect back to main list
        header('Location: index.php');
        exit;
    }
}