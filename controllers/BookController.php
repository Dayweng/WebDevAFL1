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
}