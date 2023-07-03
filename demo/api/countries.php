<?php

function countries_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') === 0){            
        $_GET['userid'] = $params[sizeof($params)-1];
        include './../Controller/getallcountries.php';
    } else {            
        $_POST['source'] = "countries";
        include './../Controller/showinvalidrequest.php';
    }
}

?>