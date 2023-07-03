<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookCategory\BookCategoryHandler;

$size = $_GET['size'];
$page = $_GET['page'];
$sessionId = $_GET['sessionid'];

$bookCategoryHandler = new BookCategoryHandler();
$bookCategoryCount = $bookCategoryHandler->getBookCategoryCount();

if($page -1 >= $bookCategoryCount/$size){
    $page = 1;
}

if($size < 3){
    $size = 3;
    $page = 1;
}

$bookCategorys = $bookCategoryHandler
->getPaginatedBookCategories($sessionId, $size, $page);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("bookcategories");

if($bookCategorys === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/bookcategories");
    echo $responseBuilder->setName("getpaginatedbookcategories")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($bookCategorys as $bookCategory){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code", $bookCategory->getCode());
    $responseData->addValue("name", $bookCategory->getName());
    $responseData->addValue("description", $bookCategory->getDescription());
    $responseData->addValue("datecreated", $bookCategory->getCreatedTime());
    $responseData->addValue("lastupdated", $bookCategory->getLastUpdatedTime());
    $responseData->addValue("status", $bookCategory->getStatus());
    $responseData->addValue("count", $bookCategoryCount);
    $responseData->addValue("size", $size);
    $responseData->addValue("page", $page);
    $responseData->addValue("pagename", "*");

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getpaginatedbookcategories")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>