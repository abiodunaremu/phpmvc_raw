<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\User\ActiveUser;

$sessionId = $_POST['sessionid'];
$filePath = $_FILES['userprofilepicture']["tmp_name"];
$fileName = $_FILES['userprofilepicture']["name"];
$fileSize = $file_size=$_FILES["userprofilepicture"]["size"];
$fileExtension = pathinfo($fileName,PATHINFO_EXTENSION);

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

$userHandler = new ActiveUser();
$fileGroupId = $userHandler->uploadProfilePicture($sessionId
, $fileName, $fileExtension, $filePath, $fileSize);

if($fileGroupId === null) {
    $responseData->addValue("href", "app/users");
    $responseData->addValue("sessionid", $sessionId);
    echo $responseBuilder->setName("uploadcustomerprofilepicture")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
} else {
    $responseData->addValue("href", "app/users");
    $responseData->addValue("sessionid", $sessionId);
    $responseData->addValue("filegroupid", $fileGroupId);
        
    echo $responseBuilder->setName("uploadcustomerprofilepicture")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>