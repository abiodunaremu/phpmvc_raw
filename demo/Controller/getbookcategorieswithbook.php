<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookCategory\BookCategoryHandler;

$bookCategoryHandler = new BookCategoryHandler();
$bookCategorys = $bookCategoryHandler
->getBookCategoriesWithBook();
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("bookcategories");

if($bookCategorys === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/bookcategories");
    echo $responseBuilder->setName("getbookcategorieswithboo")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($bookCategorys as $bookCategory){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code",$bookCategory->getCode());
    $responseData->addValue("name",$bookCategory->getName());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getbookcategorieswithbook")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>