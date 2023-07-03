<?php
use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;
use Lib\ResponseBuilder\JsonClientResponseArray;
use Service\Admin\AdminHandler;

$adminId = $_GET['adminid'];
$sessionId = $_GET['sessionid'];

$adminHandler = new AdminHandler();
$admin = $adminHandler->getAdminById($sessionId, $adminId);
$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

if($admin === null){
    $responseData->addValue("href", "app/admins");
    echo $responseBuilder->setName("getadmindetails")
    ->setStatus("failed")
    ->addClientResponse($responseData)
    ->build();
    // http_response_code(404);
    return;
}

$responseData->addValue("fullname",$admin->getFullName());
$responseData->addValue("description",$admin->getDescription());
$responseData->addValue("phonenumber",$admin->getPhoneNumber());
$responseData->addValue("email",$admin->getEmail());
$responseData->addValue("filegroupcode",$admin->getFileGroupCode());

// $responseDataB = new JsonClientResponseData();
// $responseDataB->addValue("firstname",$admin->getFirstName());
// $responseDataB->addValue("lastname",$admin->getLastName());
// $responseDataB->addValue("Gender",$admin->getGender());

// $response = new JsonClientResponseArray();
// $response->setName("Admin Detials");
// // $response->addResponse("ID",$admin->getAdminId());
// $response->addResponse($responseDataB);
// $response->addResponse($responseDataB);
// // echo $response;

echo $responseBuilder->setName("getadmindetails")
->setStatus("successful")
->addClientResponse($responseData)
// ->addClientResponse($response)
->build();

http_response_code(200);
?>