<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookUnit\BookUnitHandler;

$bookUnitId = $_GET['bookunitid'];
$sessionId = $_GET['sessionid'];

$bookUnitHandler = new BookUnitHandler();
$bookUnit = $bookUnitHandler->getBookUnitById(
    $sessionId, $bookUnitId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookUnit === null){
    $responseData->addValue("href", "app/bookunits");
    echo $responseBuilder->setName("getbookunitdetails")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("name",$bookUnit->getName());
$responseData->addValue("description",$bookUnit->getDescription());
$responseData->addValue("filegroupcode",$bookUnit->getFileGroupCode());

echo $responseBuilder->setName("getbookunitdetails")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>