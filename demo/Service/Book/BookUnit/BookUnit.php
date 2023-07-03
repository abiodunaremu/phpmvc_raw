<?php
namespace Service\Book\BookUnit;

class BookUnit{

    private $code;
    private $name;
    private $createdTime;
    private $lastUpdatedTime;
    private $status;

    public function __construct($code, $name
    , $createdTime, $lastUpdatedTime, $status){
        $this->code = $code;
        $this->name = $name;
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