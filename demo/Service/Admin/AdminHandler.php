<?php
namespace Service\Admin;

// error_reporting(E_ALL);
// ini_set('display_errors',1);

// require_once './../Database/DatabaseManager.php';
// require_once './../ErrorReporter/ErrorReporter.php';
// require_once './../ErrorReporter/ErrorNodeFactory.php';
// require_once './../EmailManager/EmailManager.php';
// require_once './../EmailManager/ResetPasswordEmailContent.php';
// require_once './Admin.php';

use Service\Admin\Admin;
use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;
use Lib\EmailManager\EmailManager;
use Lib\EmailManager\ResetPasswordEmailContent;

class AdminHandler{
    public $admin;
    public $admins;
    public function getAdminById($adminId){
        return $this->getAdmin($adminId);
    }
    
    protected function getAdmin($adminId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwAdmin where vAdminCode='".$adminId."';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->admin = new Admin($row["vAdminCode"], $row["vFullName"]
                , $row["vUsername"], $row["vPassword"], $row["tDescription"]
                , $row["vAddress"], $row["vCountryName"], $row["vPhoneNumber"]
                , $row["vEmail"], $row["vFileGroupID"], $row["dDateCreated"]
                , $row["dLastUpdate"], $row["cStatus"]);
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "AdminHandler->getAdmin; Empty resultset for adminId '".$adminId."'",
                    "The admin requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
        //    $response = "Error! Please login again";
        //    $error = DatabaseManager::mysql_error( );
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "AdminHandler->getAdmin; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->admin;
    }    

    public function searchAdminsByCriteria($criteria)
    {
        return $this->searchAdmins($criteria);
    }

    protected function searchAdmins($criteria){
        
        $errorNodeFactory = new ErrorNodeFactory();

        $query = "SELECT * FROM vwAdmin WHERE vAdminCode ='".$criteria."' "
        ."OR vFullName LIKE '%".$criteria."%' OR tDescription LIKE '%".$criteria."%'"
        ."OR vEmail LIKE '%".$criteria."%' OR vPhoneNumber LIKE '%".$criteria."%';";

        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->admins = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);
                    array_push($this->admins, new Admin($row["vAdminID"], $row["vFirstName"], $row["vMiddleName"],
                    $row["vLastName"], $row["dDOB"], $row["cGender"], $row["vCountryName"], 
                    $row["vPhoneNumber"], $row["vEmail"], "", $row["vAlias"],
                    $row["vMediaGroupID"], $row["dDateCreated"], "","", $row["vAdminTypeName"]));
                }
            }else{
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "AdminHandler->searchAdmins; Empty resultset for criteria '".$criteria."'",
                    "The admin requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "AdminHandler->getAdmin; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->admins;
    }    


    private function generateResetPassword($email){
        $pass2=explode("@", $email);
        $checkLen=strlen($pass2[0]);
        $passInit=rand(1, $checkLen>=3?$checkLen-3:$checkLen);
        $pass1=substr($email, $passInit,$checkLen>=3?3:$checkLen);
        $password=$pass1."%aC*".rand(0,99);
        return $password;
    }

    public function resetAdminPassword($email, $phoneNumber){
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
        $query = "CALL prnUpdAdminPassword('".$email."','".$phone.
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
                            $this->admin = $this->getAdminById($uID);

                            $emailManager = new EmailManager();
                            $emailContent = new ResetPasswordEmailContent(
                                $this->admin->getFirstName()." ".$this->admin->getLastName(), 
                            $email, $pass);
                            $resp =  $emailManager->setEmailContent($emailContent)->
                            useAlagbaseSMTPEmailConnection()->
                            usePHPMailerEmailAPI()->sendEmail();
                        }else {
                            // $response = "Invalid email and phone number combination.";
                            
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "AdminHandler->resetPassword; No associated admin id returned", 
                                "Invalid email and phone number combination."
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    }else{
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "AdminHandler->resetPassword; Empyty result set from query", 
                            "Invalid eamil and/or phone number."
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Invalid adminname and/or phone number";//.$pass.strcmp($row["u_id"],"")."--".$row["s_id"];
                    }
                }else{
                    // $response = "Unable to login. Please try again later.".  mysql_error( );
                    // $error = DatabaseManager::mysql_error( );
                    
                    $errorNode = $errorNodeFactory->createPersistenceError(
                    "AdminHandler->resetPassword; Null result set: ".DatabaseManager::mysql_error( ),
                    "Unable to login. Please try again later."
                    );
                    ErrorReporter::addNode($errorNode); 
                }
            }else {
                // $response = "Query failed";
                // $error = DatabaseManager::mysql_error( );
                
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "AdminHandler->resetPassword; Query failed: ".DatabaseManager::mysql_error(),
                    "Unable to Login. Please try again later"
                    );
                ErrorReporter::addNode($errorNode); 
            }
        else{
            // $response = "Holder failed";
            // $error = DatabaseManager::mysql_error( );
            
            $errorNode = $errorNodeFactory->createPersistenceError(
                "AdminHandler->resetPassword; Holder failed: ".DatabaseManager::mysql_error(),
                "Unable to login. Please try again later"
                );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->admin;
    }
}

// $adminHandler = new AdminHandler();
// $admin = $adminHandler->resetAdminPassword("nectr@gmcail.com","+2348055132352");
// // $admin = $adminHandler->getAdminById("U3");
// echo $admin->getFirstName()." - ok ? <br/>";
// echo $admin->getPassword()."<br/>";
// $adminHandler = new AdminHandler();
// $admin = $adminHandler->resetAdminPassword("'nectr8'@gmwwcail.com","+2348055132352");
// // echo $admin->getFirstName()."ww<br/>";
// // echo $admin->getPassword()."<br/>";
// echo ErrorReporter::getTailNode()->getErrorMessage()."<br/>";
// echo ErrorReporter::getTailNode()->getAdminResponse()."<br/>";
// echo ErrorReporter::getTailNode()->getErrorType()."<br/>";
// echo ErrorReporter::getHeadNode()->getErrorMessage()."<br/>";
// echo ErrorReporter::getHeadNode()->getAdminResponse()."<br/>";
// echo ErrorReporter::getHeadNode()->getErrorType()."<br/>";
// echo ErrorReporter::getHeadNode()->getErrorType()."<br/>";
// echo ErrorReporter::getNodeCount()."<br/>";
// echo $admin->getPassword()."<br/>";
?>
