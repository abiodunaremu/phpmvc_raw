<?php

function booktypes_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0){
        //register a book categories
        $_POST['booktypename'] = $decoded['booktypename'];
        $_POST['sessionid'] = $decoded['sessionid'];
        
        include './../Controller/newbooktype.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+2){
        //retrieve author information
        $_GET['booktypeid'] = $params[sizeof($params)-1];
        include './../Controller/getbooktypedetails.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+1){    
        $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getallbooktypes.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+3
        && $params[sizeof($params)-1] === "book"){    
        $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getbooktypeswithbook.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+6
        && strcasecmp($params[sizeof($params)-2], 'page') === 0
        && strcasecmp($params[sizeof($params)-4], 'size') === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['size'] = $params[sizeof($params)-3];
            $_GET['page'] = $params[sizeof($params)-1];
        include './../Controller/getpaginatedbooktypes.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') 
        === 0 && sizeof($params) === $index_entity+2){    
            $_PUT['sessionid'] = $decoded['sessionid'];  
            $_PUT['value'] = $decoded['value'];
            $_PUT['field'] = $decoded['field'];
            $_PUT['id'] = $params[sizeof($params)-1];
        include './../Controller/updatebooktypes.php';
    } else{
        $_POST['source'] = "booktypes";
        include './../Controller/showinvalidrequest.php';
    }
}
?>