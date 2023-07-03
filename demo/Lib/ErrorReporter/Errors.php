<?php
namespace Lib\ErrorReporter;

// error_reporting(E_ALL);
// ini_set('display_errors',1);

// require_once 'ErrorNode.php';

use Lib\ErrorReporter\ErrorNode;

class Errors implements ErrorNode{

    var $source;
    var $previousNode;
    var $nextNode;
    var $errorMessage;
    var $errorType;
    var $userResponse;

    function setPreviousNode($errorNode){
        $this->previousNode = $errorNode;
    }

    function getPreviousNode(){
        return $this->previousNode;
    }

    function setNextNode($errorNode){
        $this->nextNode = $errorNode;
    }

    function getNextNode(){
        if($this->nextNode){
            return $this->nextNode;
        }else{
            return $this;
        }
    }

    function setErrorMessage($errorMessage){
        $this->errorMessage = $errorMessage;
    }

    function getErrorMessage(){
        return $this->errorMessage;
    }

    function setUserResponse($userResponse){
        $this->userResponse = $userResponse;
    }

    function getUserResponse(){
        return $this->userResponse;
    }

    protected function setErrorType($errorType){
        $this->errorType = $errorType;
    }

    function getErrorType(){
        return $this->errorType;
    }

}
?>