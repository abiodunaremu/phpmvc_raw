<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Author\AuthorHandler;

$authorHandler = new AuthorHandler();
$authors = $authorHandler->getAuthors();
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("authors");

if($authors === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/authors");
    echo $responseBuilder->setName("getallauthors")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($authors as $author){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("code",$author->getCode());
    $responseData->addValue("name",$author->getName());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getallauthors")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>