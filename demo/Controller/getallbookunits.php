<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookUnit\BookUnitHandler;

$bookUnitHandler = new BookUnitHandler();
$bookUnits = $bookUnitHandler->getBookUnits();
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("bookunits");

if($bookUnits === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/bookunits");
    echo $responseBuilder->setName("getallbookunits")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($bookUnits as $bookUnit){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code",$bookUnit->getCode());
    $responseData->addValue("name",$bookUnit->getName());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getallbookunits")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>