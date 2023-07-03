<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookHandler;

$size = $_GET['size'];
$page = $_GET['page'];
$category = $_GET['category'];
$sessionId = $_GET['sessionid'];

$bookHandler = new BookHandler();
$bookCount = $bookHandler->getBookByCategoryCount($category);

if($page-1 >= $bookCount/$size){
    $page = 1;
}
if($size < 3){
    $size = 3;
    $page = 1;
}

$books = $bookHandler->getPaginatedBooksbyCategory($sessionId, $size, $page, $category);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("books");

if($books === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/books");
    echo $responseBuilder->setName("getpaginatedbooksbycategory")
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
    $responseData->addValue("category",$book->getBookCategory());
    $responseData->addValue("filegroupid",$book->getFilegroupCode() == null?"":$book->getFilegroupCode());
    $responseData->addValue("price",$book->getPrice());
    $responseData->addValue("currency",$book->getCurrency());
    $responseData->addValue("discount",$book->getDiscount());
    $responseData->addValue("lastupdatetime",$book->getLastUpdatedTime());
    $responseData->addValue("count",$bookCount);
    $responseData->addValue("size",$size);
    $responseData->addValue("page",$page);
    $responseData->addValue("pagecategory",$category);
    $responseData->addValue("pagename","*");

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getpaginatedbooksbycategory")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>