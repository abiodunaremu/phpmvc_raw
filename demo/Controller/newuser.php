<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\User\ActiveUser;

$firstName = $_POST['firstname'];
$lastName = $_POST['lastname'];
$phone = $_POST['phonenumber'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$country = $_POST['country'];
$dob = $_POST['dateofbirth'];
$deviceType = "w";
$region = "*";

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();
$activeUser = ActiveUser::registerUser($firstName, $lastName, 
$phone, $email, $dob, $gender, $country, $deviceType, $region);

if($activeUser === null){
    $responseData->addValue("href", "app/users");
    $responseData->addValue("dateofbirth", $dob);
    echo $responseBuilder->setName("newuser")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();    
    // http_response_code(404);
    return;
}else{
    $responseData->addValue("href", "app/users");
    $responseData->addValue("firstname", $activeUser::getUser()->getFirstName());
    $responseData->addValue("lastname", $activeUser::getUser()->getLastName());
    $responseData->addValue("email", $activeUser::getUser()->getEmail());
    $responseData->addValue("password", $activeUser::getUser()->getPassword());
    
    echo $responseBuilder->setName("newuser")
    ->setStatus("successful")
    ->addClientResponse($responseData)
    ->build();    
    http_response_code(201);
}

?>