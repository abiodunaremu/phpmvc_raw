<?php
namespace Lib\ErrorReporter;

// error_reporting(E_ALL);
// ini_set('display_errors',1);

// require_once 'Errors.php';

use Lib\ErrorReporter\Errors;

class PersistenceError extends Errors{

    public function __construct($errorMessage, $userResponse){
        $this->setErrorMessage($errorMessage);
        $this->setUserResponse($userResponse);
        $this->setErrorType("Persistence Error");
    }
}

?>