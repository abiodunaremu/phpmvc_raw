<?php

define("BASEPATH", realpath(dirname(__FILE__)));

class Autoloader{

    public function autoloadFile($class){
        $fileName = BASEPATH.'/'.str_replace('\\', '/', $class).'.php';
        include $fileName;
    }

    public function __construct(){
        spl_autoload_register(function($class){
            $fileName = BASEPATH."\\".$class.'.php';
            // $fileName = BASEPATH."/".str_replace('\\', '/', $class).'.php';
            include $fileName;
        });
    }
}
?>
