<?php
namespace Service\User;

// error_reporting(E_ALL);
// ini_set('display_errors',1);

// require_once './../Database/DatabaseManager.php';
// require_once './../ErrorReporter/ErrorReporter.php';
// require_once './../ErrorReporter/ErrorNodeFactory.php';
// require_once './../EmailManager/EmailManager.php';
// require_once './../EmailManager/ResetPasswordEmailContent.php';
// require_once './User.php';

use Service\User\User;
use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;
use Lib\EmailManager\EmailManager;
use Lib\EmailManager\ResetPasswordEmailContent;

class UserHandler{
    public $user;
    public $users;
    public function getUserById($userId){
        return $this->getUser($userId);
    }
    
    protected function getUser($userId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwCustomer where vCustomerID='".$userId."';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->user = new User($row["vCustomerID"], $row["vFirstName"], $row["vMiddleName"],
                $row["vLastName"], $row["dDOB"], $row["cGender"], $row["vCountryName"], 
                $row["vPhoneNumber"], $row["vEmail"], "",
                $row["vMediaGroupID"], $row["dDateCreated"], "","");
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "UserHandler->getUser; Empty resultset for userId '".$userId."'",
                    "The user requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
        //    $response = "Error! Please login again";
        //    $error = DatabaseManager::mysql_error( );
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "UserHandler->getUser; Null resultset: ".DatabaseManager::mysql_error(),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->user;
    }    

    public function searchUsersByCriteria($criteria) {
        return $this->searchUsers($criteria);
    }

    protected function searchUsers($criteria){
        
        $errorNodeFactory = new ErrorNodeFactory();

        $query = "SELECT * FROM vwCustomer WHERE vCustomerID='".$criteria."' "
        ."OR vFirstName LIKE '%".$criteria."%' OR vLastName LIKE '%".$criteria."%'"
        ."OR vEmail LIKE '%".$criteria."%' OR vPhoneNumber LIKE '%".$criteria."%';";

        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->users = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);
                    array_push($this->users, new User($row["vUserID"], $row["vFirstName"], $row["vMiddleName"],
                    $row["vLastName"], $row["dDOB"], $row["cGender"], $row["vCountryName"], 
                    $row["vPhoneNumber"], $row["vEmail"], "", $row["vAlias"],
                    $row["vMediaGroupID"], $row["dDateCreated"], "","", $row["vUserTypeName"]));
                }
            }else{
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "UserHandler->searchUsers; Empty resultset for criteria '".$criteria."'",
                    "The user requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "UserHandler->getUser; Null resultset: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->users;
    }    


    private function generateResetPassword($email){
        $pass2=explode("@", $email);
        $checkLen=strlen($pass2[0]);
        $passInit=rand(1, $checkLen>=3?$checkLen-3:$checkLen);
        $pass1=substr($email, $passInit,$checkLen>=3?3:$checkLen);
        $password=$pass1."%aC*".rand(0,99);
        return $password;
    }

    public function resetUserPassword($email, $phoneNumber){
        return $this->resetPassword(
        $email, $phoneNumber);
    }

    private function resetPassword($email, $phone){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $response = "";
        $submitstatus = "FAIL";
        $uID = "";
        $pass = $this->generateResetPassword($email);
        $queryHolder = "SET @uID=''";
        $querySelectHolder = "SELECT @uID AS 'u_id'";
        $query = "CALL prnUpdUserPassword('".$email."','".$phone.
        "','".$pass."',@uID);";
        
        if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);      
                    if($num_results > 0)
                    {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row["u_id"], "") != 0)
                        {
                            $uID = $row["u_id"];
                            $this->user = $this->getUserById($uID);

                            $emailManager = new EmailManager();
                            $emailContent = new ResetPasswordEmailContent(
                                $this->user->getFirstName()." ".$this->user->getLastName(), 
                            $email, $pass);
                            $resp =  $emailManager->setEmailContent($emailContent)->
                            useAlagbaseSMTPEmailConnection()->
                            usePHPMailerEmailAPI()->sendEmail();
                        }else {
                            // $response = "Invalid email and phone number combination.";
                            
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "UserHandler->resetPassword; No associated user id returned", 
                                "Invalid email and phone number combination."
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    }else{
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "UserHandler->resetPassword; Empyty result set from query", 
                            "Invalid eamil and/or phone number."
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Invalid username and/or phone number";//.$pass.strcmp($row["u_id"],"")."--".$row["s_id"];
                    }
                }else{
                    // $response = "Unable to login. Please try again later.".  mysql_error( );
                    // $error = DatabaseManager::mysql_error( );
                    
                    $errorNode = $errorNodeFactory->createPersistenceError(
                    "UserHandler->resetPassword; Null result set: ".DatabaseManager::mysql_error( ),
                    "Unable to login. Please try again later."
                    );
                    ErrorReporter::addNode($errorNode); 
                }
            }else {
                // $response = "Query failed";
                // $error = DatabaseManager::mysql_error( );
                
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "UserHandler->resetPassword; Query failed: ".DatabaseManager::mysql_error(),
                    "Unable to Login. Please try again later"
                    );
                ErrorReporter::addNode($errorNode); 
            }
        else{
            // $response = "Holder failed";
            // $error = DatabaseManager::mysql_error( );
            
            $errorNode = $errorNodeFactory->createPersistenceError(
                "UserHandler->resetPassword; Holder failed: ".DatabaseManager::mysql_error(),
                "Unable to login. Please try again later"
                );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->user;
    }
}

// $userHandler = new UserHandler();
// $user = $userHandler->resetUserPassword("nectr@gmcail.com","+2348055132352");
// // $user = $userHandler->getUserById("U3");
// echo $user->getFirstName()." - ok ? <br/>";
// echo $user->getPassword()."<br/>";
// $userHandler = new UserHandler();
// $user = $userHandler->resetUserPassword("'nectr8'@gmwwcail.com","+2348055132352");
// // echo $user->getFirstName()."ww<br/>";
// // echo $user->getPassword()."<br/>";
// echo ErrorReporter::getTailNode()->getErrorMessage()."<br/>";
// echo ErrorReporter::getTailNode()->getUserResponse()."<br/>";
// echo ErrorReporter::getTailNode()->getErrorType()."<br/>";
// echo ErrorReporter::getHeadNode()->getErrorMessage()."<br/>";
// echo ErrorReporter::getHeadNode()->getUserResponse()."<br/>";
// echo ErrorReporter::getHeadNode()->getErrorType()."<br/>";
// echo ErrorReporter::getHeadNode()->getErrorType()."<br/>";
// echo ErrorReporter::getNodeCount()."<br/>";
// echo $user->getPassword()."<br/>";
?>
