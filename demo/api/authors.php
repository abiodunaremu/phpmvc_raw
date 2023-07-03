<?php

function authors_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0){
        //register an author
        $_POST['authorname'] = $decoded['authorname'];
        $_POST['description'] = $decoded['description'];
        $_POST['address'] = $decoded['address'];
        $_POST['phonenumber'] = $decoded['phonenumber'];
        $_POST['email'] = $decoded['email'];
        $_POST['filegroupcode'] = $decoded['filegroupcode'];
        $_POST['sessionid'] = $decoded['sessionid'];
        
        include './../Controller/newauthor.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+2){
        //retrieve author information
        $_GET['authorid'] = $params[sizeof($params)-1];
        include './../Controller/getauthordetails.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+1){    
        $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getallauthors.php';
    } else if(sizeof($params) === $index_entity+3
    && strcasecmp($params[sizeof($params)-1], 'image') === 0){
        //upload author images
        include './../Controller/uploadauthorimage.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+6
        && strcasecmp($params[sizeof($params)-2], 'page') === 0
        && strcasecmp($params[sizeof($params)-4], 'size') === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['size'] = $params[sizeof($params)-3];
            $_GET['page'] = $params[sizeof($params)-1];
        include './../Controller/getpaginatedauthors.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') 
        === 0 && sizeof($params) === $index_entity+2){    
            $_PUT['sessionid'] = $decoded['sessionid'];  
            $_PUT['value'] = $decoded['value'];
            $_PUT['field'] = $decoded['field'];
            $_PUT['id'] = $params[sizeof($params)-1];
        include './../Controller/updateauthors.php';
    }  else{            
        $_POST['source'] = "authors";
        include './../Controller/showinvalidrequest.php';
    }
}
?>