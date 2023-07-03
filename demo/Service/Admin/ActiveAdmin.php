<?php
namespace Service\Admin;

use Service\Admin\Admin;
use Service\Admin\AdminHandler;
use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;
use Lib\EmailManager\EmailManager;
use Lib\EmailManager\SignupEmailContent;
use Lib\FileUploadManager\FileUploadManager;
use Lib\FileManager\FileManager;
use Lib\FileManager\FileFormatter\FileFormatterFactory;

class ActiveAdmin{

    private static $sessionId;

    private static $user;
    private static $activeAdmin;

    public static function getAdmin(){
        return self::$user;
    }

    public static function getSessionId(){
        return self::$sessionId;
    }

    private static function setSessionId($sessionId){
        self::$sessionId = $sessionId;
    }

    private function __construct($sessionId){
        self::setSessionId($sessionId);
    }

    static function getInstance($sessionId){
        
        if(self::$activeAdmin != null 
        && self::$sessionId === $sessionId){
            return self::$activeAdmin;
        }      

        $userId = self::verifyActiveSession( $sessionId);

        if($userId){            
            $userHandler = new AdminHandler();
            self::$user = $userHandler->getAdminById($userId);
            self::$activeAdmin = new ActiveAdmin($sessionId);
        }else{
            return null;
        }

        return self::$activeAdmin;
    }
    
    // public function __call($methodName, $parameter){
    //     if($methodName === "getInstance" && sizeof($parameter)===1){
    //         $this->newInstance($parameter[0]);
    //     }
    // }

    public static function generatePassword($email){
        $pass2=explode("@", $email);
        $checkLen=strlen($pass2[0]);
        $passInit=rand(1, $checkLen>=3?$checkLen-3:$checkLen);
        $pass1=substr($email, $passInit,$checkLen>=3?3:$checkLen);
        $password=$pass1."&Ab".rand(0,99);
        return $password;
    }
    
    static function registerAdmin($firstName,
    $lastName, $phone, $email, $dob,
    $gender, $country, $deviceType, $region){

        $errorNodeFactory = new ErrorNodeFactory();
        if(self::$activeAdmin != null){
            $errorNode = $errorNodeFactory->createObjectError(
                "Admin Object already exist: will not create new user ",
                "Internal error occured. Please try again later."
            );
            ErrorReporter::addNode($errorNode);
            return null;
        }

        self::createAdmin($firstName, $lastName,
        $phone, $email, $dob, $gender, $country,
        $deviceType, $region);

        return self::$activeAdmin;
    }

