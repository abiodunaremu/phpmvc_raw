<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookHandler;
use Service\Book\BookAuthor\BookAuthorHandler;

$name = $_POST['name'];
$description = $_POST['description'];
$bookType = $_POST['booktype'];
$bookCategory = $_POST['bookcategory'];
$price = $_POST['price'];
$currency = $_POST['currency'];
$bookUnit = $_POST['bookunit'];
$authors = $_POST['authors'];
$weight = $_POST['weight'];
$length = $_POST['length'];
$bredth = $_POST['bredth'];
$height = $_POST['height'];
$discount = $_POST['discount'];
$fileGroupCode = $_POST['filegroupcode'];
$sessionId = $_POST['sessionid'];

$bookHandler = new BookHandler();
$bookCode = $bookHandler->persistBook(
    $name, $description
    , $bookType, $bookCategory, $price, $currency, $bookUnit
    , $weight, $length, $bredth, $height, $discount
, $fileGroupCode, $sessionId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($bookCode === null){
    $responseData->addValue("href", "app/books");
    echo $responseBuilder->setName("newbook")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

foreach($authors as $author){

    $bookAuthorHandler = new BookAuthorHandler();
    $bookAuthor = $bookAuthorHandler->persistBookAuthor(
        $name, $author["name"], $sessionId);
        
    // if($bookAuthor === null){
    //     $responseData->addValue("href", "app/bookauthors");
    //     echo $responseBuilder->setName("newbookauthor")
    //     ->setStatus("failed")
    //     ->addClientResponse($responseData)
    //     ->build();
    // }
    $x = 0;
$responseData->addValue("author".$x,$author["name"]);
$x++;
}

$responseData->addValue("name",$name);
$responseData->addValue("bookid",$bookCode);

echo $responseBuilder->setName("newbook")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>