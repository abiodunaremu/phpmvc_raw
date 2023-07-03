<?php

namespace Service\Country;

use Service\Country\Country;
use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;

class CountryHandler{
    private $country;

    public function getCountryById($countryId){
        return $this->getCountry(DatabaseManager::getConnection(), $countryId);
    }
    
    private function getCountry($con, $countyId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM Country WHERE cCountryCode ='".$countryId."';";
        $row="";
        $result = DatabaseManager::mysql_query($query,$con);
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->country = new Country($row["cCountryCode"],
                 $row["vCountryName"]);
            }else{
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "CountryHandler->getCountry; Empty resultset for countryName '".$userId."'",
                    "Requested country does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "CountryHandler->getCountry; Null resultst: ".DatabaseManager::mysql_error($con),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->country;
    }   
    
    public function getAllCountries(){
        return $this->getCountries(DatabaseManager::getConnection());
    }
    
    private function getCountries($con){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM Country;";
        $row="";
        $result = DatabaseManager::mysql_query($query,$con);
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->country = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);
                    
                    array_push($this->country, new Country($row["cCountryCode"],
                    $row["vName"]));
                    // echo "-----".$this->country;
                }
            }else{
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "CountryHandler->getCountriesJson; Empty resultset for countryId '".$countryId."'",
                    "Requested country does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "CountryHandler->getCountriesJson; Null resultst: ".DatabaseManager::mysql_error($con),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->country;
    }
}

?>