    private static function createAdmin($fullName, $username
    , $password, $description, $address, $country, $phone, $email
    , $fileGroupId){        
        $error = "";
        $response = "";
        $sessionId = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnInsAdmin('".$fullName."','".$username.
                "','".$password."','".$description."','".$address."','".$country.
                "','".$phone."','".$email."','".$fileGroupId."',@sID);";

        if(self::emailExist(  $email) === "exist"){
            // $response = "Email already exist.";
        
            $errorNode = $errorNodeFactory->createObjectError(
                "Class:ActiverAdmin->createAdmin; emailExist returned exist = ".self::emailExist(  $email),
                "A user with your email already exists.<br/>If you don't remember your password please click the forgot password link "
            );
            ErrorReporter::addNode($errorNode); 
        }
        else if(self::phoneExist($phone) === "exist"){
            
            // $response = "Phone number already exist.";
        
            $errorNode = $errorNodeFactory->createObjectError(
                "Class:ActiverAdmin->createAdmin; phoneExist returned 'exist' = ".self::phoneExist(  $phone),
                "<strong> A user with your phone number </strong> already exists.<br/>If you don't remember your password please click the forgot password link"
            );
            ErrorReporter::addNode($errorNode); 
        }
        else if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);
                    if($num_results>0) {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row["s_id"],"")!=0) {
                            $sessionId = $row["s_id"];
                            self::$user = new Admin($sessionId, $firstName, "",
                            $lastName, $dob, $gender, $country, 
                            $phone, $email, $password, "", "default", "","", "0");
                            self::$activeAdmin = new ActiveAdmin($sessionId);

                            //send notification email to user
                            
                            $emailManager = new EmailManager();
                            $emailContent = new SignupEmailContent($firstName." ".$lastName, 
                            $email, $password);
                            $resp =  $emailManager->setEmailContent($emailContent)->
                            useAlagbaseSMTPEmailConnection()->
                            usePHPMailerEmailAPI()->sendEmail();

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:ActiverAdmin->createAdmin; Empty session id returned ".$query,
                                "Unable to complete sign up.<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 

                            // $response = "Invalid session";
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:ActiverAdmin->createAdmin; Empty result set returned",
                            "Unable to complete sign up.<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 

                        // $response = "Error! Intenal error occur";
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:ActiverAdmin->createAdmin; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to complete sign up.<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Error! Operation fail. Please try again later.";
                    // $error = DatabaseManager::mysql_error( );
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:ActiverAdmin->createAdmin; Query failed: ".$query.
                    DatabaseManager::mysql_error( ),
                    "Unable to complete sign up.<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 

                // $response = "Error! Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:ActiverAdmin->createAdmin; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to complete sign up.<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 

            // $response = "Error! Holder failed";
            // $error = DatabaseManager::mysql_error( );
        }
        /** TODO */
        //Log response and error to ErrorReporter
        //done
        // echo $response.$error;
    }

    private static function emailExist(  $email){
        $exist="not"; 
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT vEmail FROM Admin WHERE vEmail='".$email."';";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0)
            {    
                $exist="exist";
            }
        }else{            
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:ActiverAdmin->emailExist; Null resulset: ".
                DatabaseManager::mysql_error( ),
                "Internal error occurred. Please try again later."
            );
            ErrorReporter::addNode($errorNode); 
        //    $exist= "Error! Please login again". DatabaseManager::mysql_error( );
        }
        return $exist;
    }    

    private static function phoneExist($phone){
        $exist="not";
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT vPhone FROM Admin WHERE vPhone='".$phone."';";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0)
            {    $exist="exist";
                return $exist;
            }
        }else{            
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:ActiverAdmin->phoneExist; Null resulset: ".
                DatabaseManager::mysql_error( ),
                "Internal error occurred. Please try again later."
            );
            ErrorReporter::addNode($errorNode); 
            // $exist= "Error! Please login again". DatabaseManager::mysql_error( );
        }
           
        return $exist;
    }
    
    private static function verifyActiveSession($sessionId)
    {
        $errorNodeFactory = new ErrorNodeFactory();
        $userID="";$row=""; $response = ""; $error = "";
        $queryHolder="SET @uID=''";
        $querySelectHolder="SELECT @uID AS 'us_id'";  
        $query = "CALL prnVerifyActiveAdminSession('".$sessionId."',@uID);";
        //$query = "SET @uID='AKINBAMI JEUN';";
        if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);      
                    if($num_results>0)
                    {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row['us_id'],"")!=0)
                        {
                            $userID = $row['us_id'];
                        }                        
                        // else
                        //     $userID= $row['us_id'];//.$num_results.length."-Error! Invalid session '".$sessionID."'";
                    }else{
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:ActiverAdmin->verifyActiveSession; Empty resultset ",
                            "Session expired. Please login again."
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Error! Session expired";
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:ActiverAdmin->verifyActiveSession; Null resultset ".
                        DatabaseManager::mysql_error( ),
                        "Internal error occured. Please login again."
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Error! Please login again";
                    // $error = DatabaseManager::mysql_error( );
                }
            }else{
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:ActiverAdmin->verifyActiveSession; Query failed ".
                    DatabaseManager::mysql_error( ),
                    "Internal error occured. Please login again."
                );
                ErrorReporter::addNode($errorNode); 
                // $response = "Error! Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else{
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:ActiverAdmin->verifyActiveSession; Holder failed ".
                DatabaseManager::mysql_error( ),
                "Internal error occured. Please login again."
            );
            ErrorReporter::addNode($errorNode); 
            // $response = "Error! Holder failed";
            // $error =  DatabaseManager::mysql_error( );
        }
        
        return $userID;
    }

    static function loginAdmin($username, $password, $startState, $deviceType, $region, $cookieCode){
        return self::login($username, $password, $startState, $deviceType, $region, $cookieCode);        
    }

    private static function login($username, $password, 
    $startState, $deviceType, $region, $cookieCode){
        $queryHolder = "SET @pLink='',@uID='',@sID=''";
        $querySelectHolder = "SELECT @uID AS 'u_id',@pLink AS 'p_Link',@sID AS 's_id'";
        $query = "CALL prnAdminSessionLogin('".$username."','".$password."',
        '".$startState."','".$deviceType."','".$region."','".$cookieCode."',@uID,@sID);";
        $response = ""; $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);      
                    if($num_results>0)
                    {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row["s_id"],"")!=0)
                        {
                            $sID = $row["s_id"];
                            $uID = $row["u_id"];
                            $pageLink = $row["p_Link"];
                            $submitstatus = "OK";
                            $response = "Login succesful";
                            $userHandler = new AdminHandler();

                            self::$user = $userHandler->getAdminById($uID);
                            self::$activeAdmin = new ActiveAdmin($sID);
                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:ActiverAdmin->login; Session Id is empty ",
                                "Invalid username and/or password."
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:ActiverAdmin->login; Resultset is empty ",
                            "Invalid username and/or password."
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Invalid username and/or password";//.$pass.strcmp($row["u_id"],"")."--".$row["s_id"];
                    }                
                } else {
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:ActiverAdmin->login; Resultset is null ".
                        DatabaseManager::mysql_error( ),
                        "Unable to login. Please try again later."
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Unable to login. Please try again later.";
                    // $error = DatabaseManager::mysql_error( );
                }               
            } else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:ActiverAdmin->login; Query failed".
                    DatabaseManager::mysql_error( ),
                    "Internal error occured. Please try again later."
                );
                ErrorReporter::addNode($errorNode);
                // $response = "Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else {        
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:ActiverAdmin->login; Holder failed".
                DatabaseManager::mysql_error( ),
                "Internal error occured. Please try again later."
            );
            ErrorReporter::addNode($errorNode);

            // $response = "Holder failed";
            // $error = DatabaseManager::mysql_error( );
        }
        return self::$activeAdmin;
    }
    

    static function logoutAdmin($sessionId){
        return self::logout( $sessionId);        
    }

    private static function logout(  $sessionId){
        $queryHolder="SET @userID=''";
        $querySelectHolder="SELECT @userID AS 'u_id'";  
        $query = "CALL prnUpdAdminSession('".$sessionId."','0',@userID);";
        $uId = "";
        $errorNodeFactory = new ErrorNodeFactory();
        if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);      
                    if($num_results>0)
                    {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row["u_id"],"")!=0)
                        {
                                $uId = $row["u_id"];
                        } else {    
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "ActiverAdmin->logout; Usr Id is empty sid=".$sessionId,
                                "Invalid admin session"
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "ActiverAdmin->logout; Resultset is empty ",
                            "Invalid admin session"
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Invalid username and/or password";//.$pass.strcmp($row["u_id"],"")."--".$row["s_id"];
                    }                
                } else {
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "ActiverAdmin->logout; Resultset is null ".
                        DatabaseManager::mysql_error( ),
                        "Unable to logout. Please try again later."
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Unable to login. Please try again later.";
                    // $error = DatabaseManager::mysql_error( );
                }               
            } else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ActiverAdmin->logout; Query failed".
                    DatabaseManager::mysql_error( ),
                    "Internal error occured. Please try again later."
                );
                ErrorReporter::addNode($errorNode);
                // $response = "Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else {        
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ActiverAdmin->logout; Holder failed".
                DatabaseManager::mysql_error( ),
                "Internal error occured. Please try again later."
            );
            ErrorReporter::addNode($errorNode);

            // $response = "Holder failed";
            // $error = DatabaseManager::mysql_error( );
        }
        return $uId;
    }

    static function changeAdminPassword($sessionId, $currentPssword, $newPassword){
        return self::changePassword($sessionId,
         $currentPssword, $newPassword);        
    }

    private static function changePassword(  $sessionId, $currentPssword, 
    $newPassword){
        $queryHolder="SET @userID='', @report=''";
        $querySelectHolder="SELECT @userID AS 'u_id', @report AS 'p_r'";  
        $query = "CALL prnUpdSessionAdminPassword('".$sessionId."','".$currentPssword.
        "','".$newPassword."', @report, @userID);";
        $uId = "";
        $errorNodeFactory = new ErrorNodeFactory();
        if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);      
                    if($num_results>0)
                    {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row["u_id"],"")!=0)
                        {
                                $uId = $row["u_id"];
                        } else if($row["p_r"]==='s') {    
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "ActiverAdmin->changePassword; p_r is s ",
                                "Invalid admin session"
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        }  else if($row["p_r"]==='p') {    
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "ActiverAdmin->changePassword; p_r is p",
                                "Invalid current password"
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        } else {    
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "ActiverAdmin->changePassword; Admin Id is empty ",
                                "Unable to update password. Please enter appropriate values and try again later"
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "ActiverAdmin->changePassword; Resultset is empty ",
                            "Invalid admin session"
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Invalid username and/or password";//.$pass.strcmp($row["u_id"],"")."--".$row["s_id"];
                    }                
                } else {+
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "ActiverAdmin->changePassword; Resultset is null ".
                        DatabaseManager::mysql_error( ),
                        "Unable to update password. Please try again later."
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Unable to login. Please try again later.";
                    // $error = DatabaseManager::mysql_error( );
                }               
            } else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ActiverAdmin->changePassword; Query failed".
                    DatabaseManager::mysql_error( ),
                    "Internal error occured. Please try again later."
                );
                ErrorReporter::addNode($errorNode);
                // $response = "Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else {        
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ActiverAdmin->changePassword; Holder failed".
                DatabaseManager::mysql_error( ),
                "Internal error occured. Please try again later."
            );
            ErrorReporter::addNode($errorNode);

            // $response = "Holder failed";
            // $error = DatabaseManager::mysql_error( );
        }
        return $uId;
    }

    public function uploadProfilePicture($sessionId
    , $fileName, $FileExtension, $filePath, $fileSize){
        $fileCreatorName = "USER_PROFILE_PICTURE";
        $fileManager = FileManager();
        $sourceFile = $fileManager
        ->createLogicFile($fileName, $FileExtension, $filePath, $fileSize);

        $fileFormatterFactory = new FileFormatterFactory();
        $JPEGImageFileFormatter = $fileFormatterFactory
        ->createJPEGImageFileFormatter($sourceFile);


        $fileUploadManager = new FileUploadManager();
        $singleFileUploader = $fileUploadManager->createSingleFileUploader();
        return $singleFileUploader->upload($sessionId, $fileCreatorName
        , $JPEGImageFileFormatter);
    }
}

