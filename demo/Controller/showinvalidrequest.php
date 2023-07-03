<?php

use Lib\ResponseBuilder\JsonResponseBuilder;
use Lib\ResponseBuilder\JsonClientResponseData;

$source = $_POST['source'];

$responseData = new JsonClientResponseData();
$responseBuilder = new JsonResponseBuilder();

$responseData->addValue("href", "app/documentation");
$responseData->addValue("source", $source);

echo $responseBuilder->setName("showinvalidrequest")
->setStatus(0)
->addClientResponse($responseData)
->build();    
?>