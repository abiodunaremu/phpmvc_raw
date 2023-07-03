<?php

function bookunits_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0){
        //register a book categories
        $_POST['bookunitname'] = $decoded['bookunitname'];
        $_POST['sessionid'] = $decoded['sessionid'];
        
        include './../Controller/newbookunit.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+2){
        //retrieve author information
        $_GET['bookunitid'] = $params[sizeof($params)-1];
        include './../Controller/getbookunitdetails.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+1){    
        $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getallbookunits.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+6
        && strcasecmp($params[sizeof($params)-2], 'page') === 0
        && strcasecmp($params[sizeof($params)-4], 'size') === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['size'] = $params[sizeof($params)-3];
            $_GET['page'] = $params[sizeof($params)-1];
        include './../Controller/getpaginatedbookunits.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') 
        === 0 && sizeof($params) === $index_entity+2){    
            $_PUT['sessionid'] = $decoded['sessionid'];  
            $_PUT['value'] = $decoded['value'];
            $_PUT['field'] = $decoded['field'];
            $_PUT['id'] = $params[sizeof($params)-1];
        include './../Controller/updatebookunits.php';
    }  else{
        $_POST['source'] = "bookunits";
        include './../Controller/showinvalidrequest.php';
    }
}
?>