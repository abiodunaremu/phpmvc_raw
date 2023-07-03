<?php
namespace Service\User;

class User{

    private $userId;
    private $firstName;
    private $middleName;
    private $lastName;
    private $dateOfBirth;
    private $gender;
    private $nationality;
    private $phoneNumber;
    private $email;
    private $password;
    private $alias;
    private $image;
    private $dateSignup;
    private $accessType;
    private $userType;
    private $status;

    public function __construct($userId, $firstName, $middleName,
    $lastName, $dateOfBirth, $gender, $nationality, 
    $phoneNumber, $email, $password, $image, 
    $dateSignup, $accessType, $status){

        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->nationality = $nationality;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->password = $password;
        $this->image = $image;
        $this->dateSignup = $dateSignup;
        $this->accessType = $accessType;
        $this->status = $status;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }
    
    public function getMiddleName(){
        return $this->middleName;
    }

    public function setMiddleName($middleName){
        $this->middleName = $middleName;
    }
    
    public function getLastName(){
        return $this->lastName;
    }

    public function setLastName($lastName){
        $this->lastName = $lastName;
    }
    
    public function getDateOfBirth(){
        return $this->dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth){
        $this->dateOfBirth = $dateOfBirth;
    }
 
    public function getGender(){
        if($this->gender === 'M'){
            return "Male";
        }else if($this->gender === 'F'){
            return "Female";
        }
        return $this->gender;
    }

    public function setGender($gender){
        $this->gender = $gender;
    }
    
    public function getNationality(){
        return $this->nationality;
    }

    public function setNationality($nationality){
        $this->nationality = $nationality;
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
    
    public function getAlias(){
        return $this->alias;
    }

    public function setAlias($alias){
        $this->alias = $alias;
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
    
    public function getAccessType(){
        return $this->accessType;
    }

    public function setAccessType($accessType){
        $this->accessType = $accessType;
    }
    
    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }    
 
    public function getUserType(){
        return $this->userType;
    }

    public function setUserType($userType){
        $this->userType = $userType;
    }  
 
    public function getUserMode(){
        if($this->userMode === null){
            $this->userMode = new UserMode($this->$userType, $this->userId);
        }
        return $this->userMode;
    }

    public function setUserMode($userMode){
        if(UserMode::isValidUserMode($userMode)){
            $this->userMode = $userMode;
        }
    }
}

?>