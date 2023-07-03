<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookHandler;

$sessionId = $_POST['sessionid'];
$filePath = $_FILES['file']["tmp_name"];
$fileSize = $_FILES["file"]["size"];
$fileName = $_FILES['file']["name"];

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

$bookHandler = new BookHandler();
$fileGroupId = $bookHandler->uploadImages($sessionId
, $fileName, $filePath, $fileSize);

if($fileGroupId === null){
    $responseData->addValue("href", "app/books");
    $responseData->addValue("sessionid", $sessionId);
    $responseData->addValue("numberoffiles", sizeof($fileName));
    echo $responseBuilder->setName("uploadbookimages")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
}else{
    $responseData->addValue("href", "app/books");
    $responseData->addValue("sessionid", $sessionId);
    $responseData->addValue("filegroupid", $fileGroupId);
    $responseData->addValue("numberoffiles", sizeof($fileName));
        
    echo $responseBuilder->setName("uploadbookimages")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>