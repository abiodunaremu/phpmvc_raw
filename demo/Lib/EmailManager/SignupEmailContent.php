<?php
namespace Lib\EmailManager;

    //error_reporting(0);
    // ini_set('display_errors',1);

    // require_once 'EmailCrorReporter/ErrorReporter.php';
    // require_once './../Erontent.php';
    // require_once './../ErrorReporter/ErrorNodeFactory.php';

    use Lib\EmailManager\EmailContent;
    use Lib\ErrorReporter\ErrorReporter;
    use Lib\ErrorReporter\ErrorNodeFactory;

class SignupEmailContent implements EmailContent{

    private $senderEmail = "abiodunaremu@milliscript.com";
    private $senderName = "YourBooks.com";
    private $userEmail;
    private $subject;
    private $body;
    private $bodyType = "plain";
    private $carbonCopy;
    private $blindCopy;
    private $userFullName;
    private $password;


    public function __construct($userFullName, $userEmail, $password){
        $this->setUserFullName($userFullName);
        $this->setReceiverEmail($userEmail);
        $this->setPassword($password);
        $this->setSubject("YourBooks.com - Welcome ".$userFullName);
    }
    
    public function setUserFullName($userFullName){
        $this->userFullName = $userFullName;
    }

    public function getUserFullName(){
        return $this->userFullName;
    }
    
    public function setPassword($password){
        $this->password = $password;
    }

    public function getPassword(){
        return $this->password;
    }
    
    public function setSubject($subject){
        $this->subject = $subject;
    }

    public function getSubject(){
        return $this->subject;
    }

    public function setReceiverEmail($userEmail){
        $this->userEmail = $userEmail;
    }

    public function getReceiverEmail(){
        return $this->userEmail;
    }

    public function setSenderEmail($senderEmail){
        $this->senderEmail = $senderEmail;
    }

    public function getSenderEmail(){
        return $this->senderEmail;
    }
    
    public function setSenderName($senderName){
        $this->senderName = $senderName;
    }

    public function getSenderName(){
        return $this->senderName;
    }    

    public function setBody($body){
        $this->body;
    }

    public function getBody(){
        //initialize the body with user's name and reciever email
        switch($this->bodyType){
            case "plainText":
                $this->initializePlainTextBody();
            case "HTML":
                $this->initializeHTMLBody();
            default:
                $this->initializeHTMLBody();
        }
        return $this->body;
    }

    public function setBodyType($bodyType){
        $this->bodyType;
    }

    public function getBodyType(){
        return $this->bodyType;
    }    

    public function setCarbonCopy($carbonCopy){
        $this->carbonCopy;
    }

    public function getCarbonCopy(){
        return $this->carbonCopy;
    }

    public function setBlindCopy($blindCopy){
        $this->blindCopy;
    }

    public function getBlindCopy(){
        return $this->blindCopy;
    }

    private function initializeHTMLBody(){
        $errorNodeFactory = new ErrorNodeFactory();
        if($this->userFullName === null ||
        $this->password === null ){            
            $errorNode = $errorNodeFactory->createObjectError(
                "SignupEmail->initializeHTMLBody; userfuLLName: "
                .$this->userFullName.
                "|password: ".$this->password."; is null",
                "Internal error occured. Please try again later"
            );
            ErrorReporter::addNode($errorNode); 
            $this->body = null;
            return; 
        }

        $this->body = "Hi ".$this->userFullName."<br/>"."Here are your login details username: ".
        $this->userEmail."<br/>Password: ".$this->password;
        // "<br/>Thank you for using Alagbase.com<br/>"."Yours sincerely<br/>Abiodun Aremu<br/><strong>Founder, Alagbase.com</strong><br/>";
    }

    private function initializePlainTextBody(){
        $errorNodeFactory = new ErrorNodeFactory();
        if($this->body === null || 
        $this->userFullName === null ||
        $this->password === null ){
            
            $errorNode = $errorNodeFactory->createObjectError(
                "SignupEmail->initializeHTMLBody; body|userFullName|password is null",
                "Internal error occured. Please try again later"
            );
            ErrorReporter::addNode($errorNode); 
            $this->body = null;
            
        }else

        $this->body = "Hi ".$this->userFullName."\n"."Here are your login details username: ".
        $this->userName."\nPassword: ".$this->password;
        // "\nThank you for using Alagbase.com\n"."Yours sincerely\nAbiodun Aremu\nFounder, Alagbase.com\n";
    }   

    function __toString(){
        return "senderName: ".$this->senderName.", SenderEmail: ".
        $this->senderEmail.", receiverEmail: ".$this->userEmail.
        ", subject: ".$this->subject.", hody: ".
        $this->body.", user Full name: ".
        $this->userFullName.", password: ".$this->password;
    }
}

// $signupEmail = new SignupEmail("Abiodun Aremu", "arenol@usa.com", "abcd1234");

// echo $signupEmail->getBody()."123<br/>";

// echo ErrorReporter::getTailNode()->getErrorMessage()."<br/>";
// echo ErrorReporter::getTailNode()->getUserResponse()."<br/>";
// echo ErrorReporter::getTailNode()->getErrorType()."<br/>";

?>