<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Country\CountryHandler;

$countryHandler = new CountryHandler();
$countries = $countryHandler->getAllCountries();
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("countries");

if($countries === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/countries");
    echo $responseBuilder->setName("getallcountries")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($countries as $country){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code",$country->getCode());
    $responseData->addValue("name",$country->getName());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getallcountries")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>