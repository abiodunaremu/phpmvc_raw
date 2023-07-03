<?php
namespace Service\Book\BookCategory;

class BookCategory{

    private $code;
    private $name;
    private $description;
    private $fileGroupCode;
    private $createdTime;
    private $lastUpdatedTime;
    private $status;

    public function __construct($code, $name, $description
    , $fileGroupCode, $createdTime, $lastUpdatedTime
    , $status){
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->fileGroupCode = $fileGroupCode;
        $this->createdTime = $createdTime;
        $this->lastUpdatedTime = $lastUpdatedTime;
        $this->status = $status;
    }

    public function getCode(){
        return $this->code;
    }

    public function setCode($code){
        $this->code = $code;
    }
    
    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }
    
    public function setDescription($description){
        $this->description = $description;
    }
    
    public function getDescription(){
        return $this->description;
    }
    
    
    public function setFileGroupCode($fileGroupCode){
        $this->fileGroupCode = $fileGroupCode;
    }
    
    public function getFileGroupCode(){
        return $this->fileGroupCode;
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