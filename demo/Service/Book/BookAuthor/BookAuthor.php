<?php
namespace Service\Book\BookAuthor;

class BookAuthor{

    private $bookCode;
    private $bookName;
    private $authorCode;
    private $authorName;
    private $createdTime;
    private $lastUpdatedTime;
    private $status;

    public function __construct($bookCode, $bookName, $authorCode,
    $authorName, $createdTime, $lastUpdatedTime, $status){

        $this->bookCode = $bookCode;
        $this->bookName = $bookName;
        $this->authorCode = $authorCode;
        $this->authorName = $authorName;
        $this->createdTime = $createdTime;
        $this->lastUpdatedTime = $lastUpdatedTime;
        $this->status = $status;
    }

    public function getBookCode(){
        return $this->bookCode;
    }
    public function setBookCode($bookCode){
        $this->bookCode = $bookCode;
    }

    public function getBookName(){
        return $this->bookName;
    }
    public function setBookName($bookCode){
        $this->bookName = $bookName;
    }
    
    public function getAuthorCode(){
        return $this->authorCode;
    }
    public function setAuthorCode($authorCode){
        $this->authorCode = $authorCode;
    }    
    public function getAuthorName(){
        return $this->authorName;
    }
    public function setAuthorName($authorName){
        $this->authorName = $authorName;
    }

    public function setCreatedTime($createdTime){
        $this->createdTime = $createdTime;
    }
    
    public function getCreatedTime(){
        return $this->createdTime;
    }

    public function setLastUpdatedTime($lastUpdatedTime){
        $this->lastUpdatedTime = $lastUpdatedTime;
    }
    
    public function getLastUpdatedTime(){
        return $this->lastUpdatedTime;
    }

    public function setStatus($status){
        $this->status = $status;
    }
    
    public function getStatus(){
        return $this->status;
    }
}

?>