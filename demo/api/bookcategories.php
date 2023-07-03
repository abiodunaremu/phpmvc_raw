<?php

function bookcategories_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0){
        //register a book categories
        $_POST['name'] = $decoded['name'];
        $_POST['description'] = $decoded['description'];
        $_POST['filegroupcode'] = $decoded['filegroupcode'];
        $_POST['sessionid'] = $decoded['sessionid'];
        
        include './../Controller/newbookcategory.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+2){
        //retrieve author information
        $_GET['bookcategoryid'] = $params[sizeof($params)-1];
        include './../Controller/getbookcategorydetails.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+1){    
        $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getallbookcategories.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+4
        && strcasecmp($params[sizeof($params)-2], 'booktype') 
        === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['booktypeid'] = $params[sizeof($params)-1];
        include './../Controller/getbookcategoriesbybooktype.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+3
        && strcasecmp($params[sizeof($params)-1], 'book') 
        === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['booktypeid'] = $params[sizeof($params)-1];
        include './../Controller/getbookcategorieswithbook.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+6
        && strcasecmp($params[sizeof($params)-2], 'page') === 0
        && strcasecmp($params[sizeof($params)-4], 'size') === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['size'] = $params[sizeof($params)-3];
            $_GET['page'] = $params[sizeof($params)-1];
        include './../Controller/getpaginatedbookcategories.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') 
        === 0 && sizeof($params) === $index_entity+2){    
            $_PUT['sessionid'] = $decoded['sessionid'];  
            $_PUT['value'] = $decoded['value'];
            $_PUT['field'] = $decoded['field'];
            $_PUT['id'] = $params[sizeof($params)-1];
        include './../Controller/updatebookcategories.php';
    } else if(sizeof($params) === $index_entity+3
    && strcasecmp($params[sizeof($params)-1], 'image') === 0){
        //upload book category  images
        include './../Controller/uploadbookcategoryimage.php';
    } else{
        $_POST['source'] = "bookcategories";
        include './../Controller/showinvalidrequest.php';
    }
}
?>