<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Admin\ActiveAdmin;

$username = $_POST['username'];
$password = $_POST['password'];
$cookieCode = $_POST['cookiecode'];
$deviceType = "w";
$region = "*";
$startState = "0";

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();
$activeAdmin = ActiveAdmin::loginAdmin($username, 
$password, $startState, $deviceType, $region, $cookieCode);

if($activeAdmin === null || $activeAdmin::getAdmin() === null){
    $responseData->addValue("href", "app/user");
    $responseData->addValue("password", $password);
    $responseData->addValue("username", $username);
    echo $responseBuilder->setName("loginadmin")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
}else{
    $responseData->addValue("href", "app/user");
    $responseData->addValue("fullname", $activeAdmin::getAdmin()->getFullName());
    $responseData->addValue("description", $activeAdmin::getAdmin()->getDescription());
    $responseData->addValue("email", $activeAdmin::getAdmin()->getEmail());
    $responseData->addValue("country", $activeAdmin::getAdmin()->getCountry());
    $responseData->addValue("phonenumber", $activeAdmin::getAdmin()->getPhoneNumber());
    $responseData->addValue("adminid", $activeAdmin::getAdmin()->getAdminId());
    $responseData->addValue("sessionid", $activeAdmin::getSessionId());
    
    echo $responseBuilder->setName("loginadmin")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>