<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookHandler;
use Service\Book\BookAuthor\BookAuthorHandler;

$bookId = $_GET['bookid'];
$sessionId = $_GET['sessionid'];

$bookHandler = new BookHandler();
$bookAuthorHandler = new BookAuthorHandler();
$book = $bookHandler->getBookById($sessionId, $bookId);
$bookAuthors = $bookAuthorHandler->getBookAuthorByBookCode($sessionId, $bookId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("authors");

if($book === null){
    $responseData->addValue("href", "app/books");
    echo $responseBuilder->setName("getbookdetails")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

foreach($bookAuthors as $bookAuthor){
    $responseDataBookAuthor = new JsonClientResponseData();

    $responseDataBookAuthor->addValue("bookcode",$bookAuthor->getBookCode());
    $responseDataBookAuthor->addValue("bookname",$bookAuthor->getBookName());
    $responseDataBookAuthor->addValue("authorcode",$bookAuthor->getAuthorCode());
    $responseDataBookAuthor->addValue("authorname",$bookAuthor->getAuthorName());

    $responseArray->addResponse($responseDataBookAuthor);
}

$responseData->addValue("name",$book->getName());
$responseData->addValue("description",$book->getDescription());
$responseData->addValue("booktype",$book->getBookType());
$responseData->addValue("bookcategory",$book->getBookCategory());
$responseData->addValue("price",$book->getPrice());
$responseData->addValue("currency",$book->getCurrency());
$responseData->addValue("quantity",$book->getQuantity());
$responseData->addValue("unit",$book->getBookUnit());
$responseData->addValue("filegroupid",$book->getFilegroupCode() == null?"":$book->getFilegroupCode());
$responseData->addValue("weight",$book->getWeight());
$responseData->addValue("length",$book->getLength());
$responseData->addValue("bredth",$book->getBredth());
$responseData->addValue("height",$book->getHeight());
$responseData->addValue("quantityinstock",$book->getQuantityInStock());
$responseData->addValue("stockstatus",$book->getStockStatus());
$responseData->addValue("freeshippingstatus",$book->getFreeShippingStatus());
$responseData->addValue("discount",$book->getDiscount());
$responseData->addValue("bookstatus",$book->getStatus());

// $responseDataB = new JsonClientResponseData();
// $responseDataB->addValue("firstname",$book->getFirstName());
// $responseDataB->addValue("lastname",$book->getLastName());
// $responseDataB->addValue("Gender",$book->getGender());

// $response = new JsonClientResponseArray();
// $response->setName("Book Detials");
// // $response->addResponse("ID",$book->getBookId());
// $response->addResponse($responseDataB);
// $response->addResponse($responseDataB);
// // echo $response;

echo $responseBuilder->setName("getbookdetails")
->setStatus("successful")
->addClientResponse($responseData)
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>