<?php

class Book
{
    private $id;
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

    public function getId()
    {
        return $this->id;
    }
}