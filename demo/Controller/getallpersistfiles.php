<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Lib\FileManager\File\PersistFileHandler;

$groupId = $_GET['groupid'];

$persistFileHandler = new PersistFileHandler();
$persistFiles = $persistFileHandler->getPersistFiles($groupId);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("persistfiles");

if($persistFiles === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/persistfiles");
    echo $responseBuilder->setName("getallpersistfiles")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($persistFiles as $persistFile){
    $responseData = new JsonClientResponseData();

    $responseData->addValue("id",$persistFile->getId());
    $responseData->addValue("name",$persistFile->getName());
    $responseData->addValue("groupid",$persistFile->getFileGroupId());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("getallpersistfiles")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>