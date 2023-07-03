<?php

function books_api($params, $decoded, $index_entity){ 
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0){
        $_POST['name'] = $decoded['name'];
        $_POST['description'] = $decoded['description'];
        $_POST['booktype'] = $decoded['booktype'];
        $_POST['bookcategory'] = $decoded['bookcategory'];
        $_POST['price'] = $decoded['price'];
        $_POST['currency'] = $decoded['currency'];
        $_POST['bookunit'] = $decoded['bookunit'];
        $_POST['authors'] = $decoded['authors'];
        $_POST['weight'] = $decoded['weight'];
        $_POST['length'] = $decoded['length'];
        $_POST['bredth'] = $decoded['bredth'];
        $_POST['height'] = $decoded['height'];
        $_POST['discount'] = $decoded['discount'];
        $_POST['filegroupcode'] = $decoded['filegroupcode'];
        $_POST['sessionid'] = $decoded['sessionid'];        
        include './../Controller/newbook.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity + 2){
        //retrieve book information
        $_GET['bookid'] = $params[sizeof($params)-1];
        $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getbookdetails.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+1){    
        $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getallbooks.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+10
        && strcasecmp($params[sizeof($params)-4], 'size') === 0
        && strcasecmp($params[sizeof($params)-2], 'page') === 0
        && strcasecmp($params[sizeof($params)-5], '*') === 0
        && strcasecmp($params[sizeof($params)-7], '*') === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['page'] = $params[sizeof($params)-1];
            $_GET['size'] = $params[sizeof($params)-3];
        include './../Controller/getpaginatedbooks.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+10
        && strcasecmp($params[sizeof($params)-4], 'size') === 0
        && strcasecmp($params[sizeof($params)-2], 'page') === 0
        && strcasecmp($params[sizeof($params)-5], '*') !== 0
        && strcasecmp($params[sizeof($params)-6], 'category') ===0
        && strcasecmp($params[sizeof($params)-7], '*') === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['page'] = $params[sizeof($params)-1];
            $_GET['size'] = $params[sizeof($params)-3];
            $_GET['category'] = $params[sizeof($params)-5];
        include './../Controller/getpaginatedbooksbycategory.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+10
        && strcasecmp($params[sizeof($params)-4], 'size') === 0
        && strcasecmp($params[sizeof($params)-2], 'page') === 0
        && strcasecmp($params[sizeof($params)-5], '*') === 0
        && strcasecmp($params[sizeof($params)-6], 'category') ===0
        && strcasecmp($params[sizeof($params)-7], '*') !== 0
        && strcasecmp($params[sizeof($params)-8], 'name') === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['page'] = $params[sizeof($params)-1];
            $_GET['size'] = $params[sizeof($params)-3];
            $_GET['bookname'] = $params[sizeof($params)-7];
        include './../Controller/getpaginatedbooksbyname.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+10
        && strcasecmp($params[sizeof($params)-4], 'size') === 0
        && strcasecmp($params[sizeof($params)-2], 'page') === 0
        && strcasecmp($params[sizeof($params)-5], '*') !== 0
        && strcasecmp($params[sizeof($params)-6], 'category') ===0
        && strcasecmp($params[sizeof($params)-7], '*') !== 0
        && strcasecmp($params[sizeof($params)-8], 'name') === 0){    
            $_GET['sessionid'] = $decoded['sessionid'];
            $_GET['page'] = $params[sizeof($params)-1];
            $_GET['size'] = $params[sizeof($params)-3];
            $_GET['category'] = $params[sizeof($params)-5];
            $_GET['bookname'] = $params[sizeof($params)-7];
        include './../Controller/getpaginatedbooksbynamecategory.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+6
        && strcasecmp($params[sizeof($params)-4], 'latest') === 0
        && strcasecmp($params[sizeof($params)-2], 'page') === 0){    
            $_GET['size'] = $params[sizeof($params)-3];   
            $_GET['page'] = $params[sizeof($params)-1];
            $_GET['sessionid'] = $decoded['sessionid'];
        include './../Controller/getlatestbooks.php';
    }  else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') 
        === 0 && sizeof($params) === $index_entity+2){    
            $_PUT['sessionid'] = $decoded['sessionid'];  
            $_PUT['value'] = $decoded['value'];
            $_PUT['field'] = $decoded['field'];
            $_PUT['id'] = $params[sizeof($params)-1];
        include './../Controller/updatebooks.php';
    }  else if(sizeof($params) === $index_entity+3
        && strcasecmp($params[sizeof($params)-1], 'images') === 0){
        include './../Controller/uploadbookimages.php';
    } else{
        $_POST['source'] = "books";
        include './../Controller/showinvalidrequest.php';
    }
}
?>