<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Lib\FileManager\File\PersistFileHandler;
use Lib\FileManager\FileType\FileTypeHandler;


$imageFileId = $_GET['persistfileid'];
$sessionId ="";
$persistFileHandler = new PersistFileHandler();
$persistFile = $persistFileHandler->getPersistFileById($sessionId, $imageFileId);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("persistfiles");

if($persistFile === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/persistfiles");
    echo $responseBuilder->setName("getimagefile")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

header("Content-Type: ".$persistFile->getFileTypeName());

readfile($persistFile->getURL());

http_response_code(200);
?>