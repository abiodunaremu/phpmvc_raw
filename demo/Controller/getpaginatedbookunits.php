<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookUnit\BookUnitHandler;

$size = $_GET['size'];
$page = $_GET['page'];
$sessionId = $_GET['sessionid'];

$bookUnitHandler = new BookUnitHandler();
$bookUnitCount = $bookUnitHandler->getBookUnitCount();

if($page -1 >= $bookUnitCount/$size){
    $page = 1;
}

if($size < 3){
    $size = 3;
    $page = 1;
}

$bookUnits = $bookUnitHandler
->getPaginatedBookUnits($sessionId, $size, $page);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("bookunits");

if($bookUnits === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/bookunits");
    echo $responseBuilder->setName("getpaginatedbookunits")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($bookUnits as $bookUnit){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code", $bookUnit->getCode());
    $responseData->addValue("name", $bookUnit->getName());
    $responseData->addValue("datecreated", $bookUnit->getCreatedTime());
    $responseData->addValue("lastupdated", $bookUnit->getLastUpdatedTime());
    $responseData->addValue("status", $bookUnit->getStatus());
    $responseData->addValue("count", $bookUnitCount);
    $responseData->addValue("size", $size);
    $responseData->addValue("page", $page);
    $responseData->addValue("pagename", "*");

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getpaginatedbookunits")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>