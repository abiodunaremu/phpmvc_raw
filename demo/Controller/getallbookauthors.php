<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookAuthor\BookAuthorHandler;

$authorId = $_GET['authorid'];
$bookId = $_GET['bookid'];
$criteria = $_GET['criteria'];
$sessionId = $_GET['sessionid'];

$bookAuthorHandler = new BookAuthorHandler();
if($criteria === "book"){
    $bookAuthors = $bookAuthorHandler->getBookAuthorByBookCode($bookId);
}else{
    $bookAuthors = $bookAuthorHandler->getBookAuthorByAuthorCode($authorId);
}
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("bookauthors");

if($bookAuthors === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/bookauthors");
    echo $responseBuilder->setName("getallbookauthors")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($bookAuthors as $bookAuthor){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("bookcode",$bookAuthor->getBookCode());
    $responseData->addValue("bookname",$bookAuthor->getBookName());
    $responseData->addValue("authorcode",$bookAuthor->getAuthorCode());
    $responseData->addValue("authorname",$bookAuthor->getAuthorName());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getallbookauthors")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>