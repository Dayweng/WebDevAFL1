<?php

class Book {

    private static function getBooks() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['books'])) {
            $_SESSION['books'] = [];
        }

        return $_SESSION['books'];
    }

    //get books
    public static function all() {
        return self::getBooks();
    }

    //borrow or return book
    public static function toggleBorrow($id) {
        $books = self::getBooks();
        if (isset($books[$id])) {
            $books[$id]['is_borrowed'] = !$books[$id]['is_borrowed'];
            $_SESSION['books'] = $books;
            return true;
        }
        return false;
    }

    //create new book
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

    //update book title and author
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

    //delete book
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