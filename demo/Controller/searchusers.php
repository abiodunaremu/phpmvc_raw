<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\User\UserHandler;

$criteria = $_GET['criteria'];

$userHandler = new UserHandler();
$users = $userHandler->searchUsersByCriteria($criteria);
$responseBuilder = new JsonResponseBuilder();
$responseArray = new JsonClientResponseArray();
$responseArray->setName("users");

if($users === null){
    $responseData = new JsonClientResponseData();
    $responseData->addValue("href", "app/users/search");
    echo $responseBuilder->setName("searchusers")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(500);
    return;
}

foreach($users as $user){
    $responseData = new JsonClientResponseData();


    $responseData->addValue("userid",$user->getUserId());
    $responseData->addValue("firstname",$user->getFirstName());
    // $responseData->addValue("middlename",$user->getMiddleName());
    $responseData->addValue("lastname",$user->getLastName());
    $responseData->addValue("Gender",$user->getGender());
    $responseData->addValue("County",$user->getNationality());
    $responseData->addValue("dateofbirth",$user->getDateOfBirth());
    $responseData->addValue("phonenumber",$user->getPhoneNumber());
    $responseData->addValue("email",$user->getEmail());
    // $responseData->addValue("alias",$user->getAlias());
    $responseData->addValue("image",$user->getImage());
    $responseData->addValue("datesignup",$user->getDateSignup());
    $responseData->addValue("accountstatus",$user->getStatus());
    $responseData->addValue("usertype",$user->getUserType());

    $responseArray->addResponse($responseData);
}

echo $responseBuilder->setName("searchusers")
->setStatus("successful")
->addClientResponse($responseArray)
->build();

http_response_code(200);
?>