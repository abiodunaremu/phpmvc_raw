<?php
namespace Lib\ResponseBuilder;

interface ResponseBuilder{
    function setName($name);
    function getName();
    function addClientResponse($clientResponse);    
    function setStatus($status);
}
?>