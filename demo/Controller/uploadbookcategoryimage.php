<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Book\BookCategory\BookCategoryHandler;

$sessionId = $_POST['sessionid'];
$filePath = $_FILES['file']["tmp_name"];
$fileSize = $_FILES["file"]["size"];
$fileName = $_FILES['file']["name"];
$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

$bookCategoryHandler = new BookCategoryHandler();
$fileGroupId = $bookCategoryHandler->uploadImage($sessionId
, $fileName, $filePath, $fileSize);

if($fileGroupId === null){
    $responseData->addValue("href", "app/bookcategories");
    $responseData->addValue("sessionid", $sessionId);
    $responseData->addValue("filepath", $fileName);
    echo $responseBuilder->setName("uploadbookcategoryimage")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
}else{
    $responseData->addValue("href", "app/bookcategories");
    $responseData->addValue("sessionid", $sessionId);
    // $responseData->addValue("filename", $fileName);
    // $responseData->addValue("filepath", $filePath);
    $responseData->addValue("filegroupid", $fileGroupId);
        
    echo $responseBuilder->setName("uploadbookcategoryimage")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>