<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookType\BookTypeHandler;

$name = $_POST['booktypename'];
$sessionId = $_POST['sessionid'];

$bookTypeHandler = new BookTypeHandler();
$bookTypeId = $bookTypeHandler->persistBookType(
    $name, $sessionId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookTypeId === null){
    $responseData->addValue("href", "app/booktypes");
    $responseData->addValue("sessionid", $sessionId);
    echo $responseBuilder->setName("newbooktype")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("booktypename",$name);
$responseData->addValue("booktypeid",$bookTypeId);

echo $responseBuilder->setName("newbooktype")
->setStatus("successful")
->addClientResponse($responseData)
->build();

http_response_code(200);
?>