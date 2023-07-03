<?php
namespace Lib\EmailManager;

interface EmailAPI{
    function setConnection($connection);
    function getConnection();
    function setEmailContent($email);
    function getEmailContent();
    function sendEmail();
}
?>