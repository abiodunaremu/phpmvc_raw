<?php
namespace Service\Book;

class Book{

    private $code;
    private $name;
    private $description;
    private $bookType;
    private $bookCategory;
    private $price;
    private $currency;
    private $quantity;
    private $bookUnit;
    private $fileGroupCode;
    private $weight;
    private $length;
    private $bredth;
    private $height;
    private $quantityInStock;
    private $stockStatus;
    private $freeShippingStatus;
    private $discount;
    private $createdTime;
    private $lastUpdatedTime;
    private $status;

    public function __construct($code, $name, $description
    , $bookType, $bookCategory, $price, $currency,$quantity
    , $bookUnit, $fileGroupCode, $weight, $length
    , $bredth, $height, $quantityInStock, $stockStatus
    , $freeShippingStatus, $discount, $createdTime, $lastUpdatedTime
    , $status){
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->bookType = $bookType;
        $this->bookCategory = $bookCategory;
        $this->price = $price;
        $this->currency = $currency;
        $this->quantity = $quantity;
        $this->bookUnit = $bookUnit;
        $this->fileGroupCode = $fileGroupCode;
        $this->weight = $weight;
        $this->length = $length;
        $this->bredth = $bredth;
        $this->height = $height;
        $this->quantityInStock = $quantityInStock;
        $this->stockStatus = $stockStatus;
        $this->freeShippingStatus = $freeShippingStatus;
        $this->discount = $discount;
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
    
    public function setBookType($bookType){
        $this->bookType = $bookType;
    }    
    public function getBookType(){
        return $this->bookType;
    }
    
    public function setBookCategory($bookCategory){
        $this->bookCategory = $bookCategory;
    }    
    public function getBookCategory(){
        return $this->bookCategory;
    }
    
    public function setPrice($price){
        $this->price = $price;
    }    
    public function getPrice(){
        return $this->price;
    }
    
    public function setCurrency($currency){
        $this->currency = $currency;
    }    
    public function getCurrency(){
        return $this->currency;
    }
    
    public function setQuantity($quantity){
        $this->quantity = $quantity;
    }    
    public function getQuantity(){
        return $this->quantity;
    }
    
    public function setBookUnit($bookUnit){
        $this->bookUnit = $bookUnit;
    }    
    public function getBookUnit(){
        return $this->bookUnit;
    }
    
    public function setFileGroupCode($fileGroupCode){
        $this->fileGroupCode = $fileGroupCode;
    }    
    public function getFileGroupCode(){
        return $this->fileGroupCode;
    }
    
    public function setWeight($weight){
        $this->weight = $weight;
    }    
    public function getWeight(){
        return $this->weight;
    }
    
    public function setLength($length){
        $this->length = $length;
    }    
    public function getLength(){
        return $this->length;
    }
    
    public function setBredth($bredth){
        $this->bredth = $bredth;
    }    
    public function getBredth(){
        return $this->bredth;
    }
    
    public function setHeigth($height){
        $this->height = $height;
    }    
    public function getHeight(){
        return $this->height;
    }
        
    public function setQuantityInStock($quantityInStock){
        $this->quantityInStock = $quantityInStock;
    }    
    public function getQuantityInStock(){
        return $this->quantityInStock;
    }
    
    public function setDiscount($discount){
        $this->discount = $discount;
    }    
    public function getDiscount(){
        return $this->discount;
    }
    
    public function setStockStatus($stockStatus){
        $this->stockStatus = $stockStatus;
    }    
    public function getStockStatus(){
        return $this->stockStatus;
    }
    
    public function setFreeShippingStatus($freeShippingStatus){
        $this->freeShippingStatus = $freeShippingStatus;
    }    
    public function getFreeShippingStatus(){
        return $this->freeShippingStatus;
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