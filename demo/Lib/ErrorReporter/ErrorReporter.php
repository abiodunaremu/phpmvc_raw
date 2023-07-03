<?php
namespace Lib\ErrorReporter;

// error_reporting(E_ALL);
// ini_set('display_errors',1);

// include 'ErrorIterator.php';
// require_once 'PersistenceError.php';
// require_once 'EmptyError.php';

use Lib\ErrorReporter\ErrorIterator;

class ErrorReporter implements ErrorIterator{

    public static $headNode;
    public static $tailNode;
    public static $nodeCount = 0;
    public static $errorReporter;
    public static $errorTraceMode = 0;
    public static $responseMessageType = 0;
    
    //Disallow instantiation of ErrorReporter Class
    private function __construct(){
        //HeadNode(emptyError)<-->TailNode(emptyError)
        $emptyError = new EmptyError();
        self::$headNode = new EmptyError();
        self::$tailNode = new EmptyError();
        self::$headNode->setNextNode(self::$tailNode);
        self::$tailNode->setPreviousNode(self::$headNode);
        self::$nodeCount = 0;
    }

    static function resetErrorRepoter(){
        //HeadNode(emptyError)<-->TailNode(emptyError)
        self::$headNode = new EmptyError();
        self::$tailNode = new EmptyError();
        self::$headNode->setNextNode(self::$tailNode);
        self::$tailNode->setPreviousNode(self::$headNode);
        self::$nodeCount = 0;
    }

    static function getInstance(){
        //Singleton object creator of ErrorReporter
        if(self::$errorReporter){
            return self::$errorReporter;
        }else{
            self::$errorReporter = new ErrorReporter();
        }
        return self::$errorReporter;
    }

    static function addNode($errorNode){        
        $emptyError = new EmptyError();

        //Make added node headNode if no existing node
        //When a head node exists
        if(self::$headNode &&
        self::$headNode->getErrorType() != 
        $emptyError->getErrorType()){
            $errorNode->setPreviousNode(self::$tailNode);
            self::$tailNode->setNextNode($errorNode);
            self::$tailNode = $errorNode;
            self::$nodeCount++;
        }else{
            self::$headNode = $errorNode;
            self::$tailNode = $errorNode;
            self::$headNode->setNextNode(self::$tailNode);
            self::$tailNode->setPreviousNode(self::$headNode);
            self::$nodeCount++;
        }
    }
    
    private static function setHeadNode($headNode){
        self::$headNode = $headNode;
    }
    
    static function getHeadNode(){
        return self::$headNode;
    }
    
    static function getTailNode(){
        return self::$tailNode;
    }
    
    private static function setTailNode($tailNode){
        self::$tailNode = $tailNode;
    }

    static function getNodeCount(){
        return self::$nodeCount;
    }

    public static function setErrorTraceMode($errorTraceMode){
        self::$errorTraceMode = $errorTraceMode;
    }
    public static function getErrorTraceMode(){
        return self::$errorTraceMode;
    }

    public static function setResponseMessageType($responseMessageType){
        self::$responseMessageType = $responseMessageType;
    }
    public static function getResponseMessageType(){
        return self::$responseMessageType;
    }
}
ErrorReporter::getInstance();
// $errorReporter = ErrorReporter::getInstance();

// $error = $errorReporter::getHeadNode();
// echo $error->getErrorType()."<br/>";
// echo $error->getErrorMessage()."<br/>";

// $errorNode = new DatabaseError("Unable to connect to db");
// echo $errorNode->getErrorType()."<br/>";
// $errorReporter::addNode($errorNode);
// $error = $errorReporter::getHeadNode();
// echo $error->getErrorMessage()."<br/>";

// $errorNode = new DatabaseError("Missing argument");
// echo $errorNode->getErrorType()."<br/>";
// $errorReporter::addNode($errorNode);
// $error = $errorReporter::getHeadNode();
// echo $error->getErrorMessage()."<br/>";
// echo $error->getNextNode()->getErrorMessage()."<br/>";

// $error = $errorReporter::getTailNode();
// echo $error->getErrorMessage()."<br/>";
// echo $error->getNextNode()->getErrorMessage()."<br/>";

// echo $errorReporter::getNodeCount();

// $errorNode = new DatabaseError("Invalid session");
// echo $errorNode->getErrorType()."<br/>";
// $errorReporter::addNode($errorNode);
// $error = $errorReporter::getHeadNode();
// echo $error->getErrorMessage()."<br/>";
// echo $error->getNextNode()->getErrorMessage()."<br/>";

// ErrorReporter::resetErrorRepoter();

// $error = $errorReporter::getTailNode();
// echo $error->getErrorMessage()."<br/>";
// echo $error->getNextNode()->getErrorMessage()."<br/>";

// echo $errorReporter::getNodeCount();
?>