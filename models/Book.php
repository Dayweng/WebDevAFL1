<?php

class Book
{
    public $id;
    public $title;
    public $author;
    public $is_borrowed;
}

function getAllBooks()
{
    if (!isset($_SESSION['books'])) {
        $_SESSION['books'] = [];
    }

    return $_SESSION['books'];
}