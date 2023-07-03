<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Admin\ActiveAdmin;

$sessionId = $_PUT['sessionid'];
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();
$logoutStatus = ActiveAdmin::logoutAdmin($sessionId);

if($logoutStatus === null || $logoutStatus === ""){
    $responseData->addValue("href", "app/sessions");
    // $responseData->addValue("sid", $sessionId);
    echo $responseBuilder->setName("logoutadmin")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
}else{
    $responseData->addValue("href", "app/sessions");    
    echo $responseBuilder->setName("logoutadmin")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>