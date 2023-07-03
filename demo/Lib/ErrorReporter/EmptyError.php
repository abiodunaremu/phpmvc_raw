<?php
namespace Lib\ErrorReporter;

// error_reporting(E_ALL);
// ini_set('display_errors',1);

// require_once 'Errors.php';

use Lib\ErrorReporter\Errors;

class EmptyError extends Errors{

    public function __construct(){
        $this->setErrorMessage("No error yet");
        $this->setErrorType("Empty");
    }
}

?>