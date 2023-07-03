<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Lib\FileManager\File\PersistFileHandler;

$persistFileId = $_GET['persistfileid'];
$sessionId = $_GET['sessionid'];

$persistFileHandler = new PersistFileHandler();
$persistFile = $persistFileHandler->getPersistFileById(
    $sessionId, $persistFileId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($persistFile === null){
    $responseData->addValue("href", "app/persistfiles");
    echo $responseBuilder->setName("getpersistfiledetails")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("id",$persistFile->getId());
$responseData->addValue("name",$persistFile->getName());
$responseData->addValue("filegroupid",$persistFile->getFileGroupId());
$responseData->addValue("filetype",$persistFile->getFileTypeName());

echo $responseBuilder->setName("getpersistfiledetails")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>