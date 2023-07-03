<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Author\AuthorHandler;

$name = $_POST['authorname'];
$description = $_POST['description'];
$address = $_POST['address'];
$phoneNumber = $_POST['phonenumber'];
$email = $_POST['email'];
$fileGroupCode = $_POST['filegroupcode'];
$sessionId = $_POST['sessionid'];

$authorHandler = new AuthorHandler();
$authorid = $authorHandler->persistAuthor(
    $name, $description
    , $address, $phoneNumber, $email
    , $fileGroupCode, $sessionId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($authorid === null){
    $responseData->addValue("href", "app/authors");
    echo $responseBuilder->setName("newauthor")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("name",$name);
$responseData->addValue("authorid",$authorid);

echo $responseBuilder->setName("newauthor")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>