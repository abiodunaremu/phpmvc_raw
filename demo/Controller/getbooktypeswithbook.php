<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookType\BookTypeHandler;

$bookTypeHandler = new BookTypeHandler();
$bookTypes = $bookTypeHandler->getBookTypesWithBook();
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("booktypes");

if($bookTypes === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/booktypes");
    echo $responseBuilder->setName("getbooktypeswithbook")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($bookTypes as $bookType){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code",$bookType->getCode());
    $responseData->addValue("name",$bookType->getName());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getbooktypeswithbook")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>