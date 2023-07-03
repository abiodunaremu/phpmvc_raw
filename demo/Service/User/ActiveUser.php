<?php
namespace Service\User;

use Service\User\User;
use Service\User\UserHandler;
use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;
use Lib\EmailManager\EmailManager;
use Lib\EmailManager\SignupEmailContent;
use Lib\FileUploadManager\FileUploadManager;
use Lib\FileManager\FileManager;
use Lib\FileManager\FileFormatter\FileFormatterFactory;

class ActiveUser{

    private static $sessionId;

    private static $user;
    private static $activeUser;

    public static function getUser(){
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
        
        if(self::$activeUser != null 
        && self::$sessionId === $sessionId){
            return self::$activeUser;
        }      

        $userId = self::verifyActiveSession( $sessionId);

        if($userId){            
            $userHandler = new UserHandler();
            self::$user = $userHandler->getUserById($userId);
            self::$activeUser = new ActiveUser($sessionId);
        }else{
            return null;
        }

        return self::$activeUser;
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
    
    static function registerUser($firstName,
    $lastName, $phone, $email, $dob,
    $gender, $country, $deviceType, $region){

        $errorNodeFactory = new ErrorNodeFactory();
        if(self::$activeUser != null){
            $errorNode = $errorNodeFactory->createObjectError(
                "User Object already exist: will not create new user ",
                "Internal error occured. Please try again later."
            );
            ErrorReporter::addNode($errorNode);
            return null;
        }

        self::createUser($firstName, $lastName,
        $phone, $email, $dob, $gender, $country,
        $deviceType, $region);

        return self::$activeUser;
    }

    private static function createUser($firstName, $lastName, 
    $phone, $email, $dob, $gender, $country, $deviceType,
    $region){        
        $error = "";
        $response = "";
        $sessionId = "";
        $password = self::generatePassword($email);
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnSignUpUser('".$firstName."','".$lastName.
                "','".$phone."','".$email."','".$password."','".$dob.
                "','".$gender."','".$country."','".$deviceType."','".
                $region."',@sID);";

        if(self::emailExist(  $email) === "exist"){
            // $response = "Email already exist.";
        
            $errorNode = $errorNodeFactory->createObjectError(
                "Class:ActiverUser->createUser; emailExist returned exist = ".self::emailExist(  $email),
                "A user with your email already exists.<br/>If you don't remember your password please click the forgot password link "
            );
            ErrorReporter::addNode($errorNode); 
        }
        else if(self::phoneExist($phone) === "exist"){
            
            // $response = "Phone number already exist.";
        
            $errorNode = $errorNodeFactory->createObjectError(
                "Class:ActiverUser->createUser; phoneExist returned 'exist' = ".self::phoneExist(  $phone),
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
                            self::$user = new User($sessionId, $firstName, "",
                            $lastName, $dob, $gender, $country, 
                            $phone, $email, $password, "", "default", "","", "0");
                            self::$activeUser = new ActiveUser($sessionId);

                            //send notification email to user
                            
                            $emailManager = new EmailManager();
                            $emailContent = new SignupEmailContent($firstName." ".$lastName, 
                            $email, $password);
                            $resp =  $emailManager->setEmailContent($emailContent)->
                            useAlagbaseSMTPEmailConnection()->
                            usePHPMailerEmailAPI()->sendEmail();

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:ActiverUser->createUser; Empty session id returned ".$query,
                                "Unable to complete sign up.<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 

                            // $response = "Invalid session";
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:ActiverUser->createUser; Empty result set returned",
                            "Unable to complete sign up.<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 

                        // $response = "Error! Intenal error occur";
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:ActiverUser->createUser; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to complete sign up.<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Error! Operation fail. Please try again later.";
                    // $error = DatabaseManager::mysql_error( );
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:ActiverUser->createUser; Query failed: ".$query.
                    DatabaseManager::mysql_error( ),
                    "Unable to complete sign up.<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 

                // $response = "Error! Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:ActiverUser->createUser; Holder failed: ".
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
        $query = "SELECT vEmail FROM Customer WHERE vEmail='".$email."';";
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
                "Class:ActiverUser->emailExist; Null resulset: ".
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
        $query = "SELECT vPhone FROM Customer WHERE vPhone='".$phone."';";
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
                "Class:ActiverUser->phoneExist; Null resulset: ".
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
        $query = "CALL prnVerifyActiveUserSession('".$sessionId."',@uID);";
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
                            "Class:ActiverUser->verifyActiveSession; Empty resultset ",
                            "Session expired. Please login again."
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Error! Session expired";
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:ActiverUser->verifyActiveSession; Null resultset ".
                        DatabaseManager::mysql_error( ),
                        "Internal error occured. Please login again."
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Error! Please login again";
                    // $error = DatabaseManager::mysql_error( );
                }
            }else{
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:ActiverUser->verifyActiveSession; Query failed ".
                    DatabaseManager::mysql_error( ),
                    "Internal error occured. Please login again."
                );
                ErrorReporter::addNode($errorNode); 
                // $response = "Error! Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else{
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:ActiverUser->verifyActiveSession; Holder failed ".
                DatabaseManager::mysql_error( ),
                "Internal error occured. Please login again."
            );
            ErrorReporter::addNode($errorNode); 
            // $response = "Error! Holder failed";
            // $error =  DatabaseManager::mysql_error( );
        }
        
        return $userID;
    }

