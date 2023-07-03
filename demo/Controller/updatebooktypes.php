<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookType\BookTypeHandler;

$bookTypeId = $_PUT['id'];
$field = $_PUT['field'];
$value = $_PUT['value'];
$sessionId = $_PUT['sessionid'];

$bookTypeHandler = new BookTypeHandler();
$bookType = $bookTypeHandler->updateBookType(
    $sessionId, $bookTypeId, $field, $value);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookType === null){
    $responseData->addValue("href", "app/booktypes");
    $responseData->addValue("field", $field);
    $responseData->addValue("value", $value);
    echo $responseBuilder->setName("updatebooktype")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("sessionid",$sessionId);
$responseData->addValue("booktypeid",$bookType);

echo $responseBuilder->setName("updatebooktype")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>