<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookCategory\BookCategoryHandler;

$bookCategoryId = $_PUT['id'];
$field = $_PUT['field'];
$value = $_PUT['value'];
$sessionId = $_PUT['sessionid'];

$bookCategoryHandler = new BookCategoryHandler();
$bookCategory = $bookCategoryHandler->updateBookCategory(
    $sessionId, $bookCategoryId, $field, $value);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookCategory === null){
    $responseData->addValue("href", "app/bookcategories");
    $responseData->addValue("field", $field);
    $responseData->addValue("value", $value);
    echo $responseBuilder->setName("updatebookcategory")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("sessionid",$sessionId);
$responseData->addValue("bookcategoryid",$bookCategory);

echo $responseBuilder->setName("updatebookcategory")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>