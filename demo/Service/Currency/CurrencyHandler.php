<?php

namespace Service\Currency;

use Service\Currency\Currency;
use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;

class CurrencyHandler{
    private $currency;

    public function getCurrencyById($currencyId){
        return $this->getCurrency(DatabaseManager::getConnection(), $currencyId);
    }
    
    private function getCurrency($con, $countyId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM Currency WHERE cCurrencyCode ='".$currencyId."';";
        $row="";
        $result = DatabaseManager::mysql_query($query,$con);
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->currency = new Currency($row["cCurrencyCode"]
                , $row["vShortName"], $row["vLongName"], $row["cCountryCode"]);
            }else{
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "CurrencyHandler->getCurrency; Empty resultset for currencyName '".$userId."'",
                    "Requested currency does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "CurrencyHandler->getCurrency; Null resultst: ".DatabaseManager::mysql_error($con),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->currency;
    }   
    
    public function getAllCurrencies(){
        return $this->getCurrencies(DatabaseManager::getConnection());
    }
    
    private function getCurrencies($con){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM Currency;";
        $row="";
        $result = DatabaseManager::mysql_query($query,$con);
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->currency = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);
                    
                    array_push($this->currency, new Currency($row["cCurrencyCode"]
                    , $row["vShortName"], $row["vLongName"], $row["cCountryCode"]));
                    // echo "-----".$this->currency;
                }
            }else{
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "CurrencyHandler->getCurrenciesJson; Empty resultset for currencyId '".$currencyId."'",
                    "Requested currency does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "CurrencyHandler->getCurrenciesJson; Null resultst: ".DatabaseManager::mysql_error($con),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->currency;
    }
}

?>