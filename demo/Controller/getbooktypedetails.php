<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookType\BookTypeHandler;

$bookTypeId = $_GET['booktypeid'];
$sessionId = $_GET['sessionid'];

$bookTypeHandler = new BookTypeHandler();
$bookType = $bookTypeHandler->getBookTypeById(
    $sessionId, $bookTypeId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookType === null){
    $responseData->addValue("href", "app/booktypes");
    echo $responseBuilder->setName("getbooktypedetails")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("name",$bookType->getName());
$responseData->addValue("description",$bookType->getDescription());
$responseData->addValue("filegroupcode",$bookType->getFileGroupCode());

echo $responseBuilder->setName("getbooktypedetails")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>