<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Admin\ActiveAdmin;

$fullName = $_POST['fullname'];
$username = $_POST['username'];
$password = $_POST['password'];
$description = $_POST['description'];
$address = $_POST['address'];
$country = $_POST['country'];
$phone = $_POST['phonenumber'];
$email = $_POST['email'];
$fileGroupId = $_POST['filegroupid'];

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();
$activeAdmin = ActiveAdmin::registerAdmin($fullName, $username
, $password, $description, $address, $country, $phone, $email
, $fileGroupId);

if($activeAdmin === null){
    $responseData->addValue("href", "app/admins");
    $responseData->addValue("fullname", $fullName);
    echo $responseBuilder->setName("newadmin")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
}else{
    $responseData->addValue("href", "app/admins");
    $responseData->addValue("fullname", $activeAdmin::getAdmin()->getFullName());
    $responseData->addValue("description", $activeAdmin::getAdmin()->getDescription());
    $responseData->addValue("email", $activeAdmin::getAdmin()->getEmail());
    $responseData->addValue("country", $activeAdmin::getAdmin()->getCountry());
    $responseData->addValue("phonenumber", $activeAdmin::getAdmin()->getPhoneNumber());
    
    echo $responseBuilder->setName("newadmin")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>