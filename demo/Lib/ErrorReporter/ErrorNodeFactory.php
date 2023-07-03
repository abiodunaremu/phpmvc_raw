<?php
namespace Lib\ErrorReporter;

// error_reporting(E_ALL);
// ini_set('display_errors',1);

// require_once 'PersistenceError.php';
// require_once 'ObjectError.php';

use Lib\ErrorReporter\PersistenceError;
use Lib\ErrorReporter\ObjectError;

class ErrorNodeFactory{

    function createPersistenceError($errorMessage, $userRespone){
        return new PersistenceError($errorMessage, $userRespone);
    }
    
    function createObjectError($errorMessage, $userRespone){
        return new ObjectError($errorMessage, $userRespone);
    }

}

?>