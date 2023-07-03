<?php
namespace Service\Currency;

class Currency{

    private $code;
    private $shortName;
    private $longName;
    private $countryCode;

    public function __construct($code, $shortName, $longName, $countryCode){
        $this->setCode($code);
        $this->setShortName($shortName);
        $this->setLongName($longName);
        $this->setCountryCode($countryCode);
    }

    public function getCode(){
        return $this->code;
    }
    public function setCode($code){
        $this->code = $code;
    }
    
    public function getShortName(){
        return $this->shortName;
    }
    public function setShortName($shortName){
        $this->shortName = $shortName;
    }
    
    public function getLongName(){
        return $this->longName;
    }
    public function setLongName($longName){
        $this->longName = $longName;
    }
    
    public function getCountryCode(){
        return $this->countryCode;
    }
    public function setCountryCode($countryCode){
        $this->countryCode = $countryCode;
    }
}