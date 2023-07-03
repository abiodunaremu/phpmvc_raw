<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Currency\CurrencyHandler;

$currencyHandler = new CurrencyHandler();
$currencies = $currencyHandler->getAllCurrencies();
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("currencies");

if($currencies === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/currencies");
    echo $responseBuilder->setName("getallcurrencies")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($currencies as $currency){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code",$currency->getCode());
    $responseData->addValue("name",$currency->getShortName());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getallcurrencies")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>