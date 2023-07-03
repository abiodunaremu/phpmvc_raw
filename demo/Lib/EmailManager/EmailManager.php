<?php

/** The builder class for sending emails */

namespace Lib\EmailManager;

use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;
use Lib\EmailManager\PHPMailerEmailAPI;
use Lib\EmailManager\DefaultSMTPEmailConnection;

class EmailManager{
    private $emailContent;
    private $emailConnection;
    private $emailAPI;

    public function setEmailContent($emailContent){
        $this->emailContent = $emailContent;
        return $this;
    }

    //Sets the emailContent content
    public function getEmailEmail(){
        return $this->emailContent;
    }

    //Sets the emailContent emailConnection details
    public function setConnection($emailConnection){
        $this->emailConnection = $emailConnection;
        return $this;
    }

    //Sets the emailContent emailConnection to predefined DefaultSMTPConnection
    public function useDefaultSMTPEmailConnection(){
        $this->emailConnection = new DefaultSMTPEmailConnection();
        return $this;
    }

    public function getConnection(){
        return $this->emailConnection;
    }

    //Sends this emailContent with preferred Email API
    public function setEmailAPI($emailAPI){
        $emailAPI->setEmailContent($this->emailContent);
        $emailAPI->setConnection($this->emailConnection);
        $this->emailAPI = $emailAPI;
        return $this;
    }

    public function getEmailAPI(){
        return $this->emailAPI;
    }

    //Sets the PHPMailerAPI to predefined EmailAPI
    public function usePHPMailerEmailAPI(){
        $emailAPI = new PHPMailerEmailAPI();
        $emailAPI->setEmailContent($this->emailContent);
        $emailAPI->setConnection($this->emailConnection);
        $this->emailAPI = $emailAPI;
        return $this;
    }

    //Sends this emailContent with preferred Email API
    public function sendEmail(){
        
        $errorNodeFactory = new ErrorNodeFactory();

        if($this->emailConnection === null || 
        $this->emailContent === null|| 
        $this->emailAPI === null){         
            $errorNode = $errorNodeFactory->createObjectError(
                "EmailManager->sendEmail; Null value in emailConnection or emailContent: <br/> emailConnection; "
                .$this->emailConnection.
                "<br/> || emailContent; ".$this->emailContent.
                "<br/> || emailAPI; ".$this->emailAPI,
                "Internal error occured while sending email. Please try again later"
            );
            ErrorReporter::addNode($errorNode); 
            return;
        }

        return $this->emailAPI->sendEmail();
    }
}

// $emailManager = new EmailManager();
// $emailContent = new SignupEmailContent("Abiodun Babalola", 
// "arenol@gmail.com", "welcomeHome");

// $resp = "";
// $resp =  $emailManager->setEmailContent($emailContent)->
// useAlagbaseSMTPEmailConnection()->
// setEmailAPI(new PHPMailerEmailAPI())->sendEmail();
// echo $resp;


// $emailManager = new EmailManager();
// $emailContent = new SignupEmailContent("Abiodun Babalola", 
// "arenol@gmail.com", "welcomeHome");
// $resp =  $emailManager->setEmailContent($emailContent)->
//  useAlagbaseSMTPEmailConnection()->
// usePHPMailerEmailAPI()->sendEmail();
// echo $resp;
// if($resp === null){
// echo ErrorReporter::getTailNode()->getErrorMessage()."<br/>";
// echo ErrorReporter::getTailNode()->getUserResponse()."<br/>";
// echo ErrorReporter::getTailNode()->getErrorType()."<br/>";
// }
?>