// $user = Admin::getInstance("Abiodun", "Olawale", "Aremu", 
// "31/12/1986", "Male", "Nigeria", "+2348168225549", 
// "arenol@usa.com", "kd99090dd", "Milliscript IT Enterprises",
// "default", "12/10/2018", "mobile", "0");

// // echo "Admin A: ".$user->getFirstName()."<br/>";

// // $userB = Admin::getInstance("Akinwande", "Abeni", "Johnson", 
// // "31/12/1986", "Male", "Nigeria", "+2348168225549", 
// // "arenol@usa.com", "kd99090dd", "Milliscript IT Enterprises",
// // "default", "12/10/2018", "mobile", "0");

// // echo "Admin B: ".$userB->getFirstName()."<br/>";

// // $activerAdminC = ActiveAdmin::loginAdmin("nectrww@gmcail.com", "wellDONE123", "0", "w", "*");

// // $activerAdminC = ActiveAdmin::registerAdmin("Kola", "Habib", 
// // "+2348055013090", "habibubibu@gmcail.com", "wellDONE123", "1990/01/20", 
// // "M", "NIGERIA", "w", "*");

// $userHandler = new AdminHandler();
// $userC = $userHandler->resetAdminPassword("habibubibu@gmcail.com", "+2348055013090");


// // $activerAdminC = Admin::getAdminBySession("S5");


// echo ErrorReporter::getTailNode()->getErrorMessage()."--<br/>";
// echo ErrorReporter::getTailNode()->getAdminResponse()."**<br/>";
// echo ErrorReporter::getTailNode()->getErrorType()."<br/>";
// // echo ErrorReporter::getHeadNode()->getErrorMessage()."<br/>";
// // echo ErrorReporter::getHeadNode()->getAdminResponse()."<br/>";
// // echo ErrorReporter::getHeadNode()->getErrorType()."<br/>";
// echo ErrorReporter::getNodeCount()."<br/>";
// // $Ausere = ActiveAdmin::getInstance("S3");
// $userC = ActiveAdmin::getAdmin();

// echo ActiveAdmin::getSessionId()."<br/>".
// $userC->getFirstName()."<br/>".
// $userC->getLastName()."<br/>".
// $userC->getEmail()."<br/>".
// $userC->getPassword()."<br/>";

// $activeAdmin = ActiveAdmin::getInstance("S5");
// $userC = ActiveAdmin::getAdmin();

// echo activeAdmin::getSessionId()."<br/>".
// $userC->getFirstName()."<br/>".
// $userC->getLastName()."<br/>".
// $userC->getEmail()."<br/>".
// $userC->getPassword();

?>