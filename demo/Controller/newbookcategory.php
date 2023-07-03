<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookCategory\BookCategoryHandler;

$name = $_POST['name'];
$description = $_POST['description'];
$fileGroupCode = $_POST['filegroupcode'];
$sessionId = $_POST['sessionid'];

$bookCategoryHandler = new BookCategoryHandler();
$bookCategory = $bookCategoryHandler->persistBookCategory(
    $name, $description, $fileGroupCode, $sessionId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookCategory === null){
    $responseData->addValue("href", "app/bookcategorys");
    echo $responseBuilder->setName("newbookcategory")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("name",$name);
$responseData->addValue("filegroupcode",$fileGroupCode);
$responseData->addValue("bookcategoryid",$bookCategory);

echo $responseBuilder->setName("newbookcategory")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>