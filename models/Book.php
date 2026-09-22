<?php

class Book {
    // Mock database array stored in session memory for simplicity
    private static function getBooks() {
        if (!isset($_SESSION['books'])) {
            $_SESSION['books'] = [
                1 => ['id' => 1, 'title' => '1984', 'author' => 'George Orwell', 'is_borrowed' => false],
                2 => ['id' => 2, 'title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'is_borrowed' => false],
                3 => ['id' => 3, 'title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'is_borrowed' => true]
            ];
        }
        return $_SESSION['books'];
    }

    // Retrieve all books
    public static function all() {
        return self::getBooks();
    }

    // Business Logic: Toggle borrowed status
    public static function toggleBorrow($id) {
        $books = self::getBooks();
        if (isset($books[$id])) {
            $books[$id]['is_borrowed'] = !$books[$id]['is_borrowed'];
            $_SESSION['books'] = $books;
            return true;
        }
        return false;
    }
}