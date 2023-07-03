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

if($page - 1 >= $bookCount/$size){
    $page = 1;
}
if($size < 2){
    $size = 2;
    $page = 1;
}

if($page > 1 && $page >= $bookCount/$size && $bookCount % $size != 0){
    $page = $page - 1;
}


$bookHandler = new BookHandler();
$books = $bookHandler->getLatestBooks($sessionId, $size, $page);

$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("books");

if($books === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/books");
    echo $responseBuilder->setName("getlatestbooks")
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
    $responseData->addValue("filegroupid",$book->getFilegroupCode() == null?"":$book->getFilegroupCode());
    $responseData->addValue("price",$book->getPrice());
    $responseData->addValue("currency",$book->getCurrency());
    $responseData->addValue("discount",$book->getDiscount());
    $responseData->addValue("lastupdatetime",$book->getLastUpdatedTime());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getlatestbooks")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>