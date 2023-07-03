<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Author\AuthorHandler;

$size = $_GET['size'];
$page = $_GET['page'];
$sessionId = $_GET['sessionid'];

$authorHandler = new AuthorHandler();
$authorCount = $authorHandler->getAuthorCount();

if($page -1 >= $authorCount/$size){
    $page = 1;
}

if($size < 3){
    $size = 3;
    $page = 1;
}

$authors = $authorHandler
->getPaginatedAuthors($sessionId, $size, $page);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("authors");

if($authors === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/authors");
    echo $responseBuilder->setName("getpaginatedauthors")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($authors as $author){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code", $author->getCode());
    $responseData->addValue("name", $author->getName());
    $responseData->addValue("description", $author->getDescription());
    $responseData->addValue("address", $author->getAddress());
    $responseData->addValue("email", $author->getEmail());
    $responseData->addValue("phonenumber", $author->getPhoneNumber());
    $responseData->addValue("imagegroupid", $author->getFileGroupCode());
    $responseData->addValue("datecreated", $author->getCreatedTime());
    $responseData->addValue("lastupdated", $author->getLastUpdatedTime());
    $responseData->addValue("status", $author->getStatus());
    $responseData->addValue("count", $authorCount);
    $responseData->addValue("size", $size);
    $responseData->addValue("page", $page);
    $responseData->addValue("pagename", "*");

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getpaginatedauthors")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>