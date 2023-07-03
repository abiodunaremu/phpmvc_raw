<?php
namespace Lib\EmailManager;

interface EmailConnection{
    function setHostURL($URL);
    function getHostURL();
    function setUsername($userName);
    function getUsername();
    function setPassword($password);
    function getPassword();
}
?>