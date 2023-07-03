<?php
namespace Lib\EmailManager;

use Lib\EmailManager\EmailAPI;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;

class PHPMailerEmailAPI implements EmailAPI
{
    private $emailConnection;
    private $emailContent;

    public function setConnection($emailConnection)
    {
        $this->emailConnection= $emailConnection;
    }

    public function getConnection()
    {
        return $this->emailConnection;
    }

    public function setEmailContent($emailContent)
    {
        $this->emailContent= $emailContent;
    }

    public function getEmailContent()
    {
        return $this->emailContent;
    }

    public function initializeAPI()
    {
        if ($this->emailConnection === null ||
        $this->emailContent === null) {
            $errorNodeFactory = new ErrorNodeFactory();
            $errorNode = $errorNodeFactory->createObjectError(
                "PHPMailerEmailAPI->initializeAPI; emailConnection: "
                .$this->emailConnection.
                "|emailContent: ".$this->emailContent."; is null",
                "Internal error occured. Please try again later"
            );
            ErrorReporter::addNode($errorNode);
            return;
        } else {
        }
    }

    public function sendEmail()
    {
        $errorNodeFactory = new ErrorNodeFactory();

        if ($this->emailConnection === null ||
        $this->emailContent === null) {
            $errorNode = $errorNodeFactory->createObjectError(
                "PHPMailerEmailAPI->sendEmail; emailconnection: "
                .$this->emailConnection.
                "|emailContent: ".$this->emailContent."; is null",
                "Internal error occured while sending email. Please try again later"
            );
            ErrorReporter::addNode($errorNode);
            return;
        }

        $mail = new \PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = $this->emailConnection->getCharSet();

        $mail->Host       = "smtp.ipage.com"; // SMTP server example
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
        $mail->Username   = "automail@milliscript.com"; // SMTP account username example
        $mail->Password   = "badJOKE2*";
        $mail->setFrom('automail@milliscript.com', 'YourBooks.com');
        // $mail->Host       = $this->emailConnection->getHostURL(); // SMTP server example
        // $mail->SMTPDebug  = $this->emailConnection->getSMTPDebug();                     // enables SMTP debug information (for testing)
        // $mail->SMTPAuth   = $this->emailConnection->getSMTPAuth();                  // enable SMTP authentication
        // $mail->Port       = $this->emailConnection->getPort();                    // set the SMTP port for the GMAIL server
        // $mail->Username   = $this->emailConnection->getUsername(); // SMTP account username example
        // $mail->Password   = $this->emailConnection->getPassword();
        // $mail->setFrom($this->emailConnection->getUsername()  ,
        // $this->emailContent->getSenderName());
        $mail->Subject = $this->emailContent->getSubject();
        //Send HTML or Plain Text email
        // $mail->isHTML($this->emailContent->getBodyType()==="HTML");
        $mail->isHTML(true);
        $mail->Body = $this->emailContent->getBody();
        $destArr=explode(",", $this->emailContent->getReceiverEmail());
        $destArrLength=count($destArr);
        for ($destX=0;$destX<$destArrLength;$destX++) {
            $mail->addAddress($destArr[$destX]);
        } //Recipient name is optional
        if (!$mail->send()) {
            $errorNode = $errorNodeFactory->createObjectError(
                "PHPMailerEmailAPI->sendEmail; sending error: ".
                $mail->ErrorInfo."<br/> emcilConnection: "
                .$this->emailConnection.
                "|emailContent: ".$this->emailContent."<br/>",
                "Unable to send email. Please try again later"
            );
            ErrorReporter::addNode($errorNode);
            return;
        // return "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return
            " Message sent! <br/>"."Email connection: ".$this->emailConnection
            ." Email content: ".$this->emailContent;
        }
    }

    public function __toString()
    {
        return "EmailConnection".$this->emailConnection.
        ", EmailContent: ".$this->emailContent;
    }
}
