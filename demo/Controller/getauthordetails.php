<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Author\AuthorHandler;

$authorId = $_GET['authorid'];
$sessionId = $_GET['sessionid'];

$authorHandler = new AuthorHandler();
$author = $authorHandler->getAuthorById($sessionId, $authorId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($author === null){
    $responseData->addValue("href", "app/authors");
    echo $responseBuilder->setName("getauthordetails")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("name",$author->getName());
$responseData->addValue("description",$author->getDescription());
$responseData->addValue("address",$author->getAddress());
$responseData->addValue("phonenumber",$author->getPhoneNumber());
$responseData->addValue("email",$author->getEmail());
$responseData->addValue("filegroupcode",$author->getFileGroupCode());

// $responseDataB = new JsonClientResponseData();
// $responseDataB->addValue("firstname",$author->getFirstName());
// $responseDataB->addValue("lastname",$author->getLastName());
// $responseDataB->addValue("Gender",$author->getGender());

// $response = new JsonClientResponseArray();
// $response->setName("Author Detials");
// // $response->addResponse("ID",$author->getAuthorId());
// $response->addResponse($responseDataB);
// $response->addResponse($responseDataB);
// // echo $response;

echo $responseBuilder->setName("getauthordetails")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>