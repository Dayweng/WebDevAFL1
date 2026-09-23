<?php

class Book
{
    private $id;
    public $title;
    public $author;
    public $is_borrowed;
    public $borrower_id;

    public function __construct($id, $title, $author, $is_borrowed = false, $borrower_id = null)
    {
        $this->id = $id;
        $this->title = $title;
            $this->author = $author;
            $this->is_borrowed = $is_borrowed;
            $this->borrower_id = $borrower_id;
    }

    public function getId()
    {
        return $this->id;
    }
}
?>