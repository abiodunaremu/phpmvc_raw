<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Author\AuthorHandler;

$authorId = $_PUT['id'];
$field = $_PUT['field'];
$value = $_PUT['value'];
$sessionId = $_PUT['sessionid'];

$authorHandler = new AuthorHandler();
$author = $authorHandler->updateAuthor(
    $sessionId, $authorId, $field, $value);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($author === null){
    $responseData->addValue("href", "app/authors");
    $responseData->addValue("field", $field);
    $responseData->addValue("value", $value);
    echo $responseBuilder->setName("updateauthor")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("sessionid",$sessionId);
$responseData->addValue("authorid",$author);

echo $responseBuilder->setName("updateauthor")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>