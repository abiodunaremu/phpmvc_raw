<?php
namespace Service\Country;

class Country{

    private $code;
    private $name;
    public function __construct($code, $name){
        $this->setCode($code);
        $this->setName($name);
    }

    public function getCode(){
        return $this->code;
    }

    public function setCode($code){
        $this->code = $code;
    }
    
    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }
}