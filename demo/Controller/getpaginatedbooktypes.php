<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookType\BookTypeHandler;

$size = $_GET['size'];
$page = $_GET['page'];
$sessionId = $_GET['sessionid'];

$bookTypeHandler = new BookTypeHandler();
$bookTypeCount = $bookTypeHandler->getBookTypeCount();

if($page -1 >= $bookTypeCount/$size){
    $page = 1;
}

if($size < 3){
    $size = 3;
    $page = 1;
}

$bookTypes = $bookTypeHandler
->getPaginatedBookTypes($sessionId, $size, $page);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("booktypes");

if($bookTypes === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/booktypes");
    echo $responseBuilder->setName("getpaginatedbooktypes")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($bookTypes as $bookType){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code", $bookType->getCode());
    $responseData->addValue("name", $bookType->getName());
    $responseData->addValue("datecreated", $bookType->getCreatedTime());
    $responseData->addValue("lastupdated", $bookType->getLastUpdatedTime());
    $responseData->addValue("status", $bookType->getStatus());
    $responseData->addValue("count", $bookTypeCount);
    $responseData->addValue("size", $size);
    $responseData->addValue("page", $page);
    $responseData->addValue("pagename", "*");

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getpaginatedbooktypes")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>