    static function loginUser($username, $password, $startState, $deviceType, $region){
        return self::login($username, $password, $startState, $deviceType, $region);        
    }

    private static function login($username, $password, 
    $startState, $deviceType, $region){
        $queryHolder = "SET @pLink='',@uID='',@sID=''";
        $querySelectHolder = "SELECT @uID AS 'u_id',@pLink AS 'p_Link',@sID AS 's_id'";
        $query = "CALL prnSessionLogin('".$username."','".$password."',
        '".$startState."','".$deviceType."','".$region."',@pLink,@uID,@sID);";
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
                            $userHandler = new UserHandler();

                            self::$user = $userHandler->getUserById($uID);
                            self::$activeUser = new ActiveUser($sID);
                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:ActiverUser->login; Session Id is empty ",
                                "Invalid username and/or password."
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:ActiverUser->login; Resultset is empty ",
                            "Invalid username and/or password."
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Invalid username and/or password";//.$pass.strcmp($row["u_id"],"")."--".$row["s_id"];
                    }                
                } else {
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:ActiverUser->login; Resultset is null ".
                        DatabaseManager::mysql_error( ),
                        "Unable to login. Please try again later."
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Unable to login. Please try again later.";
                    // $error = DatabaseManager::mysql_error( );
                }               
            } else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:ActiverUser->login; Query failed".
                    DatabaseManager::mysql_error( ),
                    "Internal error occured. Please try again later."
                );
                ErrorReporter::addNode($errorNode);
                // $response = "Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else {        
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:ActiverUser->login; Holder failed".
                DatabaseManager::mysql_error( ),
                "Internal error occured. Please try again later."
            );
            ErrorReporter::addNode($errorNode);

            // $response = "Holder failed";
            // $error = DatabaseManager::mysql_error( );
        }
        return self::$activeUser;
    }
    

    static function logoutUser($sessionId){
        return self::logout( $sessionId);        
    }

    private static function logout(  $sessionId){
        $queryHolder="SET @userID=''";
        $querySelectHolder="SELECT @userID AS 'u_id'";  
        $query = "CALL prnUpdCustomerSession('".$sessionId."','0',@userID);";
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
                                "ActiverUser->logout; Usr Id is empty sid=".$sessionId,
                                "Invalid user session"
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "ActiverUser->logout; Resultset is empty ",
                            "Invalid user session"
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Invalid username and/or password";//.$pass.strcmp($row["u_id"],"")."--".$row["s_id"];
                    }                
                } else {
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "ActiverUser->logout; Resultset is null ".
                        DatabaseManager::mysql_error( ),
                        "Unable to logout. Please try again later."
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Unable to login. Please try again later.";
                    // $error = DatabaseManager::mysql_error( );
                }               
            } else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ActiverUser->logout; Query failed".
                    DatabaseManager::mysql_error( ),
                    "Internal error occured. Please try again later."
                );
                ErrorReporter::addNode($errorNode);
                // $response = "Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else {        
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ActiverUser->logout; Holder failed".
                DatabaseManager::mysql_error( ),
                "Internal error occured. Please try again later."
            );
            ErrorReporter::addNode($errorNode);

            // $response = "Holder failed";
            // $error = DatabaseManager::mysql_error( );
        }
        return $uId;
    }

    static function changeUserPassword($sessionId, $currentPssword, $newPassword){
        return self::changePassword($sessionId,
         $currentPssword, $newPassword);        
    }

    private static function changePassword(  $sessionId, $currentPssword, 
    $newPassword){
        $queryHolder="SET @userID='', @report=''";
        $querySelectHolder="SELECT @userID AS 'u_id', @report AS 'p_r'";  
        $query = "CALL prnUpdSessionUserPassword('".$sessionId."','".$currentPssword.
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
                                "ActiverUser->changePassword; p_r is s ",
                                "Invalid user session"
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        }  else if($row["p_r"]==='p') {    
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "ActiverUser->changePassword; p_r is p",
                                "Invalid current password"
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        } else {    
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "ActiverUser->changePassword; User Id is empty ",
                                "Unable to update password. Please enter appropriate values and try again later"
                            );
                            ErrorReporter::addNode($errorNode); 
                            // $response = "Invalid username and/or password";
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "ActiverUser->changePassword; Resultset is empty ",
                            "Invalid user session"
                        );
                        ErrorReporter::addNode($errorNode); 
                        // $response = "Invalid username and/or password";//.$pass.strcmp($row["u_id"],"")."--".$row["s_id"];
                    }                
                } else {+
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "ActiverUser->changePassword; Resultset is null ".
                        DatabaseManager::mysql_error( ),
                        "Unable to update password. Please try again later."
                    );
                    ErrorReporter::addNode($errorNode); 
                    // $response = "Unable to login. Please try again later.";
                    // $error = DatabaseManager::mysql_error( );
                }               
            } else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ActiverUser->changePassword; Query failed".
                    DatabaseManager::mysql_error( ),
                    "Internal error occured. Please try again later."
                );
                ErrorReporter::addNode($errorNode);
                // $response = "Query failed";
                // $error = DatabaseManager::mysql_error( );
            }
        else {        
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ActiverUser->changePassword; Holder failed".
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

// $user = User::getInstance("Abiodun", "Olawale", "Aremu", 
// "31/12/1986", "Male", "Nigeria", "+2348168225549", 
// "arenol@usa.com", "kd99090dd", "Milliscript IT Enterprises",
// "default", "12/10/2018", "mobile", "0");

// // echo "User A: ".$user->getFirstName()."<br/>";

// // $userB = User::getInstance("Akinwande", "Abeni", "Johnson", 
// // "31/12/1986", "Male", "Nigeria", "+2348168225549", 
// // "arenol@usa.com", "kd99090dd", "Milliscript IT Enterprises",
// // "default", "12/10/2018", "mobile", "0");

// // echo "User B: ".$userB->getFirstName()."<br/>";

// // $activerUserC = ActiveUser::loginUser("nectrww@gmcail.com", "wellDONE123", "0", "w", "*");

// // $activerUserC = ActiveUser::registerUser("Kola", "Habib", 
// // "+2348055013090", "habibubibu@gmcail.com", "wellDONE123", "1990/01/20", 
// // "M", "NIGERIA", "w", "*");

// $userHandler = new UserHandler();
// $userC = $userHandler->resetUserPassword("habibubibu@gmcail.com", "+2348055013090");


// // $activerUserC = User::getUserBySession("S5");


// echo ErrorReporter::getTailNode()->getErrorMessage()."--<br/>";
// echo ErrorReporter::getTailNode()->getUserResponse()."**<br/>";
// echo ErrorReporter::getTailNode()->getErrorType()."<br/>";
// // echo ErrorReporter::getHeadNode()->getErrorMessage()."<br/>";
// // echo ErrorReporter::getHeadNode()->getUserResponse()."<br/>";
// // echo ErrorReporter::getHeadNode()->getErrorType()."<br/>";
// echo ErrorReporter::getNodeCount()."<br/>";
// // $Ausere = ActiveUser::getInstance("S3");
// $userC = ActiveUser::getUser();

// echo ActiveUser::getSessionId()."<br/>".
// $userC->getFirstName()."<br/>".
// $userC->getLastName()."<br/>".
// $userC->getEmail()."<br/>".
// $userC->getPassword()."<br/>";

// $activeUser = ActiveUser::getInstance("S5");
// $userC = ActiveUser::getUser();

// echo activeUser::getSessionId()."<br/>".
// $userC->getFirstName()."<br/>".
// $userC->getLastName()."<br/>".
// $userC->getEmail()."<br/>".
// $userC->getPassword();

?>