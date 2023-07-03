<?php
namespace Lib\ResponseBuilder;

interface ClientResponseData{
    function addValue($key, $response);
    function getData();
    function getValueCount();
}
?>