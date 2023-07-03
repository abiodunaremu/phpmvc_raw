<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Author\AuthorHandler;

$sessionId = $_POST['sessionid'];
$filePath = $_FILES['file']["tmp_name"];
$fileSize = $_FILES["file"]["size"];
$fileName = $_FILES['file']["name"];
$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

$authorHandler = new AuthorHandler();
$fileGroupId = $authorHandler->uploadImage($sessionId
, $fileName, $filePath, $fileSize);

if($fileGroupId === null){
    $responseData->addValue("href", "app/authors");
    $responseData->addValue("sessionid", $sessionId);
    $responseData->addValue("filename", $fileName);
    echo $responseBuilder->setName("uploadauthorimage")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
}else{
    $responseData->addValue("href", "app/authors");
    $responseData->addValue("sessionid", $sessionId);
    $responseData->addValue("filegroupid", $fileGroupId);
        
    echo $responseBuilder->setName("uploadauthorimage")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>