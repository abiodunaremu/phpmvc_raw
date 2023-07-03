<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookHandler;

$size = $_GET['size'];
$page = $_GET['page'];
$sessionId = $_GET['sessionid'];

$bookHandler = new BookHandler();
$bookCount = $bookHandler->getBookCount();

if($page -1 >= $bookCount/$size){
    $page = 1;
}
if($size < 3){
    $size = 3;
    $page = 1;
}

$books = $bookHandler->getPaginatedBooks($sessionId, $size, $page);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("books");

if($books === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/books");
    echo $responseBuilder->setName("getpaginatedbooks")
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
    $responseData->addValue("booktype",$book->getBookType());
    $responseData->addValue("bookcategory",$book->getBookCategory());
    $responseData->addValue("price",$book->getPrice());
    $responseData->addValue("currency",$book->getCurrency());
    $responseData->addValue("quantity",$book->getQuantity());
    $responseData->addValue("bookunit",$book->getBookUnit());
    $responseData->addValue("filegroupid",$book->getFilegroupCode() == null?"":$book->getFilegroupCode());
    $responseData->addValue("weight",$book->getWeight());
    $responseData->addValue("length",$book->getLength());
    $responseData->addValue("bredth",$book->getBredth());
    $responseData->addValue("height",$book->getHeight());
    $responseData->addValue("discount",$book->getDiscount());
    $responseData->addValue("status",$book->getStatus());

    $responseData->addValue("datecreated", $book->getCreatedTime());
    $responseData->addValue("lastupdated",$book->getLastUpdatedTime());
    $responseData->addValue("count",$bookCount);
    $responseData->addValue("size",$size);
    $responseData->addValue("page",$page);
    $responseData->addValue("pagecategory","*");
    $responseData->addValue("pagename","*");

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getpaginatedbooks")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>