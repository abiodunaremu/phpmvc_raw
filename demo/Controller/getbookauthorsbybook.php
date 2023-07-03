<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookAuthor\BookAuthorHandler;

$bookId = $_GET['bookid'];
$sessionId = $_GET['sessionid'];

$bookAuthorHandler = new BookAuthorHandler();
$bookAuthors = $bookAuthorHandler->getBookAuthorByBookCode($sessionId, $bookId);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("bookauthors");

if($bookAuthors === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/bookauthors");
    echo $responseBuilder->setName("getbookauthorsbybook")
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

echo $responseBuilder->setName("getbookauthorsbybook")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>