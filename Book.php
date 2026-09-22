<?php

class Book {
    // Mock database array stored in session memory for simplicity
    private static function getBooks() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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

    // Business Logic: Add new book (Create)
    public static function create($title, $author) {
        $books = self::getBooks();

        $newId = empty($books) ? 1 : max(array_keys($books)) + 1;

        $books[$newId] = [
            'id' => $newId,
            'title' => $title,
            'author' => $author,
            'is_borrowed' => false
        ];

        $_SESSION['books'] = $books;
        return true;
    }

    // Business Logic: Update title & author (Update)
    public static function update($id, $title, $author) {
        $books = self::getBooks();
        if (isset($books[$id])) {
            $books[$id]['title'] = $title;
            $books[$id]['author'] = $author;
            $_SESSION['books'] = $books;
            return true;
        }
        return false;
    }

    // Business Logic: Delete book (Delete)
    public static function delete($id) {
        $books = self::getBooks();
        if (isset($books[$id])) {
            unset($books[$id]);
            $_SESSION['books'] = $books;
            return true;
        }
        return false;
    }
}