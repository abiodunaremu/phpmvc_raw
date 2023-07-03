<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookCategory\BookCategoryHandler;

$bookCategoryId = $_GET['bookcategoryid'];
$sessionId = $_GET['sessionid'];

$bookCategoryHandler = new BookCategoryHandler();
$bookCategory = $bookCategoryHandler->getBookCategoryById(
    $sessionId, $bookCategoryId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookCategory === null){
    $responseData->addValue("href", "app/bookcategories");
    echo $responseBuilder->setName("getbookcategorydetails")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("name",$bookCategory->getName());
$responseData->addValue("description",$bookCategory->getDescription());
$responseData->addValue("filegroupcode",$bookCategory->getFileGroupCode());

// $responseDataB = new JsonClientResponseData();
// $responseDataB->addValue("firstname",$bookCategory->getFirstName());
// $responseDataB->addValue("lastname",$bookCategory->getLastName());
// $responseDataB->addValue("Gender",$bookCategory->getGender());

// $response = new JsonClientResponseArray();
// $response->setName("BookCategory Detials");
// // $response->addResponse("ID",$bookCategory->getBookCategoryId());
// $response->addResponse($responseDataB);
// $response->addResponse($responseDataB);
// // echo $response;

echo $responseBuilder->setName("getbookcategorydetails")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>