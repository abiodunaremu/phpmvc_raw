<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("access-control-allow-origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("access-control-allow-methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Or_+_igin, X-Requested-With, Content-Type, Accept, Authorization,Access-Control-Allow-Origin,access-control-allow-headers");
header("access-control-allow-headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,Access-Control-Allow-Origin,access-control-allow-headers,access-control-allow-methods");

// error_reporting(E_ALL);
ini_set('display_errors',0);

include './../Autoloader.php';
include './countries.php';
include './sessions.php';
include './users.php';
include './authors.php';
include './admins.php';
include './adminsessions.php';
include './bookcategories.php';
include './booktypes.php';
include './bookunits.php';
include './books.php';
include './persistfiles.php';
include './currencies.php';
include './bookauthors.php';

$autoloader = new Autoloader();

use Lib\ErrorReporter\ErrorReporter;
ErrorReporter::setErrorTraceMode(1);
ErrorReporter::setResponseMessageType(0);

//Make sure that the content type of the POST request has been set to application/json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    $pend= 'Content type must be: application/json';
}else{
}

//Make sure that it is a POST request.
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    $pend= 'Request method must be POST!';
}

//Receive the RAW post data.
$content = trim(file_get_contents("php://input"));
// echo "<---Content is: ".$content."---->";
//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);

//If json_decode failed, the JSON is invalid.
if(!is_array($decoded)){
    $pend= 'Received content contained invalid JSON!';
}

$path = $_SERVER['REQUEST_URI'];
$_SERVER['REQUEST_METHOD'] = strtoupper($decoded['requesttype']);
//  echo "...".$decoded['requesttype'].$_SERVER['REQUEST_METHOD']."-->Request Method";
$params     = explode("/", $path);
$safe_pages = array("users", "sessions", "countries", "documentation"
, "authors", "admins", "adminsessions", "bookcategories", "booktypes"
, "bookunits", "books", "persistfiles", "currencies", "bookauthors");
                
$index_entity_local = 3;
$index_entity_live = 2;
$index_entity = $index_entity_local;

if(count($params)>=1 && in_array($params[$index_entity], $safe_pages)) {
    if(strcmp($params[$index_entity], "countries") == 0){
        countries_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "sessions") == 0){
        sessions_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "users") == 0){
        users_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "authors") == 0){
        authors_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "admins") == 0){
        admins_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "adminsessions") == 0){
        adminsessions_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "bookcategories") == 0){
        bookcategories_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "booktypes") == 0){
        booktypes_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "bookunits") == 0){
        bookunits_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "books") == 0){
        books_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "persistfiles") == 0){
        persistfiles_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "currencies") == 0){
        currencies_api($params, $decoded, $index_entity);
    } else if(strcmp($params[$index_entity], "bookauthors") == 0){
        bookauthors_api($params, $decoded, $index_entity);
    } else {
        $_POST['source'] = "api";
        include './../Controller/showinvalidrequest.php';
    }
} else if($params[$index_entity] === "documentation"){
    $_POST['source'] = "documentation";
    header("Content-Type: text/html");
    include './../Documentation/index.php';
} else {
    $_POST['source'] = "api";
    include './../Controller/showinvalidrequest.php';
}
?>