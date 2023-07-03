<?php
namespace Lib\EmailManager;

interface EmailContent{
    function setSubject($setSubject);
    function getSubject();
    function setSenderEmail($senderEmail);
    function getSenderEmail();
    function setSenderName($senderName);
    function getSenderName();
    function setReceiverEmail($receiver);
    function getReceiverEmail();
    function setCarbonCopy($carbonCopy);
    function getCarbonCopy();
    function setBody($body);
    function getBody();
    function setBodyType($bodyType);
    function getBodyType();
    function setBlindCopy($setSubject);
    function getBlindCopy();
}

?>