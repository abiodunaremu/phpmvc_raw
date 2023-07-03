<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\User\UserHandler;

$userid = $_GET['userid'];

$userHandler = new UserHandler();
$user = $userHandler->getUserById($userid);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($user === null){
    $responseData->addValue("href", "app/users");
    echo $responseBuilder->setName("getuserdetails")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("firstname",$user->getFirstName());
$responseData->addValue("middlename",$user->getMiddleName());
$responseData->addValue("lastname",$user->getLastName());
$responseData->addValue("Gender",$user->getGender());
$responseData->addValue("County",$user->getNationality());
$responseData->addValue("dateofbirth",$user->getDateOfBirth());
$responseData->addValue("phonenumber",$user->getPhoneNumber());
$responseData->addValue("email",$user->getEmail());
$responseData->addValue("alias",$user->getAlias());
$responseData->addValue("image",$user->getImage());
$responseData->addValue("datesignup",$user->getDateSignup());
$responseData->addValue("accountstatus",$user->getStatus());
$responseData->addValue("usertype",$user->getUserType());

// $responseDataB = new JsonClientResponseData();
// $responseDataB->addValue("firstname",$user->getFirstName());
// $responseDataB->addValue("lastname",$user->getLastName());
// $responseDataB->addValue("Gender",$user->getGender());

// $response = new JsonClientResponseArray();
// $response->setName("User Detials");
// // $response->addResponse("ID",$user->getUserId());
// $response->addResponse($responseDataB);
// $response->addResponse($responseDataB);
// // echo $response;

echo $responseBuilder->setName("getuserdetails")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>