<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookHandler;

$bookHandler = new BookHandler();
$books = $bookHandler->getBooks();
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("books");

if($books === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/books");
    echo $responseBuilder->setName("getallbooks")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($books as $book){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code",$book->getCode());
    $responseData->addValue("name",$book->getName());
    $responseData->addValue("description",$book->getDescription());
    $responseData->addValue("filegroupid",$book->getFilegroupCode());
    $responseData->addValue("price",$book->getPrice());
    $responseData->addValue("currency",$book->getCurrency());
    $responseData->addValue("discount",$book->getDiscount());
    $responseData->addValue("lastupdatetime",$book->getLastUpdatedTime());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getallbooks")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>