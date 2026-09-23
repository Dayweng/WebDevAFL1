<?php

class Book
{
    public $id;
    public $title;
    public $author;
    public $is_borrowed;

    public function __construct($id, $title, $author, $is_borrowed = false)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->is_borrowed = $is_borrowed;
    }
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