<?php
namespace Service\Admin;

class Admin{

    private $adminId;
    private $fullName;
    private $username;
    private $password;
    private $description;
    private $address;
    private $country;
    private $phoneNumber;
    private $email;
    private $image;
    private $dateSignup;
    private $lastUpdate;
    private $status;

    public function __construct($adminId, $fullName
    , $username, $password, $description, $address
    , $country, $phoneNumber, $email, $image
    ,  $dateSignup, $lastUpdate, $status){

        $this->adminId = $adminId;
        $this->fullName = $fullName;
        $this->username = $username;
        $this->password = $password;
        $this->description = $description;
        $this->address = $address;
        $this->country = $country;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->image = $image;
        $this->dateSignup = $dateSignup;
        $this->lastUpdate = $lastUpdate;
        $this->status = $status;
    }

    public function setAdminId($adminId){
        return $this->adminId = $adminId;
    }
    public function getAdminId(){
        return $this->adminId;
    }

    public function getFullName(){
        return $this->fullName;
    }
    public function setFullName($fullName){
        $this->fullName = $fullName;
    }
    
    public function getDescription(){
        return $this->description;
    }
    public function setDescription($description){
        $this->description = $description;
    }
    
    public function getAddress(){
        return $this->address;
    }
    public function setAddress($address){
        $this->address = $address;
    }
    
    public function getCountry(){
        return $this->country;
    }
    public function setCountry($country){
        $this->country = $country;
    }
    
    public function getPhoneNumber(){
        return $this->phoneNumber;
    }
    public function setPhoneNumber($phoneNumber){
        $this->phoneNumber = $phoneNumber;
    }
    
    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    
    public function getPassword(){
        return $this->password;
    }
    public function setPassword($password){
        $this->password = $password;
    }

    public function getImage(){
        return $this->image;
    }
    public function setImage($image){
        $this->image = $image;
    }
    
    public function getDateSignup(){
        return $this->dateSignup;
    }
    public function setDateSignup($dateSignup){
        $this->dateSignup = $dateSignup;
    }
    
    public function getLastUpdate(){
        return $this->lastUpdate;
    }
    public function setLastUpdate($lastUpdate){
        $this->lastUpdate = $lastUpdate;
    }
    
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }
}

?>