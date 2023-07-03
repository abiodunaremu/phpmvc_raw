<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\User\ActiveUser;

$username = $_POST['username'];
$password = $_POST['password'];
$deviceType = "w";
$region = "*";
$startState = "0";

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();
$activeUser = ActiveUser::loginUser($username, 
$password, $startState, $deviceType, $region);

if($activeUser === null || $activeUser::getUser() === null){
    $responseData->addValue("href", "app/user");
    $responseData->addValue("password", $password);
    $responseData->addValue("username", $username);
    echo $responseBuilder->setName("loginuser")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
}else{
    $responseData->addValue("href", "app/user");
    $responseData->addValue("firstname", $activeUser::getUser()->getFirstName());
    $responseData->addValue("lastname", $activeUser::getUser()->getLastName());
    $responseData->addValue("email", $activeUser::getUser()->getEmail());
    $responseData->addValue("country", $activeUser::getUser()->getNationality());
    $responseData->addValue("dateofbirth", $activeUser::getUser()->getDateOfBirth());
    $responseData->addValue("gender", $activeUser::getUser()->getGender());
    $responseData->addValue("phonenumber", $activeUser::getUser()->getPhoneNumber());
    $responseData->addValue("userid", $activeUser::getUser()->getUserId());
    $responseData->addValue("sessionid", $activeUser::getSessionId());
    
    echo $responseBuilder->setName("loginuser")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>