<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookHandler;

$bookId = $_PUT['id'];
$field = $_PUT['field'];
$value = $_PUT['value'];
$sessionId = $_PUT['sessionid'];

$bookHandler = new BookHandler();
$book = $bookHandler->updateBook(
    $sessionId, $bookId, $field, $value);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($book === null){
    $responseData->addValue("href", "app/books");
    $responseData->addValue("field", $field);
    $responseData->addValue("value", $value);
    echo $responseBuilder->setName("updatebook")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("sessionid",$sessionId);
$responseData->addValue("bookid",$book);

echo $responseBuilder->setName("updatebook")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>