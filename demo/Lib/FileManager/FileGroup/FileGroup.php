<?php
namespace Lib\FileManager\FileGroup;

class FileGroup{

    private $id;
    private $userId;
    private $fileCreatorId; //method that initiated the creation within the app
    private $createdTime;
    private $lastUpdate;
    private $status;

    public function __construct($id, $userId, $initiatorId,
    $createdTime, $lastUpdateTime, $status){

        $this->id = $id;
        $this->userId = $userId;
        $this->fileCreatorId = $fileCreatorId;
        $this->createdTime = $createdTime;
        $this->lastUpdateTime = $lastUpdateTime;
        $this->status = $status;
    }

    public function getId(){
        return $this->Id;
    }

    public function setId($Id){
        $this->Id = $Id;
    }
    
    public function getUserId(){
        return $this->userId;
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }
    
    public function setFileCreatorId($fileCreatorId){
        $this->fileCreatorId = $fileCreatorId;
    }
    
    public function getFileCreatorId(){
        return $this->fileCreatorId;
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