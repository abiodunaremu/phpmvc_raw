<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookUnit\BookUnitHandler;

$name = $_POST['bookunitname'];
$sessionId = $_POST['sessionid'];

$bookUnitHandler = new BookUnitHandler();
$bookUnitId = $bookUnitHandler->persistBookUnit(
    $name, $sessionId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookUnitId === null){
    $responseData->addValue("href", "app/bookunits");
    echo $responseBuilder->setName("newbookunit")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("bookunitname",$name);
$responseData->addValue("bookunitid",$bookUnitId);

echo $responseBuilder->setName("newbookunit")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>