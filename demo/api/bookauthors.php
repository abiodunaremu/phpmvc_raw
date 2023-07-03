<?php
function bookauthors_api($params, $decoded, $index_entity){
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity + 4
        && $params[sizeof($params)-2] === "book"){
        //retrieve book information
        $_GET['bookid'] = $params[sizeof($params)-1];
        $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getbookauthorsbybook.php';
    } else {            
        $_POST['source'] = "bookauthors";
        include './../Controller/showinvalidrequest.php';
    }
}
?>