<?php

function currencies_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') === 0){  
        include './../Controller/getallcurrencies.php';
    } else {            
        $_POST['source'] = "currencies";
        include './../Controller/showinvalidrequest.php';
    }
}

?>