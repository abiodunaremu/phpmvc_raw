<?php
namespace Lib\ErrorReporter;

interface ErrorNode{
    function setPreviousNode($errorNode);
    function getPreviousNode();
    function setNextNode($errorNode);
    function getNextNode();
    function setErrorMessage($errorNode);
    function getErrorMessage();
    function setUserResponse($userResponse);
    function getUserResponse();
    function getErrorType();
}

?>