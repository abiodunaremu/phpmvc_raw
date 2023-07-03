<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookUnit\BookUnitHandler;

$bookUnitId = $_PUT['id'];
$field = $_PUT['field'];
$value = $_PUT['value'];
$sessionId = $_PUT['sessionid'];

$bookUnitHandler = new BookUnitHandler();
$bookUnit = $bookUnitHandler->updateBookUnit(
    $sessionId, $bookUnitId, $field, $value);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookUnit === null){
    $responseData->addValue("href", "app/bookunits");
    $responseData->addValue("field", $field);
    $responseData->addValue("value", $value);
    echo $responseBuilder->setName("updatebookunit")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("sessionid",$sessionId);
$responseData->addValue("bookunitid",$bookUnit);

echo $responseBuilder->setName("updatebookunit")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>